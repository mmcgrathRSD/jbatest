<?php
namespace RallyShop\Site\Controllers;

class Checkout extends \Shop\Site\Controllers\Checkout
{
    /**
     * Submits a completed cart checkout processing
     *
     */
    public function submit()
    {
        $real_email = trim( strtolower( $this->input->get( 'email_address', null, 'string' ) ) );

        set_time_limit(0);
        /*
         * Get the Customers Cart
        	*/
        $cart = \RallyShop\Models\Carts::fetch();
        

        // Update the cart with checkout data from the form submitted, shipping address, billing address
        $checkout_inputs = $this->input->get( 'checkout', array(), 'array' );
        $checkout_inputs = $cart->updateCheckoutInfo($checkout_inputs);

        $cart->set('user_email', $real_email);
        $cart->set('checkout.simple_ship', $this->isSimpleShip($cart));
        //using store here to avoid completely rebuilding the cart again to add billing data
        $cart->store();
        
        // verify they haven't been sitting on checkout too long
		$checkoutExpiration = \Base::instance()->get('shop.checkout_expiration');
        if (
        	!empty($cart->get('checkout_started'))
        	&& $cart->get('checkout_started') < strtotime("-$checkoutExpiration minutes")
    	) {
        	\Dsc\System::addMessage('Your shopping cart contents may have changed. Please review your order and try again.', 'warning');
			$this->app->reroute('/shop/checkout');
			return;
        }
		
		// make sure coupon is still valid
		if (!empty($cart->coupons)) {
			$cart->checkCoupons();
			
			if (empty($cart->coupons)) {
				$this->app->reroute('/shop/checkout');
				return;
			}
		}

        /*
         * HANDLE GUEST CHECKOUT IF THIS IS A GUEST CHECKOUT WE NEED TO HANDLE THIS
         */
        $identity = $this->getIdentity();

        if (empty( $identity->id ))
        {

            if (!filter_var($real_email, FILTER_VALIDATE_EMAIL)) {
                \Dsc\System::addMessage( 'Please enter a valid email address.', 'error' );
                $this->app->reroute( '/shop/checkout' );
                return;
            }

            if (\Users\Models\Users::emailExists($real_email)) {
                //IF the email exists in mongo but the customer didn't login we will load them add assign this user to cart just before accept
                $identity = (new \Users\Models\Users)->load(['email' => $real_email]);
                $assignToUnAuthenticatedGuest  = true;
            } else {

                //THIS IS A NEW EMAIL LETS MAKE A GUEST USER

                $mongo_id = (string) new \MongoDB\BSON\ObjectID;
                $password = \Users\Models\Users::generateRandomString();
                $name =  $checkout_inputs['billing_address']['name'];
                $name = $this->parseName($name);

                $data = array(
                    'first_name' => $name['first'],
                    'last_name' => $name['last'],
                    'email' => $real_email,
                    'new_password' => $password,
                    'confirm_new_password' => $password
                );

                $identity = (new \RallyShop\Models\Customers)->bind($data);

                try
                {
                    // this will handle other validations, such as username uniqueness, etc
                    $identity->set('role', 'identified');
                    $identity->set('active', true);
                  
                    $identity->save();
                }
                catch(\Exception $e)
                {
                    //this if check is a hack to fix it an unknown exception on live 
                    \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);
                    \Dsc\System::addMessage( 'Could not create guest account', 'error' );
                    \Dsc\System::addMessage( $e->getMessage(), 'error' );
                    \Dsc\System::instance()->setUserState('shop.checkout.register.flash_filled', true);
                    
                    \Shop\Services\NewRelic::shopCheckoutException($e, $e->getMessage());
                    
                    $flash = \Dsc\Flash::instance();
                    $flash->store(array());
                    $this->app->reroute('/shop/checkout');
                    return;
                }

                // if we have reached here, then all is right with the form
                $flash = \Dsc\Flash::instance();
                $flash->store(array());

                $cart->set('user_id', $identity->id)->store();

               /*
				 * QUEUE them a new customer email
				 */
				\Dsc\Queue::task('\Shop\Models\Customers::sendNewCustomerEmail', [(string) $identity->id], [
				    'title'   => 'Send New Customer email',
				    'batch'   => 'emails'
				]);

            }

        }

   
        /*
         * QUICK CHECK TO MAKE SURE WE HAVE ITEMS
         */
        if ($cart->quantity() <= 0)
        {
            $this->app->reroute('/shop');
        }

