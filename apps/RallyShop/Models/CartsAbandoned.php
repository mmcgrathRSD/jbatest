<?php
namespace RallyShop\Models;

class CartsAbandoned extends \Shop\Models\CartsAbandoned
{

    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $filter_abandoned = $this->getState('filter.abandoned', 0);
        
        if ($filter_abandoned)
        {
            // abandoned carts are only valid when they have items in them
            $this->setCondition('items', array(
                '$not' => array(
                    '$size' => 0
                )
            ));
            
            // only users
            $this->setCondition('user_id', array(
                '$nin' => array('', null)
            ));
            
           
            
            $settings = \Shop\Models\Settings::fetch();
            $abandoned_time = $settings->get('abandoned_cart_time') * 60;
            
            // set starting date and time for abandoned carts?
            $filter_abandoned_datetime = $this->getState('filter.abandoned.datetime');
            if (!empty($filter_abandoned_datetime))
            {
                $abandoned_time = $filter_abandoned_datetime - $abandoned_time;
            }
            else
            { // or use current timestamp
                $abandoned_time = time() - $abandoned_time;
            }
            
            $this->setCondition('metadata.last_modified.time', array(
                '$lt' => $abandoned_time
            ));
            
            // only newly abandoned carts
            $filter_only_new = $this->getState('filter.abandoned_only_new', 0);
            if ($filter_only_new)
            {
                // TODO OR NULL
                $this->setCondition('abandoned_notifications', array(
                    '$size' => 0
                ));
            }
        }
        
