<?php 
namespace JBAShop\Site\Controllers;

class Order extends \Shop\Site\Controllers\Order 
{
    /**
     * List a user's orders
     *
     */
    public function index()
    {
        $identity = $this->getIdentity();
        if (empty($identity->id)) 
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/orders');
            \Base::instance()->reroute('/sign-in');
            return;
        }
        
        $model = new \Shop\Models\Orders;
        $model->emptyState()->populateState()
            ->setState('list.limit', 10 )
            ->setState('filter.user', (string) $identity->id )
            ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ;
        $state = $model->getState();
                
        try {
            $paginated = $model->paginate();
        } catch ( \Exception $e ) {
            // TODO Change to a normal 404 error
            \Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
            $f3->reroute( '/' );
            return;
        }

        \Base::instance()->set('state', $state );
        \Base::instance()->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'My Orders');
        
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orders/index.php');
    }
    
    /**
     * Display a single order
     */
    public function read()
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
                
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            \Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/order/' . $id );
            \Base::instance()->reroute('/sign-in');
            return;
        }
    	
    	try {
    		$item = (new \Shop\Models\Orders)->setState('filter.id', $id)->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    		if ((string) $item->user_id != (string) $identity->id) {
    		    throw new \Exception;
    		}    		
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Order", 'error');
    		\Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    		$f3->reroute( '/shop/orders' );
    		return;
    	}
    	
    	\Base::instance()->set('order', $item );
    	
    	if ($f3->get('print')) {
    	    $this->app->set('meta.title', 'Print | Order Detail');
    	    $view = \Dsc\System::instance()->get('theme');
    	    echo $view->renderView('Shop/Site/Views::order/print.php');
    	    return;    		
    	}
    	
    	$this->app->set('meta.title', 'Order Detail');
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::order/detail.php');
    }
    
    /**
     * Display a single order
     */
    public function updatePayment()
    {
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'string' );
    
    	$identity = $this->getIdentity();
    	if (empty($identity->id))
    	{
    		\Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/order/' . $id );
    		\Base::instance()->reroute('/sign-in');
    		return;
    	}
    	 
    	try {
    		
    
    	    
    		if(\Dsc\Mongo\Helper::isValidId ($id)) {
    			$item = (new \JBAShop\Models\Orders)->setState('filter.id', $id)->getItem();
    		} else {
    			$item = (new \JBAShop\Models\Orders)->setCondition('number', $id)->getItem();
    		}
    		
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    		if ((string) $item->user_id != (string) $identity->id) {
    			throw new \Exception;
    		}
    			
    		
    		//check to make sure this order is not already fulfilled
    		
    		
    	} catch ( \Exception $e ) {
    		// TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Order", 'error');
    		\Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    		$f3->reroute( '/shop/orders' );
    		return;
    	}
    	 
    	\Base::instance()->set('order', $item );
    	 
    	
    	 
    	$this->app->set('meta.title', 'Order Update Payment Details');
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	
    	if($item->totalDue() > 0) {
    		echo $view->render('Shop/Site/Views::order/update_payment_details.php');
    	} else {
    		echo $view->render('Shop/Site/Views::order/update_successful.php');
    	}
    	
    }
    
    /**
     * Display a single order
     */
    public function processUpdatePayment()
    {
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
    	
    	
    	
    	//require user to be logged in
    	$identity = $this->getIdentity()->reload();
    	if (empty($identity->id))
    	{
    		\Dsc\System::instance()->get('session')->set('site.login.redirect', '/shop/order/' . $id );
    		\Base::instance()->reroute('/sign-in');
    		return;
    	}
    
    	try {
    		$item = (new \JBAShop\Models\Orders)->setState('filter.id', $id)->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    		
    		//require the logged in user to be the same as the order owner
    		if ((string) $item->user_id != (string) $identity->id) {
    			throw new \Exception;
    		}
    		
    		$action = $this->input->get('action', '', 'string');   		
            /*
             * returns if no splits are found else returns array of all splits
             */
            $splits = \Shop\Models\Orders::orderGetSplits($item->number);

    		switch ($action) {
                case 'update':
                    $this->updateOrder($item);
                    if(empty($splits)) {
                    $this->chargeOrder($item->id);
                    }
    		    case 'retry':
                    if(empty($splits)) {
                        $this->chargeOrder($item->id);
                    } else {
                        \Dsc\System::instance()->addMessage("Thank you. Your payment will be processed soon.", 'success');
                        \Base::instance()->reroute('/');
                    }

                    break;
    			
    			default:
    				\Dsc\System::instance()->addMessage( "Invalid Action", 'error');
    				$f3->reroute( '/shop/order/updatepayment/'.$id);
    			    break;
    		}
    		
    		
    	} catch ( \Exception $e ) {
    		// TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Order", 'error');
    		\Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    		$f3->reroute( '/shop/orders' );
    		return;
    	}
    
    	\Base::instance()->set('order', $item );
    
    	
    
    	$this->app->set('meta.title', 'Update Payment Details for Order');
    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::order/update_payment_details.php');
    }
    
    public function updateOrder($order)
    {
		$f3 = \Base::instance();

		$data = $f3->get('POST');

		$identity = $this->getIdentity();

		if (empty($identity->id)) {
			$flash = \Dsc\Flash::instance();
			$f3->set('flash', $flash );

			$this->app->set('meta.title', 'Login or Register | Checkout');

			$view = \Dsc\System::instance()->get( 'theme' );
			echo $view->render( 'Shop/Site/Views::checkout/identity.php' );
			return;
		}

		$billing_address = $this->input->get('billing', array(), 'array');

		if (array_key_exists('payment_method_nonce', $data) && !empty($data['payment_method_nonce'])) {
			$braintreeId = $identity->{'braintree.id'};

			$braintreeAddress = [
				'streetAddress' => $billing_address['line_1'],
				'region' => $billing_address['region'],
				'postalCode' => $billing_address['postal_code'],
				'countryCodeAlpha2' => $billing_address['country']
			];

			if (!empty($billing_address['line_2'])) {
				$braintreeAddress['extendedAddress'] =  $billing_address['line_2'];
			}

			if (!empty($billing_address['city'])) {
				$braintreeAddress['locality'] =  $billing_address['city'];
			}

			$braintree = new \JBAShop\Payment\Braintree;

			try {
				$token = $braintree->getTokenFromNonce($data['payment_method_nonce'], $braintreeId, ['billing' => $braintreeAddress, 'deviceData' => $data['device_data']]);

				$order->set('billing_address', $billing_address);
				$order->set('payment_methods', [$token]);
				$order->save();

				return;
			} catch (\Exception $e) {

				\Dsc\System::instance()->addMessage($e->getMessage(), 'error');
				\Base::instance()->reroute( '/shop/order/updatepayment/' . $order->_id);
				
			}
		}

		\Dsc\System::instance()->addMessage("Payment method invalid", 'error');
		\Base::instance()->reroute( '/shop/order/updatepayment/' . $order->_id);
    }
      
    
    public function chargeOrder($orderid, $amount = null, $token = null) {
    	 try {
    	 	$payment = new \Shop\Payment\Braintree;
    	 	$result = $payment->chargeOrder($orderid, true, $amount, $token);

    	 	if($result) {
    	 		
    	 		$item = (new \Shop\Models\Orders)->setState('filter.id', $orderid)->getItem();
    	 		
				\Dsc\Queue::task('\Picking\Models\Orders::staticlyMoveBatch', [$item->number, 'PICKING'], [
					'title'      => 'Moving order ' . $item->number . ' to Picking',
					'archive'    => true,
					'batch'      => 'local',
					'identifier' => 'movetopicking',
					'when'       => time() + (60 * 10)
				]);

    	 		\Dsc\System::instance()->addMessage( "Payment Successful", 'success');
    	 		\Base::instance()->reroute( '/shop/order/updatepayment/'.$orderid);
    	 		//payment was successful
    	 	} else {
    	 		
    	 		\Dsc\System::instance()->addMessage( "Payment was unsuccessful", 'error');
    	 		\Base::instance()->reroute( '/shop/order/updatepayment/'.$orderid);
    	 		//payment declined
    	 	}
    	 	
    	 } catch (\Exception $e) {
    	     
    	 	\Dsc\System::instance()->addMessage( "Payment was unsuccessful", 'error');
    	 	\Dsc\System::instance()->addMessage( $e->getMessage(), 'error');
    	 	\Base::instance()->reroute( '/shop/order/updatepayment/'.$orderid);
    	 	//payment declined	
    	 }	
    }
    
    
    
    /**
     * List a user's orders
     *
     */
    public function manualTokenCreate()
    {
    	

    	$this->app->set('meta.title', 'Create Order Token');
    	$this->app->set('sop', $this->app->get('PARAMS.id'));
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::order/manual_token.php');
    }
}