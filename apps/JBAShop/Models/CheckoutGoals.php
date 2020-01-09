<?php
namespace JBAShop\Models;

class CheckoutGoals extends \Dsc\Mongo\Collection
{
 
    protected $__collection_name = 'checkout.goals';
    
    protected $__config = array(
        'default_sort' => array(
            '_id' => -1
        )
    );    

    protected function fetchConditions()
    {
        parent::fetchConditions();
		
        $filter_keyword = $this->getState('filter.keyword');
        if ($filter_keyword && is_string($filter_keyword))
        {
        	$key = new \MongoDB\BSON\Regex($filter_keyword,'i');
        
        
        	$this->setCondition('user_email', $key);
        }
        
        
        
        return $this;
    }
    
    public static function startTracking()
    {
    	try {
    		$user = \Dsc\System::instance()->get('auth')->getIdentity();
    		
    		if(!empty($user->id)) {
    			$loggedin = true;
    		} else {
    			$loggedin = false;
    		}
    		
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(empty($checkout_id)) {
    			$cart =  \JBAShop\Models\Carts::fetch();
    			$tracking = (new static)
    			->set('cart_id', $cart->id)
    			->set('user_email', @$cart->user_email)
    			->set('timestamp', \time())
    			->set('loggedin', $loggedin)
    			->set('device_type', 'desktop-tablet');
    			$audit = \Audit::instance();
    			if($audit->isMobile()) { 
    				$tracking->set('device_type', 'mobile');
    			} 
    			$tracking->set('created', \time())->save();
    			$checkout_id = \Dsc\System::instance()->get('session')->set('checkout_id', $tracking->id);
    		}
    	} catch (\Exception $e) {
    		
    	}
    	
    	
    }
    
    public static function shownConfirmedIdentity( )
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$item->set('confirmed_identity', \time());
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    /*
     * NOT CURRENTLY USED
     */
    public static function shownLoginRegisterPage( )
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$item->set('login_register', \time());
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function shownCheckoutPage( )
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$item->set('showcheckoutapage', \time());
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function startPayPalCheckout( )
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$item->set('paypal_checkout', \time());
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function startCreditCartCheckout( )
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$item->set('standard_checkout', \time());
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    
    public static function completedShippingForm( )
    {
    	try {
    	$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    	if(!empty($checkout_id)) {
    	  $item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    	  if($item) {
    	  $item->set('complete_shipping_form', \time());
    	  $item->set('timestamp', \time());
    	  $item->save();
    	  }
    	   		
    	}
    	} catch (\Exception $e) {
    	
    	}
    }
    
    public static function completedBillingForm( )
    {
    	try {
    
    	$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    	if(!empty($checkout_id)) {
    		$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    		if($item) {
    		$item->set('complete_billing_form', \time());
    		$item->set('timestamp', \time());
    		$item->save();
    		}
    	}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function completedCouponForm( )
    {
    
    }
    
    public static function completedGiftCardForm( )
    {
    	
    }
    
    public static function completedShippingMethod( )
    {
    	try {
	    	$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
	    	if(!empty($checkout_id)) {
	    		$item =  (new static)->setState('filter.id', $checkout_id)->getItem();
	    		if($item) {
	    			$item->set('complete_shipping_method', \time());
	    			$item->set('timestamp', \time());
	    			$item->save();
	    		}
	    	}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function completedPaymentFailed( $message = '', $kount = '')
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$paymentFailed = $item->paymentFailed;
    				if(empty($paymentFailed)) {
    					$paymentFailed = [];
    				}
    				if(!empty($message)) {
    					$message = 'Payment attempt Failed';
    				}
    				$paymentFailed[] = $message;
    				$item->set('paymentFailed', $paymentFailed);
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    
    
    public static function completedPaymentForm( )
    {
    	try {
	    	$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
	    	if(!empty($checkout_id)) {
	    		$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
	    		if($item) {
	    		$item->set('complete_payment_form', \time());
	    		$item->set('timestamp', \time());
	    		$item->save();
	    		}
	    	}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function completedCheckout( $order_id = null)
    {
    	try {
	    	$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
	    	if(!empty($checkout_id)) {
	    		$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
	    		if($item) {
	    			$item->set('complete_checkout_confirmation', \time());
	    			$item->set('order_id', $order_id );
	    			$item->set('timestamp', \time());
	    			$item->save();
	    		}
	    		
	    	}
    	} catch (\Exception $e) {
    		 
    	}
    }
    
    public static function systemLog( $message = '', $line = null, $class = null)
    {
    	try {
    		$checkout_id = \Dsc\System::instance()->get('session')->get('checkout_id');
    		if(!empty($checkout_id)) {
    			$item =  (new static)->setState('filter.id',   $checkout_id)->getItem();
    			if($item) {
    				$logs = $item->logs;
    				if(empty($logs)) {
    					$logs = [];
    				}			
    				$logs[] = $message;
    				$item->set('logs', $logs);
    				$item->set('timestamp', \time());
    				$item->save();
    			}
    		}
    	} catch (\Exception $e) {
    		 
    	}
    }
   
}

?>