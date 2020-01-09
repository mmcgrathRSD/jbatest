<?php
namespace JBAShop\Admin;

class Listener extends \Prefab
{


	public function onSystemRebuildMenu($event)
	{
			if ($model = $event->getArgument('model'))
			{


				// Find the Catalog Menu Item
				$catalog_item = (new \Admin\Models\Nav\Primary())->load(array(
						'type' => 'admin.nav',
						'path' => '/admin-primary-navigation/shop',
						'title' => 'Shop'
				));

				$children = array(

						array(
								'title' => 'Import',
								'route' => './admin/shop/import',
								'icon' => 'fa fa-upload'
						),
						array(
								'title' => 'User Content',
								'route' => './admin/shop/usercontents',
								'icon' => 'fa fa-lg fa-fw fa fa-comments'
						),
						array(
								'title' => 'Packages',
								'route' => './admin/shop/packages',
								'icon' => 'fa fa-lg fa-fw fa fa-gift'
						),
						array(
								'title' => 'Vendors',
								'route' => './admin/shop/vendors',
								'icon' => 'fa fa-lg fa-fw fa fa-gift'
						)
						

				);

				$catalog_item->addChildren($children);

				
				
                // Find the Customers Menu Item
                $customers_item = (new \Admin\Models\Nav\Primary())->load(array(
                        'type' => 'admin.nav',
                        'path' => '/admin-primary-navigation/shop/customers',
                        'title' => 'Customers'
                ));
                
                
              
                
                $children = array(
                        array(
                                'title' => 'Notifications',
                                'route' => './admin/shop/notifications',
                                'icon' => 'fa fa-bookmark'
                        ),
                );
            	$customers_item->addChildren( $children );
				

				\Dsc\System::instance()->addMessage('Rally Shop added its admin menu items.');
			}
	}

	public function onDisplayShopCustomers($event)
	{
		$item = $event->getArgument('item');
		$tabs = $event->getArgument('tabs');
		$content = $event->getArgument('content');

		\Base::instance()->set('item', $event->getArgument('item'));
		$view = \Dsc\System::instance()->get('theme');
		$html = $view->renderLayout('RallyShop/Admin/Views::shop_customer/read.php');

		$tabs[] = 'Shop';
		$content[] = $html;

		$event->setArgument('tabs', $tabs);
		$event->setArgument('content', $content);
	}

	public function onDisplayAdminUserEdit($event)
	{
		$item = $event->getArgument('item');
		$tabs = $event->getArgument('tabs');
		$content = $event->getArgument('content');

		\Base::instance()->set('item', $item);
		$view = \Dsc\System::instance()->get('theme');

		$tabs['shop'] = 'Shop';
		$content['shop'] = $view->renderLayout('RallyShop/Admin/Views::users/tab.php');

		$tabs['shop'] = 'User Social Profile';
		$content['shop'] = $view->renderLayout('RallyShop/Admin/Views::users/profile.php');
		
		$event->setArgument('tabs', $tabs);
		$event->setArgument('content', $content);
	}
	
	
	public function mailerPreviewShopNew_order($event) {
		
		$options = [];
		
		$options['order'] = (new \Shop\Models\Orders)->setCondition('number', 'STAGE-1069155')->getItem();
		
		$event->setArgument('variables', $options);

	}
	
