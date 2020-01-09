<?php 
namespace JBAShop\Payment;

/*Just a simple class for handling Braintree operations*/

Class Braintree {
	
	var $input = null;
	
	public function getInputs() {
		$array =  json_decode( file_get_contents('php://input') );
	
		if(empty($array)) {
			$array = (object) $_REQUEST;
		}
	
		return $array;
	
	}
	 
	
	/*
	 * TOKENIZES A PAYMENT METHOD
	 */
	public function getTokenFromNonce($nonce, $btCustomer, $data = array() ) {
	
		
		try {

			$paymentMethod = array(
					'customerId' => $btCustomer,
					'paymentMethodNonce' => $nonce,
					'options' => array(
							'verifyCard' => true,
							'failOnDuplicatePaymentMethod' => false
					)
			);
			
			if(!empty($data['billing'])) {
				$paymentMethod['billingAddress'] = $data['billing'];
			}
	
			//DEVICE DATA COMES FROM POSTED FORM
			//https://developers.braintreepayments.com/ios+ruby/guides/advanced-fraud-tools/overview
			if(!empty(@$data['deviceData'])) {
				$paymentMethod['deviceData'] = $data['deviceData'];
			}
			
			$result = \Braintree_PaymentMethod::create($paymentMethod);
	
			// CHECKING RESULT OF PAYMENT METHOD CREATION
			if($result->success) {
				
	
							if(!empty($result->paymentMethod->verifications)) {
									

								switch (get_class($result->paymentMethod)) {
				
									case 'Braintree_PayPalAccount':
										$paymentData = array(
										'payment_method' => 'braintree',
										'token' =>  $result->paymentMethod->token,
										'type' => 'paypal',
										'email' => $result->paymentMethod->email
										);
										break;
				
				
									case 'Braintree_CreditCard':
										/*
										 * KOUNT INTEGRATION
										 *
										 * $result->riskData->decision as be 4 Strings
										 * Not Evaluated, Approve, Review, and Decline.
										 *
										 * WHAT SHOULD WE DO IN EACH CASE?
										 *
										 * BRAINTREE DOCS SUGGEST
										 * $result->paymentMethod->riskData
										 *
										 * BUT I HAD TO USE
										 *
										 * $result->paymentMethod->verifications[0]['riskData']
										 */
										if(!empty(@$result->paymentMethod->verifications[0]['riskData']['decision']) && \Base::instance()->get('DEBUG') == 0) {
								
											switch ($result->paymentMethod->verifications[0]['riskData']['decision']) {
												case 'Decline':
													
													$this->setError('Payment has been declined, please contact customer service', 'K501');
	
													break;
												case 'Approve':
													//WHAT SHOULD WE DO ON APPROVE?
													break;
				
												case 'Not Evaluated':
													//WHAT SHOULD WE DO ON APPROVE?
													break;
				
												case 'Review' :
													//MARK THE ORDER FOR REVIEW
													break;
				
				
											}
				
										}
											
										//BASED OFF THE KOUNT RESULTS WE WILL SET THE PAYMENT DATA.
										$paymentData = array(
												'payment_method' => 'braintree',
												'type' => 'card',
												'token' =>  $result->paymentMethod->token,
												'cardType' => $result->paymentMethod->cardType,
												'maskedNumber' => $result->paymentMethod->maskedNumber,
												'riskDataid' => @$result->paymentMethod->verifications[0]['riskData']['id'],
												'riskDataDecision' => @$result->paymentMethod->verifications[0]['riskData']['decision']
										);
											
										//==========================================================================
				
										if(@$data->billing->countryCodeAlpha2 == 'US')  {
				
											switch ($result->paymentMethod->verifications[0]['cvvResponseCode']) {
												case 'M':
												case 'S':
													//MATCH VALID
													break;
												case 'N':
													$this->setError('CVV does not match');
													break;
														
												case 'N':
												case 'I':
												default:
													$this->setError('CVV does not match');
													break;
											}
												
											//==========================================================================
												
				
				
											switch ($result->paymentMethod->verifications[0]['avsPostalCodeResponseCode']) {
												case 'M':
												case 'S':
													//MATCH VALID
													break;
												case 'N':
												case 'U':
													$this->setError('The postal code provided does not match the information on file.');
													break;
				
												case 'N':
												case 'I':
												default:
													$this->setError('The postal code provided does not match');
													break;
											}
												
											//==========================================================================
				
											switch ($result->paymentMethod->verifications[0]['avsStreetAddressResponseCode']) {
												case 'M':
												case 'S': //S means not supported
													//MATCH VALID
													break;
												case 'N':
												case 'U':
													$this->setError('Billing Street Address does not match');
													break;
														
												case 'N':
												case 'I':
												default:
													$this->setError('Billing Street Address does not match');
													break;
											}
												
										}
											
											
										$expires = \DateTime::createFromFormat('m/Y', $result->paymentMethod->expirationDate);
										$now     = new \DateTime();
											
										if ($expires < $now) {
											$this->setError('Card is Expired');
										}
											
											
										break;
				
								} 
				//NO VERFICATIONS
				} else {
						
						switch (get_class($result->paymentMethod)) {
								
							case 'Braintree_PayPalAccount':
								$paymentData = array(
								'payment_method' => 'braintree',
								'token' =>  $result->paymentMethod->token,
								'type' => 'paypal',
								'email' => $result->paymentMethod->email
								);
								break;
									
							case 'Braintree_CreditCard':
								$paymentData = array(
								'payment_method' => 'braintree',
								'type' => 'card',
								'token' =>  $result->paymentMethod->token,
								'cardType' => $result->paymentMethod->cardType,
								'maskedNumber' => $result->paymentMethod->maskedNumber
								);
								break;
						}
					}
	
	
	
			} else {
	
				//CREATING THE CARD FAILED
				$errorMessage ='Error : ';
				foreach($result->errors->deepAll() AS $error) {
					$errorMessage .= $error->code .' - '. $error->message;
					\Dsc\Mongo\Collections\Logs::add('ERROR CREATING PAYMENT: '. $error->code .  ' :' . $error->message, 'ERROR', 'ERROR');
				}
				
				
				$verification = @$result->creditCardVerification;
	
				if(!empty($verification)) {
					switch ($verification->status) {
						case 'processor_declined':
							$this->setError($verification->processorResponseText);
							break;
	
						case 'gateway_rejected':
							if(!empty($verification->gatewayRejectionReason)) {
								$this->setError($verification->gatewayRejectionReason);
							} else {
								
								
								if(!empty($verification->cvvResponseCode)) {
									
									switch ($verification->cvvResponseCode) {
										case 'M':
										case 'S':
											//MATCH VALID
											break;
										case 'N':
											$this->setError('CVV does not match');
											break;
									
										case 'N':
										case 'I':
										default:
											$this->setError('CVV does not match');
											break;
									}
								}

								//==========================================================================
								if(!empty($verification->avsPostalCodeResponseCode)) {
									switch ($verification->avsPostalCodeResponseCode) {
										case 'M':
									case 'S':
										//MATCH VALID
										break;
									case 'N':
									case 'U':
										$this->setError('The postal code provided does not match the information on file.');
										break;
									case 'N':
									case 'I':
									default:
										$this->setError('The postal code provided does not match');
										break;
									}
								}
								
								//==========================================================================	
								if(!empty($verification->avsStreetAddressResponseCode)) {
									switch ($verification->avsStreetAddressResponseCode) {
										case 'M':
										case 'S': //S means not supported
											//MATCH VALID
											break;
										case 'N':
										case 'U':
											$this->setError('Billing Street Address does not match');
											break;
									
										case 'N':
										case 'I':
										default:
											$this->setError('Billing Street Address does not match');
											break;
									}
								}

								$this->setError('Gateway Rejection: Please Double Check Billing Address Is Correct.');
							}
							break;
						default:
							;
							break;
					}
				}
					
				
				$this->setError($errorMessage);
					
			}
	
	
	
		} catch (\Exception $e) {
			
			$this->setError($e->getMessage());
		}

		//DEVICE DATA COMES FROM POSTED FORM
		//https://developers.braintreepayments.com/ios+ruby/guides/advanced-fraud-tools/overview
		if(!empty(@$data['deviceData'])) {
			$paymentData['deviceData'] = $data['deviceData']; 
		}
		
		return $paymentData;
	}
	
	
	
	public function clientKey() {
	
		$data = $this->getInputs();
		$inputs = array();
		if(!empty($data->customernumber)) {
			try {
				$customer = \Braintree_Customer::find($data->customernumber);
				if(!empty(@$customer->id)) {
					$inputs = array( "customerId" => $customer->id );
				}
			} catch (\Exception $e) {
	
			}
		}
	
		echo  \Braintree_ClientToken::generate($inputs);
		exit;
	}
	
	
	
	public function getAddresses() {
		
	}
	
	public function getPaymentMethods() {
	
	}
	
	/**
	 * @param string $fullName
	 * @return array
	 */
	protected function parseName($fullName)
	{
		$name = explode(' ', trim($fullName), 2);
	
		if(empty($name[0])) {
			$name[0] = '';
		}
	
		if(empty($name[1])) {
			$name[1] = '';
		}
	
		return [
				'first' => $name[0],
				'last'  => $name[1]
		];
	}
	
	public function chargeOrder($order, $generatePaymentXML = false, $amount = null, $token = null) {
			try {
				if ($order instanceof \JBAShop\Models\Orders == false) {
					$order = (new \JBAShop\Models\Orders)->setState('filter.id', $order)->getItem();
				}
				
				if(empty($order)) {
					$this->setError('Invalid Order: Order Not Found.');
				}
				
				
				if(!empty($amount)) {
					
				} else {
					$amount = $order->totalDue();
				}
				
				
				
				if(!empty($token)) {
					$paymentToken = $token;
				} else {
					switch (count($order->{'payment_methods'})) {
						case 0:
						$this->setError('Trying to Charge but there is no payment Methods');
						break;
						case 1;
						$paymentToken = $order->get('payment_methods.0.token');
						break;
						
						default:
						$this->setError('There is more than one payment method, but you did not provide a token');
						break;
					}
					
				}
				
				
				
					
				if($amount <= 0) {
					
					return true;
					//we need to fail and do something
				}
				
				
				
				
				
					
				$customer = [];
				
			
				$customer = array_filter($customer);
				
				$channel = 'www.rallysportdirect.com';
				
				
				$name = $this->parseName( $order->{'billing_address.name'});
				$billing = [];
				$billing['streetAddress'] = $order->{'billing_address.line_1'};
				$billing['firstName'] = $name['first'];
				$billing['lastName'] = $name['last'];
				$billing['postalCode'] = $order->{'billing_address.postal_code'};
				$billing['countryCodeAlpha2'] = $order->{'billing_address.country'};
				$billing['region'] = $order->{'billing_address.region'};
				$billing['locality'] = $order->{'billing_address.city'};
				$billing['extendedAddress'] = trim($order->{'billing_address.line_2'});
				
			
				$billing = array_filter($billing);
				
				
				$name = $this->parseName( $order->{'shipping_address.name'});
				$shipping = [];
				$shipping['streetAddress'] = $order->{'shipping_address.line_1'};
				$shipping['firstName'] = $name['first'];
				$shipping['lastName'] = $name['last'];
				$shipping['postalCode'] = $order->{'shipping_address.postal_code'};
				$shipping['countryCodeAlpha2'] = $order->{'shipping_address.country'};
				$shipping['region'] = $order->{'shipping_address.region'};
				$shipping['locality'] = $order->{'shipping_address.city'};
				$shipping['extendedAddress'] = trim($order->{'shipping_address.line_2'});
					
			
					
				$sale = array(
						'amount' => round(trim($amount),2),
						'orderId' => trim($order->number),
						'channel' => $channel,
						'paymentMethodToken' => $paymentToken,
						'options' => array(
								'submitForSettlement' => True
						)
				);
					
				if(!empty($customer)) {
					$sale['customer'] = $customer;
				}
					
				if(!empty($billing)) {
					$sale['billing'] = $billing;
				}
					
				if(!empty($shipping)) {
					$sale['shipping'] = $shipping;
				}
				
				if (\Base::instance()->get('SITE_TYPE') != 'wholesale') {
					//SENDING UDF RSD-2319
					$part_numbers = array_filter(\Dsc\ArrayHelper::getColumn($order->items, 'model_number'));
					
					$sale['customFields'] = [
					    'fraud_skus' => implode(',' , $part_numbers)
					];
				}
				
				//$sale['deviceData'] = json_encode($doc['device_data']);
		 
				
				
				$result = \Braintree_Transaction::sale($sale);
			
					
				$response = array();
				
					
				if($result->success) {
				
				
					//CHARGE WAS SUCCESSFUL LETS CHECK FOR RISK DATA AND DECIDE
					if(!empty(@$result->transaction->riskData['decision'])) {
		
						switch (@$result->transaction->riskData['decision']) {
							case 'Decline':
								/*
								 * IF KOUNT DECLINES, WE DECLINE THE CHECKOUT AND RETURN A MESSAGE TO USER
								 * */			
								\Base::instance()->set('result', $result);
								\Base::instance()->set('doc', $sale);
								$html = \Dsc\System::instance()->get( 'theme' )->renderView( 'Theme/Views::emails/braintree/failed_fruad.php' );
								$emailSent = \Dsc\System::instance()->get('mailer')->send('errors@rallysportdirect.com', 'Fraud Braintree Charge', array($html) );
								
								$this->setError('Payment has been declined, please contact customer service', '501');
								
								break;
							case 'Approve':
								//WHAT SHOULD WE DO ON APPROVE?
								break;
		
							case 'Not Evaluated':
								//WHAT SHOULD WE DO ON NOT EVALUATED?
							
								\Base::instance()->set('result', $result);
								\Base::instance()->set('doc', $data);
								$html = \Dsc\System::instance()->get( 'theme' )->renderView( 'Theme/Views::emails/braintree/failed_fruad.php' );
								$emailSent = \Dsc\System::instance()->get('mailer')->send('errors@rallysportdirect.com', 'Fraud Braintree Charge - NOT EVALUATED', array($html) );
		
								break;
		
							case 'Review' :
								/*
								 * IF KOUNT DECLINES, WE DECLINE THE CHECKOUT AND RETURN A MESSAGE TO USER
								 * */
								$response['code'] = '1001';
							
								\Base::instance()->set('result', $result);
								\Base::instance()->set('doc', $sale);
								
								//$html = \Dsc\System::instance()->get( 'theme' )->renderView( 'Theme/Views::emails/braintree/failed_fruad.php' );
								
									
								break;
								
							default:
							//	$this->logCharge($data->order_id, 'KOUNT: DEFAULT: '. @$result->transaction->riskData->decision );
								break;
									
		
						}
		
					} else {
						
						\Dsc\Mongo\Collections\Logs::add('KOUNT EMPTY');
						
					}
		
						
					$response['authCode'] = $result->transaction->id;
					$response['amount'] = $amount;
		
					if(!empty(@$result->transaction->paypal['token'])) {
						$response['cardtype'] = 'Paypal';
					} else {
						switch (strtolower($result->transaction->creditCardDetails->cardType)) {
							case 'visa':
								$response['cardtype'] = 'BTVisa';
								break;
							case 'mastercard':
								$response['cardtype'] = 'BTMastercard';
								break;
							case 'discover':
								$response['cardtype'] = 'BTDisc';
								break;
							case 'american express':
								$response['cardtype'] = 'BTAmex';
								break;
							default:
								$response['cardtype'] = 'Wire Transfer';
								break;
						}
					}
		
		
					//WE JUST NEED TO UPDATE THE ORDER AND RETURN TRUE
					
					$payments_received_total = $order->get('payments_received_total');
					$payments_received_total = $payments_received_total + $amount;
					
					$order->set('payments_received_total', $payments_received_total);
					$order->set('process_status', 'PICKING');

					$order->addTransactionPayment($response, $generatePaymentXML);
					
					$order->save();
					
					return true;
					
						
				} else {
					$this->setError('Payment has failed: Most commonly this is billing address mismatch if you continue to get this error, please contact customer service we can process your payment over the phone.');
				}
					
			} catch (\Exception $e) {
				\Dsc\Mongo\Collections\Logs::add('EXCEPTION ' . $e->getMessage());
				$this->setError($e->getMessage());
				//handle problem
			}
		
		return false;
	}

	public static function createCustomerQueue($userObjectId) {
		
		try {
			
			$user = (new \Users\Models\Users)->setCondition('_id', new \MongoDB\BSON\ObjectID((string) $userObjectId))->getItem();
	
			if(empty($user)) {
					throw new \Exception('User not found');
			}	
			
				//IF THERE IS NO GP NUMBER LETS ASSIGN ONE
				if(empty($user->{'gp.customer_number'})) {
					
					$number = (new \JBAShop\Models\Customers)->createCustomerNumber();
					$user->set('number', $number);
					$user->set('gp.customer_number', $number);
					$user->clear('braintree');
					$user->store();

				} else {
					try {
						//there is a GP NUMBER lets see if it is in braintree
						$braintree_customer = \Braintree_Customer::find($user->{'gp.customer_number'});
						$user->braintree = (object) array('id' => $braintree_customer->id);
						$user->store();
						}  catch (\Braintree_Exception_NotFound $e) {
						//NOT FOUND
							$user->clear('braintree');
							$user->store();
        			}
					
				}
				
					if(empty($user->{'braintree.id'})) {
						//OK we have a customer with a gp.number and that GP number is not in braintree
						$result = \Braintree_Customer::create(array(
								'id' => (string) $user->{'gp.customer_number'},
								'firstName' => $user->first_name,
								'lastName' => $user->last_name,
								'email' => $user->email
						));
					
						if($result->success) {
							$user->braintree = (object) array('id' => $result->customer->id);
							$user->store();
						} else {
							$result = \Braintree_Customer::create(array(
								'firstName' => $user->first_name,
								'lastName' => $user->last_name,
								'email' => $user->email
							));
							if($result->success) {
								$user->braintree = (object) array('id' => $result->customer->id);
								$user->store();
							} else {
								throw new \Exception('Can not create customer account');
							}
						}
					}	
				
				
			
			return $user;
			 
		} catch (\Exception $e) {
			
			throw new \Exception($e->getMessage());
			//NOT SURE WHAT TO DO ON THIS FAIL
		}	
	}
	
	
	
	protected function setError($message) {
		//TODO ADD SYSTEM EMAIL TO DEVS
		
		
		throw new \Exception($message);
		
	}
	
}