        /*
         * CONFIRM THE IDENTITY LETS JUST DOUBLE CHECK WE ARE NOW LOGGED IN
        	*/

        if (empty( $identity->id ))
        {
            $flash = \Dsc\Flash::instance();
            \Base::instance()->set('flash', $flash );

            \Dsc\System::addMessage( 'Could not create account or login', 'error' );
            \Shop\Services\NewRelic::shopCheckoutError('Could not create account or login');
            
            $this->app->reroute('/shop/checkout');
            return;
        }
        $identity = (new \RallyShop\Models\Customers)->bind($identity->cast());
        $identity->ensureValidCustomer();
        $identity->store();
        
        /*
         * BEFORE WE CAN CHECKOUT WITH BRAINTREE WE NEED TO HAVE A BRAINTREE CUSTOMER SO LETS CHECK TO SEE IF WE HAVE ONE AND IF SO LOAD IT SO WE CAN
         */
        if(!empty($identity->get('braintree.id'))) {
            $braintreeCustomerID = $identity->get('braintree.id');

            //OK THIS USER HAS A PREVIOUS ACCOUNT LETS LOAD IT TO CONFIRM IT IS VALID
            try {
                $braintree_customer = \Braintree_Customer::find($braintreeCustomerID);
                $cart->set('__braintree_customer', $braintree_customer);
            } catch (\Braintree_Exception_NotFound $e) {
                \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);
                //lets generate one of the user, a listener should have already handled this before getting here but this is a catch all
                $user = \Shop\Payment\Braintree::createCustomerQueue($identity->id);
                $braintreeCustomerID = $user->get('braintree.id');
                $braintree_customer = \Braintree_Customer::find($braintreeCustomerID);
                $cart->set('__braintree_customer', $braintree_customer);
                
            } catch (\Exception $e) {

                \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);
               
                \Shop\Services\NewRelic::shopCheckoutException($e, $e->getMessage());
                