	public function mailerPreviewRallyshopOrder_cancelled($event) {
	
		$options = [];
	
		$options['order_id'] = 'RS123FAKEORDER';
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewRallyshopPayment_failed_first($event) {
	
		$options = [];
	
		$options['order_id'] = 'RS123FAKEORDER';
	
		$event->setArgument('variables', $options);
	}
	public function mailerPreviewRallyshopPayment_failed_second($event) {
	
		$options = [];
	
		$options['order_id'] = 'RS123FAKEORDER';
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewShopBlind_order_has_shippped($event) {
	
	    $options = [];
	
	    $order = (new \JBAShop\Models\Orders)->setCondition('tracking_numbers.0', ['$exists' => true])->getItem();
	    $options['order'] = $order;
	
	    $provider = $order->getShippingProvider();
	
	    if(!empty($provider)) {
	        $options['provider'] = $provider;
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
	            default:
	                $url = null;
	        }
	    }
	
	    if(!empty($order->{'tracking_numbers'})) {
	        $options['tracking_url'] = $url.$order->{'tracking_numbers.0'};
	    } else {
	        $options['tracking_url'] = null;
	    }
	
	
	    $event->setArgument('variables', $options);
	}
	
	
	public function mailerPreviewRallyshopOrder_has_shippped($event) {
	
		$options = [];
	
		$order = (new \JBAShop\Models\Orders)->setCondition('tracking_numbers.0', ['$exists' => true])->getItem();
		$options['order'] = $order;
		
		$provider = $order->getShippingProvider();
		
		if(!empty($provider)) {
			$options['provider'] = $provider;
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
				default:
					$url = null;
			}
		}
		
		if(!empty($order->{'tracking_numbers'})) {
			$options['tracking_url'] = $url.$order->{'tracking_numbers.0'};
		} else {
			$options['tracking_url'] = null;
		}
		
	
		$event->setArgument('variables', $options);
	}
	
	
	public function mailerPreviewRallyshopUsercontent_question_answered($event) {
	
		$options = [];
	
		$options['question'] = (new \JBAShop\Models\UserContent)->setCondition('type', 'question')->setCondition('product_id', ['$exists' => true])->getItem();
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewRallyshopOrder_return_confirmation($event) {
	
		$options = [];
	
		$options['return'] = (new \JBAShop\Models\Returns)->getItem();
	
		$event->setArgument('variables', $options);
	}
	 
		public function mailerPreviewRallyshopOrder_return_processed($event) {
	
		$options = [];
	
		$options['return'] = (new \JBAShop\Models\Returns)->getItem();
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewRallyshopItem_back_instock_notification($event) {
	
		$options = [];
	
		$options['product'] = (new \JBAShop\Models\Products)->setState('filter.stock_status', 'in_stock')->getItem();
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewShopReview_products($event) {
	
		$options = [];
	
		$order = (new \JBAShop\Models\Orders)->setCondition('_id', new \MongoDB\BSON\ObjectID('55c543e8caae52c1228b821b'))->getItem();
		$options['order'] = $order;	
		$options['user'] = $order->user();
		
		$ids = \Dsc\ArrayHelper::getColumn($order->items, 'product_id');
		$model = new \JBAShop\Models\Products();
		$options['products'] = $model->setState('filter.ids', $ids)->getList();
		
		$event->setArgument('variables', $options);
		
	}
	
	public function mailerPreviewRallyshopRequest_review_shopper_approved($event) {
	
		$options = [];
	
		$order = (new \JBAShop\Models\Orders)->setCondition('_id', new \MongoDB\BSON\ObjectID('55c543e8caae52c1228b821b'))->getItem();
		$options['order'] = $order;
		$options['user'] = $order->user();
	
		$ids = \Dsc\ArrayHelper::getColumn($order->items, 'product_id');
		$model = new \JBAShop\Models\Products();
		$options['products'] = $model->setState('filter.ids', $ids)->getList();
	
		$event->setArgument('variables', $options);
	
	}
	
	
	public function mailerPreviewRallyshopPayment_confirmation($event) {
	
		$options = [];

		$options['order_id'] = 'RS-FAKE111111';
	
		$event->setArgument('variables', $options);
	}
	
	
	public function mailerPreviewRallysportContact_us_request($event) {
	
		$options = [];
		
		$question = (new \Shop\Models\UserContent)->setCondition('type', 'question')->setCondition('product_id', ['$exists' => true])->getItem();
		
		$options['question'] = $question;
		$options['customertopic'] = $question->title;
		$options['customername'] = $question->user()->first_name.' '.$question->user()->last_name;
		$options['customerphone'] = $question->user()->number;
		$options['customeremail'] = $question->user()->email;
		$options['customermessage'] = $question->content;
		
		$event->setArgument('variables', $options);
	}	
	
	public function mailerPreviewRallyshopUsercontent_question_to_customer_service($event) {
	
		$options = [];
		
		$question = (new \Shop\Models\UserContent)->setCondition('type', 'question')->setCondition('product_id', ['$exists' => true])->getItem();
		
		$options['question'] = $question;
		$options['customertopic'] = $question->title;
		$options['customername'] = $question->user()->first_name.' '.$question->user()->last_name;
		$options['customerphone'] = $question->user()->number;
		$options['customeremail'] = $question->user()->email;
		$options['customermessage'] = $question->content;
		$options['usercontent'] = $question;
		
		$event->setArgument('variables', $options);
	}	
	public function mailerPreviewRallyshopUsercontent_question_to_vendor($event) {
		
		$options = [];
		
		$question = (new \Shop\Models\UserContent)->setCondition('type', 'question')->setCondition('product_id', ['$exists' => true])->getItem();
		
		$options['question'] = $question;
		$options['customertopic'] = $question->title;
		$options['customername'] = $question->user()->first_name.' '.$question->user()->last_name;
		$options['customerphone'] = $question->user()->number;
		$options['customeremail'] = $question->user()->email;
		$options['customermessage'] = $question->content;
		$options['usercontent'] = $question;
		
		$event->setArgument('variables', $options);
		
	}
	
	
	
	public function mailerPreviewRallyshopUsercontent_new_post($event) {
		$options = [];
		$question = (new \Shop\Models\UserContent)->setCondition('type', 'question')->setCondition('product_id', ['$exists' => true])->getItem();
		
		$options['usercontent.product_title'] = 'PRODUCT TITLE';
		$options['usercontent.product_slug'] ='CUSTOMER NAME';
		$options['usercontent'] = $question;
		
		$event->setArgument('variables', $options);
	}	
	
	public function mailerPreviewRallyshopAbandoned_cart_first_attempt($event) {
	
		$options = [];
		$cart = (new \JBAShop\Models\CartsAbandoned())->setState('filter.abandoned', '1')
		->getItem();
		$user = $cart->user();
		$token = \Dsc\System::instance()->get('auth')->getAutoLoginToken($user, true);
		
		$options['cart'] = $cart;
		$options['user'] = $user;
		$options['token'] = $token;
		
			$ids = \Dsc\ArrayHelper::getColumn($cart->items, 'product_id');
			$model = new \JBAShop\Models\Products();
			$docs = $model->setState('filter.ids', $ids)->getList();
			$options['items'] = $docs;
		
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewRallyshopNew_customer_created($event) {
	
		$options = [];
		$user = (new \Users\Models\Users())->setCondition('forgot_password.token', ['$exists' => true])
		->getItem();

		$token = \Dsc\System::instance()->get('auth')->getAutoLoginToken($user, true);
	

		$options['user'] = $user;
		$options['token'] = $token;
	
	
	
	
		$event->setArgument('variables', $options);
	}
	
	public function mailerPreviewRallyshopWishlist_item_pricedropped($event) {
	
	    $options = [];
	    
	    $user = (new \Users\Models\Users())->setCondition('forgot_password.token', ['$exists' => true])
	    ->getItem();
	    $dateTime = new \DateTime();
	    $now = $dateTime->format('l F jS Y');
	
	    $options['user'] = $user;
	    $options['product'] = (new \JBAShop\Models\Products)->setCondition('publication.status', 'published')->getItem();
	    $options['date'] = $now;
	
	
	
	
	    $event->setArgument('variables', $options);
	}
	
	

	/*
	 * AFTER A USER IS SAVED LETS UPDATE THEIR USER CONTENT
	 */
	public function afterSaveUsersModelsUsers ($event) {
		
		$model = $event->getArgument('model');
		
		\Dsc\Queue::task('\RallyShop\Models\UserContent::updateForUser', [$model->id], ['batch' => 'usercontent']);
		
	}
	
	public function mailerPreviewRallyshopRally_recap ($event) {
	    

	    
	    $options = (new \JBA\Site\Controllers\Diagnostics)->sendRallyRecap(true);

	    $event->setArgument('variables', $options);
	    

	}
}