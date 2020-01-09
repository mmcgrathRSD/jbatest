<?php 
namespace JBAShop\Admin\Controllers;

class OrderFlow extends \Shop\Admin\Controllers\Order
{
   
	
	public function index () {
		 
		$user = null;
		if($customer = $this->session->get('order.customer')) {
			// TODO
		} else {
			try{
				$userid = $this->inputfilter->clean( $this->app->get("PARAMS.userid"), 'alnum');
				$user = (new \Users\Models\Users)->setState('filter.id', $userid)->getItem();
			} catch( \Exception $e ) {
				$user = new \Users\Models\Users();
	        }
		}
		$this->app->set( 'meta.title', "Create a New Order | Shop" );
		$this->app->set( 'user', $user );
		$view = \Dsc\System::instance()->get('theme');
		echo $view->render('Shop/Admin/Views::orders/flow/index.php');
	}
	
	
	public function steps() {
		//dispatcher methods
		$step = $this->app->get('PARAMS.step');
		$this->app->set("error_occurred", false);
		$this->app->set("error_msg", "");
		
		
		try {
			$class = new \JBAShop\Admin\Controllers\OrderFlow;
			if(method_exists($class, $step )) {
		
				$class->$step(); 
				exit(0);
			} else {
				throw new \Exception('No Method ' . $step);	
			}
		} catch (\Exception $e) {
			$this->app->set("error_occured", true);
			$this->app->set("error_msg", $e->getMessage());
			$this->sendJsonData();
			exit(0);
		}
	}	
	
	public function firstStep() {
		
		try{
			$userid = $this->inputfilter->clean( $this->app->get("PARAMS.userid"), 'alnum');
			$user = (new \Users\Models\Users)->setState('filter.id', $userid)->getItem();
		} catch( \Exception $e ) {
			$user = new \Users\Models\Users();
        }
		
		
		$view = \Dsc\System::instance()->get('theme');
		$view->event = $view->trigger( 'onDisplayShopOrdersEdit', array( 'item' => $this->getItem(), 'user' => $user, 'tabs' => array(), 'content' => array() ) );
		echo $view->render('Shop/Admin/Views::orders/flow/firstStep.php');
	}


	// loads tab with form to create a new customer
	public function createCustomer(){
		$view = \Dsc\System::instance()->get('theme');
		$this->app->set('tabid', 'createCustomer');		
		$this->app->set('tab', $this->renderTabHtml('New Customer', 'createCustomer'));
		
		$this->app->set('tabcontent', $view->renderLayout('Shop/Admin/Views::orders/flow/createCustomer.php'));		
		$this->app->set("select_tab_id", 'createCustomer');
		$this->sendJsonData();
		exit(0);		
	}
    
	//loads the customer and wishlist objects and the ymm, if no user just shows generic ymm
	public function getCustomerForOrder() {
		
		if($email = $this->inputfilter->clean( $this->app->get('POST.email'), 'string' ) ) {
			
			$customer = (new \JBAShop\Models\Customers)->setCondition('email', $email)->getItem();
			
			$wishlists = $customer->getWishlists();
			
			$cart = $customer->getCart();
			
			$this->session->set('order.customer', $customer);
			
			
			$this->app->set('customer', $customer);
			$this->app->set('wishlists', $wishlists);
			$this->app->set('cart', $cart);
		}
		
		$this->app->set('tabid', 'customer');
		$view = \Dsc\System::instance()->get('theme');
		$this->app->set('tab', $this->renderTabHtml('YMM', 'customerStep'));
		$this->app->set('sidebar', $view->renderLayout('Shop/Admin/Views::orders/flow/customerSidebar.php'));
		
		$this->app->set('tabcontent', $view->renderLayout('Shop/Admin/Views::orders/flow/ymm.php'));
		
		$this->sendJsonData();
		exit(0);
	
	}
	
	public function addGuestCustomerToOrder() {
		
	}
	
	
	//loads the customer and wishlist objects etc
	public function saveYmm() {
	
		$email = $this->inputfilter->clean( $this->app->get('POST.email'), 'string');
	
		$customer = (new \JBAShop\Models\Customers)->setCondition('email', $email)->getItem();
	
		$wishlists = $customer->getWishlists();
	
		$cart = $customer->getCart();
	
		$this->session->set('order.customer', $customer);
	
	
		$this->app->set('customer', $customer);
		$this->app->set('wishlists', $wishlists);
		$this->app->set('cart', $cart);
		$this->app->set('tabid', 'customer');
	
		$view = \Dsc\System::instance()->get('theme');
		$this->app->set('tab', $this->renderTabHtml('YMM'));
		$this->app->set('sidebar', $view->renderLayout('Shop/Admin/Views::orders/flow/customerSidebar.php'));
	
		$this->app->set('tabcontent', $view->renderLayout('Shop/Admin/Views::orders/flow/ymm.php'));
	
		$this->sendJsonData();
		exit(0);
	}
	
	
	// generates html for tab header
	protected function renderTabHtml($text, $class='') {
		$html = '<li role="presentation" class="'.$class.'"><a href="#'.$this->app->get('tabid').'" aria-controls="home" role="tab" data-toggle="tab">'.$text.'</a></li>';	
		return $html;
	}
    
	
	protected function sendJsonData() {
		
		$json = array('data' => array());
		$json['data']['tab'] = $this->app->get('tab');
		$json['data']['tabcontent'] = $this->app->get('tabcontent');
		$json['data']['sidebar'] = $this->app->get('sidebar');
		$json['data']['select'] = $this->app->get("select_tab_id");

		$json['result'] = !$this->app->get('error_occurred');
		$json['error_msg'] = $this->app->get("error_msg");

		header('Content-Type: application/json');
		echo json_encode($json);
		exit(0);
	}
    
    
}