                \Dsc\System::addMessage( $e->getMessage(), 'error' );
                $this->app->reroute('/shop/checkout');

            }

        } else {
            //create a customer for new customer and guests
            $user = \Shop\Payment\Braintree::createCustomerQueue($identity->id);

            $braintreeCustomerID = $user->get('braintree.id');
            $braintree_customer = \Braintree_Customer::find($braintreeCustomerID);
            $cart->set('__braintree_customer', $braintree_customer);

        }

        $f3 = \Base::instance();

        //using store here to avoid completely rebuilding the cart again to add billing data
        $cart->store();

        /*
         * LETS CONFIRM THAT THIS CART HAS ALL THE INFORMATION IT NEEDS
         */
        try {

            $cart->canCheckout((bool) $cart->{'checkout.simple_ship'});

        } catch (\Exception $e) {
            \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);

            if ($this->app->get( 'AJAX' ))
            {
                \Shop\Services\NewRelic::shopCheckoutException($e, $e->getMessage());
                return $this->outputJson( $this->getJsonResponse( array(
                    'error'=> true,
                    'result'=>  $e->getMessage()
                ) ) );

            }	 else {
                \Shop\Services\NewRelic::shopCheckoutException($e, $e->getMessage());

                \Dsc\System::addMessage($e->getMessage(), 'error');

                // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
                $redirect = '/shop/checkout';
                if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
                {
                    $redirect = $custom_redirect;
                }

                \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
                $f3->reroute( $redirect );
            }


        }



        // Get \Shop\Models\Checkout
        // Bind the cart and payment data to the checkout model
        $checkout = \RallyShop\Models\Checkout::instance();
        $checkout->addCart($cart)->addPaymentData($f3->get('POST'));

        $data = $checkout->paymentData();

        if($cart->paymentRequired()) {


            if(empty($data['payment_method_nonce'])) {
                //no payment not sure maybe we die?

                $checkout->setError('Payment Method Not Valid');
            }

            /*
             * BEFORE WE CAN CHECKOUT WITH BRAINTREE WE NEED TO HAVE A BRAINTREE CUSTOMER
             * SO LETS CHECK TO SEE IF WE HAVE ONE AND IF SO LOAD IT SO WE CAN WE SHOULD ALREADY
             * HAVE THIS BUT JUST IN CASE LETS
             * MAKE SURE AND ATTEMPT TO CREATE ONE MORE TIME
             */
            if(empty($cart->get('__braintree_customer')->id)) {
                try {
                    $identity = \Shop\Payment\Braintree::createCustomerQueue($identity->id);
                    $braintree_customer = \Braintree_Customer::find($identity->get('braintree.id'));
                    $cart->set('__braintree_customer', $braintree_customer);
                } catch (\Braintree_Exception_NotFound $e) {
                    \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);
                    $checkout->setError('Payment:'. $e->getMessage());
                } catch (\Exception $e) {
                    \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), false);
                    $checkout->setError('Payment:'. $e->getMessage());
                }

            }


            /*
             * OK WE HAVE A CUSTOMER ACCOUNT
             * LETS CREATE A PAYMENT METHOD
             * WITH THE INFORMATION THEY PROVIDED
             */

            $billing_requires_postal_code = false;
            if (empty($checkout_inputs['billing_address']['postal_code']) && !empty($checkout_inputs['billing_address']['country'])) {
                $model = (new \Shop\Models\Countries())->setCondition('isocode_2', $checkout_inputs['billing_address']['country'])->getItem();

                if (!empty($model)) {
                    $billing_requires_postal_code = (bool) $model->requires_postal_code;
                }
            }

            if (!empty($checkout_inputs['billing_address']['line_1']) &&
                (!$billing_requires_postal_code || !empty($checkout_inputs['billing_address']['postal_code'])) &&
                !empty($checkout_inputs['billing_address']['country'])
            ) {

                $billingAddress = array();
                $billingAddress['streetAddress'] =  $checkout_inputs['billing_address']['line_1'];
                if(!empty( $checkout_inputs['checkout']['billing_address']['line_2'])) {
                    $billingAddress['extendedAddress'] =  $checkout_inputs['billing_address']['line_2'];
                }
                if(!empty($data['checkout']['billing_address']['city'])) {
                    $billingAddress['locality'] =  $checkout_inputs['billing_address']['city'];
                }
                if(!empty($data['checkout']['billing_address']['region'])) {
                    $billingAddress['region'] = $checkout_inputs['billing_address']['region'];
                }

                if (!empty($checkout_inputs['billing_address']['postal_code'])) {
                    $billingAddress['postalCode'] = $checkout_inputs['billing_address']['postal_code'];
                }

                $billingAddress['countryCodeAlpha2'] = $checkout_inputs['billing_address']['country'];

            } else {
                $checkout->setError('Billing Address is Incomplete');
            }



            $braintree = new \Shop\Payment\Braintree;

            /*
             * RIGHT HERE IS WHERE WE COULD AVOID THE 50 ADDRESSES BUG BY CHECKING IF THEY ALREADY USED THIS ADDRESS
             */

            if(empty($data['device_data'])) {
                \Shop\Models\CheckoutGoals::completedPaymentFailed('Device Data Not Found', false);
                $checkout->setError('We were unable to verify your checkout please ensure you have javascript enabled, if you keep experiencing this please contact customer service');
            }


            if (!empty($checkout->getErrors()))
            {


                if ($this->app->get( 'AJAX' ))
                {
                    $errors = array();
                    foreach ($checkout->getErrors() as $exception)
                    {   
                        \Shop\Services\NewRelic::shopCheckoutException($exception, $exception->getMessage());
                        $errors[] =  $exception->getMessage();
                    }

                   
                    return $this->outputJson( $this->getJsonResponse( array(
                        'error'=> true,
                        'result'=> $errors
                    ) ) );
                }	 else {

                    $errors = array();
                    foreach ($checkout->getErrors() as $exception)
                    {
                        \Dsc\System::addMessage( $exception->getMessage(), 'error' );
                        $errors[] =  $exception->getMessage();
                        \Shop\Services\NewRelic::shopCheckoutException($exception, $exception->getMessage());
                        
                    }

                  
                    // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
                    $redirect = '/shop/checkout';
                    if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
                    {
                        $redirect = $custom_redirect;
                    }

                    \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
                    $f3->reroute( $redirect );
                }
            }




            /*
             * CREATE A TOKEN FOR THIS CARD THEY JUST SUBMITTED
             */
            try {

                if(!empty($cart->get('payment_data'))) {
                    $paymentData = $cart->get('payment_data');
                }
                else {
                    $paymentData = $braintree->getTokenFromNonce($data['payment_method_nonce'],$cart->get('__braintree_customer')->id, ['billing' => $billingAddress, 'deviceData' => @$data['device_data'] ]);

                }
                //IF WE ARE USING PAYPAL
                if(!empty($cart->get('nonce'))) {
                    $cart->set('payment_data', $paymentData)->store();
                }


                $checkout->addPaymentData($paymentData);


                if(!empty($assignToUnAuthenticatedGuest)) {
                    $cart->set('user_id', $identity->id);
                }

                /*
                 * ACCEPT PAYMENT UP HERE SO THAT IT CAN TRIGGER CHECKOUT ERRORS
                 */
                $checkout->acceptOrder();


                \Shop\Models\CheckoutGoals::completedPaymentForm();
            }  catch (\Exception $e) {

                switch ( $e->getCode()) {
                    //CUSTOM BRAINTREE TOKEN REPORTED FRAUD
                    case 'K501':
                        \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), true);
                        $checkout->setError($e->getMessage());
                        break;

                    default:
                        \Shop\Models\CheckoutGoals::completedPaymentFailed($e->getMessage(), true);
                        $checkout->setError($e->getMessage());
                        break;
                }


            }


            /*
             * OK IF WE HAVE GOTTEN THIS FAR WITHOUT ERRORS
             *
             * We have a customer, a valid cart, a valid braintree customer, and a valid token. so if we have errors return them else we accept
             */
            if (!empty($checkout->getErrors()))
            {
                /*
                 * WE HAVE ERRORS MAYBE WE ADD EXTRA LOGIN EMAILING HERE
                 */
                if ($this->app->get( 'AJAX' ))
                {
                    $errors = array();
                    foreach ($checkout->getErrors() as $exception)
                    {

                        $errors[] =  $exception->getMessage();
                        \Shop\Services\NewRelic::shopCheckoutException($exception, $exception->getMessage());
                    }

                    return $this->outputJson( $this->getJsonResponse( array(
                        'error'=> true,
                        'result'=> $errors
                    ) ) );

                } else {


                    $errors = array();
                    foreach ($checkout->getErrors() as $exception)
                    {
                        \Dsc\System::addMessage( $exception->getMessage(), 'error' );
                        $errors[] =  $exception->getMessage();
                        \Shop\Services\NewRelic::shopCheckoutException($exception, $exception->getMessage());
                    }

                    // redirect to the ./shop/checkout/payment page unless a failure redirect has been set in the session (site.shop.checkout.redirect.fail)
                    $redirect = '/shop/checkout';
                    if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect.fail' ))
                    {
                        $redirect = $custom_redirect;
                    }

                    \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect.fail', null );
                    $f3->reroute( $redirect );
                }

                return;
            }

        } else {

            /*
             * MAYBE IS NOT REQUIRED
             * SO THEY USED A GIFT CARD OR THE ITEM WAS FREE
             */
            $checkout->acceptOrder();

        }


        /*
         * ALL DONE WE PROCESSED AN ORDER
         */
        if ($this->app->get( 'AJAX' ))
        {
            return $this->outputJson( $this->getJsonResponse( array(
                'result'=> 'success'
            ) ) );
        } else {

            // Redirect to ./shop/checkout/confirmation unless a site.shop.checkout.redirect has been set
            $redirect = '/shop/checkout/confirmation';
            if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.shop.checkout.redirect' ))
            {
                $redirect = $custom_redirect;
            }

            \Dsc\System::instance()->get( 'session' )->set( 'site.shop.checkout.redirect', null );
            $f3->reroute( $redirect );

            return;
        }
    }
}
