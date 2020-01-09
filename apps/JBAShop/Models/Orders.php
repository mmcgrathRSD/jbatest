<?php
namespace RallyShop\Models;

class Orders extends \Shop\Models\Orders
{
	protected $__regenerateXML = false;


	/**
	 * Creates an order from a cart object
	 *
	 * @param \Shop\Models\Carts $cart
	 *
	 * @return \Shop\Models\Orders
	 */
	public static function fromCart( \Shop\Models\Carts $cart )
	{
		$order = new static();
		return $order->mergeWithCart( $cart );
	}

	/*
	 * Loads the Current document into the mapper before save to allow comparisons to fire events on data change
	 */
	protected function trackChanges() {
		$this->__track_changes = true;
		return $this;
	}

	protected function afterSave()
	{  
        //never send an email from an order that was WHS
	    if(strpos(strtolower($this->get('number')), 'whs') !== false || strpos(strtolower($this->get('original_order_number')), 'whs') !== false )  {
	          $this->set('emails.shipped', \time())->store();
	    } 
	    
	    //also don't end SO emails for wholesale // TODO AFTER GO LIVE WE WOULD REMOVE THIS AFTER THE OLD SITE IS ALL THE WAY GONE
	    if(strpos(strtolower($this->get('price_level')), 'whole') !== false && strpos(strtolower($this->get('number')), 'so') !== false  )  {
	        $this->set('emails.shipped', \time())->store();
	    }
	    
	    //also don't end SO  if they were invoiced emails for wholesale // TODO AFTER GO LIVE WE WOULD REMOVE THIS AFTER THE OLD SITE IS ALL THE WAY GONE
	    if(strpos(strtolower($this->get('price_level')), 'whole') !== false && strpos(strtolower($this->get('number')), 'inv') !== false && strpos(strtolower($this->get('original_order_number')), 'so') !== false  )  {
	        $this->set('emails.shipped', \time())->store();
	    }	  
	    
		//if tracking numbers WHERE empty and now they are not, lets email the customer the order has shipped
		if(!empty($this->get('tracking_numbers')) && empty($this->get('emails.shipped'))) {
           if((int) $this->{'shipments_shipdate'} > (int) strtotime("-1 week") ) {
               $this->sendOrderShippedEmail();
           }
		}
	}

    public function sendOrderShippedEmail($email = null)
    {
        
        $provider = $this->getShippingProvider();
        $url = null;
        if(!empty($provider)) {
            switch ($provider) {
                case 'UPS':
                    $url = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=';
                    break;
                case 'FedEx':
                    $url = 'http://www.fedex.com/Tracking?action=track&tracknumbers=';
                    break;
                case 'USPS':
                    $url = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=';
                    break;
            }
        }
        if(!empty($this->{'tracking_numbers'})) {
            $tracking_url = $url . $this->{'tracking_numbers.0'};
        } else {
            $tracking_url = null;
        }
        
       
        if(!empty($this->get('checkout.blind_ship_to')) ) {
           
            $email = $this->get('checkout.blind_ship_to');
            
            $user = (new \Users\Models\Users)->setCondition('_id', $this->user_id)->getItem();
            
           
            
            if(!empty($user->get('blind_ship.name')) && !empty($user->get('blind_ship.from'))) {
 
                $mailer = \Dsc\System::instance()->get('mailer');
                if ($content = $mailer->getEmailContents('shop.blind_order_has_shippped', array(
                    'order' => $this,
                    'tracking_url' => $tracking_url,
                    'provider' => $provider
                ))) {
                    $content['fromEmail'] = $user->get('blind_ship.from');
                    $content['fromName'] = $user->get('blind_ship.name');
                    $content['replyToEmail'] = $user->get('blind_ship.from');
                    $content['replyToName'] = $user->get('blind_ship.name');
                    
                    
                  $result =   $mailer->sendEvent($email ? $email : $this->user_email, $content);
                  $result =   $mailer->sendEvent($user->get('blind_ship.from'), $content);
                    
                  $this->set('emails.shipped', \time())->store();
                }  
            }
            
            
           } else {
            $mailer = \Dsc\System::instance()->get('mailer');
            if ($content = $mailer->getEmailContents('rallyshop.order_has_shippped', array(
                'order' => $this,
                'tracking_url' => $tracking_url,
                'provider' => $provider
            ))) {
                $mailer->sendEvent($email ? $email : $this->user_email, $content);
                $this->set('emails.shipped', \time())->store();
            }  
        }
    }

	

    public function sendCancelledEmail($email = null)
    {
        $mailer = \Dsc\System::instance()->get('mailer');
        if ($content = $mailer->getEmailContents('rallyshop.order_cancelled', array(
            'order_id' => $this->number
        ))) {
            $mailer->sendEvent($email ? $email : $this->user_email, $content);
        }
    }

	/*
	 * CHECK TO MAKE SURE THAT ORDER NEEDS TO BE GENERATED
	 */
	public function checkGenerateXmlStatus($force = false) {
		$check = true;

		if (!empty($this->xmlgenerated) && !empty($this->existsingp)) {
		    $check = false;
		}

		if ($this->status != \Shop\Constants\OrderStatus::open) {
		    $check = false;
		}

		if ($check || $this->__regenerateXML || $force) {
			$this->queueCreateOrderXML();
		}
	}

	protected function queueCreateOrderXML() {
		if (!preg_match('/\.\d+$/', $this->number)) {
			\Dsc\Queue::task(
				'\Rally\Models\eConnect\SplitOrders::createXML',
				[(string) $this->number],
				[
					'title'=> 'Creating XML for order ' . $this->number,
					'batch' => 'local',
					'priority' => 10
				]
			);

			$this->set('xmlgenerated', time())->store();
		}
	}
}