        $filter_first = $this->getState('filter.first', 0);
        if ($filter_first)
        {
        	$this->setCondition('abandoned_notification_first', array(
        			'$exists' => false
        	));
        }
        
    }

    /**
     * Finds all carts that are abandoned and adds jobs for email notifications to queue manager
     */
    public static function queueEmailsForNewlyAbandonedCarts()
    {
        $settings = \Shop\Models\Settings::fetch();
        
        if (empty($settings->abandoned_cart_emails_enabled)) 
        {
            return;
        }
        
        $newly_abandoned = (new static())->setState('filter.abandoned', '1')
            ->setState('filter.first', '1')
            ->setState('filter.abandoned_only_new', 1)
            ->getList();        
        
        
        if (count((array) $newly_abandoned))
        {
            $abandoned_time = $settings->get('abandoned_cart_time') * 60; //minutes time seconds
            
            foreach ($newly_abandoned as $cart)
            {
                $email = null;
                if ($cart->quantity() > 0) 
                {
                    if ($cart->user()->guest) {
                        // don't send emails to guest users
                        continue;
                    }
                    
                    if (count($cart->items)) {
                    	$ids = \Dsc\ArrayHelper::getColumn($cart->items, 'product_id');
                    	$model = new \RallyShop\Models\Products();
						$docs = $model->setState('filter.ids', $ids)->getList();
						foreach($docs as $doc) {
							if($doc->inventory_count <= 0) {
								continue;
							}
						}
                    } else {
                    	continue;
                    }
  
                    $email = $cart->user_email ? $cart->user_email : $cart->user()->email();
                    // scheduling should be relative to when this job runs, not the time of the cart's last modification,
                    // because that could lead to lots of emails at once for really old carts
                    // if the cron job is started months after the site goes live
                    
                    
                 /*  $time = $abandoned_time + time();
                    
                    $task = \Dsc\Queue::task('\RallyShop\Models\CartsAbandoned::sendAbandonedEmailNotification', array(
                    		(string) $cart->id, 
                    		'first'
                    ), array(
                    		'title' => 'Abandoned Cart Email Notification to ' . $email,
                    	//	'when' => $time,
                    		'batch' => 'email'
                    ));*/
                    
                    
                    /*
                     * SUPER ANNOYING BUG THAT CLOUDNIARY_URL FAILS IF CALLED BY THE QUEUE, so calling directly for now
                     */
                    static::sendAbandonedEmailNotification($cart->id, 'first');
                    
                    $cart->abandoned_notifications[] = new \MongoDB\BSON\ObjectID((string) $cart->_id);
                    // save reference to those task to the cart without modifying last_modified timestamp
                    $cart->store();
            
                    

                }
            }
        }
    }
    
    /**
     * 
     * @param unknown $cart_id
     * @param unknown $notification_idx
     */
    public static function sendAbandonedEmailNotification($cart_id, $notification_idx)
    {
    	try {
    		
    	
        $settings = \RallyShop\Models\Settings::fetch();
        if (empty($settings->abandoned_cart_emails_enabled))
        {
            throw  new \Exception('Abandoned Emails are Turned Off in settings');
        }
        
        $cart = (new static())->setState('filter.id', $cart_id)->getItem();
        
        // cart was deleted so dont do anything
        if (empty($cart->id))
        {
           throw  new \Exception('Cart was missing');
        }
        
        // Has the cart been updated recently?  if so, don't send this email
       // $abandoned_time = $settings->get('abandoned_cart_time') * 60;
       // $abandoned_time = time() - $abandoned_time;
        
       // if ($cart->{'metadata.last_modified.time'} > $abandoned_time) 
       // {
       //    throw  new \Exception('Cart was modified after abandoned time');
       // }
        
        /*
         * DON'T SEND EMAILS WITH NO ITEMS
         */
        if ($cart->quantity() == 0) 
        {
          throw  new \Exception('Quantity 0');
        }
        
        
        $user = $cart->user();        
        if (empty($user->id) || $user->id != $cart->user_id || $user->guest)
        {
         
          throw  new \Exception('Customer Not Found');
        }        
        
        //let users opt out
        if(!empty($user->{'preferences.emails.cart'})) {
        	return;
        }
        

        if (empty($cart->{'user_email'}))
        {
            if (!empty($user->email)) 
            {
              $email = $user->email;     
            }
        }
        else
        {   $email = $cart->{'user_email'};     
           
        }
        
        $email = strtolower($email);
        
        $token = \Dsc\System::instance()->get('auth')->getAutoLoginToken($user, true);
               
        if (count($cart->items)) {
        	$ids = \Dsc\ArrayHelper::getColumn($cart->items, 'product_id');
        	$model = new \RallyShop\Models\Products();
        	$docs = $model->setState('filter.ids', $ids)->getList();
        	foreach($docs as $doc) {
        		if($doc->inventory_count <= 0) {
        			return;
        		}
        	}
        } else {
        	return;
        }
        
        //IF we can do some checks here on the cart to support multiple attempts
        if(empty($cart->abandoned_notification_first) || !empty($cart->abandoned_notifications)) {
        	$mailer = \Dsc\System::instance()->get('mailer');
        	if ($content = $mailer->getEmailContents('rallyshop.abandoned_cart_first_attempt', array(
        			'cart' => $cart,
        			'user' => $user,
        			'token' => $token,
        			'email' => $email,
        			'items' => $docs,
        			'ga' => 'utm_source=remark&utm_medium=cart&utm_campaign=Email'
        	))) {
        		$mailer->sendEvent( $email, $content);
        		$cart->abandoned_notification_first = \time();
        		$cart->store();
        	}
        }
        
       
        $cart->store();
        } catch (\Exception $e) {
            
        	\Shop\Services\NewRelic::shopCheckoutException($e, $e->getMessage());
        }
         
    }

    /**
     * Delete any queued email notifications for this cart
     */
    public function deleteAbandonedEmailNotifications()
    {
        return static::deleteQueuedEmails( $this );
    }
    
    /**
     * Delete any queued email notifications for this cart
     * 
     * Kinda chainable
     */    
    public static function deleteQueuedEmails( \Shop\Models\Carts $cart )
    {
        if (empty($cart->abandoned_notifications))
        {
            return $cart;
        }
        
        if ($ids = array_values($cart->abandoned_notifications))
        {
            \Dsc\Mongo\Collections\QueueTasks::collection()->deleteMany(array(
                '_id' => array(
                    '$in' => $ids
                )
            ));
        }
        
        return $cart;
    }
}