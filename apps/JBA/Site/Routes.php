<?php

namespace JBA\Site;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group{
	
	
	function __construct(){
		
		
		parent::__construct();
	}
	
	/**
	 * Initializes all routes for this group
	 * NOTE: This method should be overriden by every group
	 */
	public function initialize(){

		$this->setDefaults(
				array(
					'namespace' => '\JBA\Site\Controllers',
					'url_prefix' => ''
				)
		);


		/** 
		 * This is to recieve notifications from cloudinary
		*/
		$this->add('/notifyme', 'GET', [
			'controller' => 'UploadNotification',
			'action' => 'index'
		]);

		$this->add('/notifyme', 'POST', [
			'controller' => 'UploadNotification',
			'action' => 'recieve'
		]);
		
		$this->add('/reviews', 'GET', array(
		    'controller' => 'ShopperApproved',
		    'action' => 'index'
		));
		$this->add('/unsubscribe', 'GET', array(
				'controller' => 'Unsubscribe',
				'action' => 'index'
		));
		
		$this->add('/contact-us', 'GET', array(
				'controller' => 'ContactUs',
				'action' => 'index'
		));
		
		$this->add('/contact-us', 'POST', array(
				'controller' => 'ContactUs',
				'action' => 'submit'
		));
		$this->add('/returnhandoff', 'GET', array(
				'controller' => 'ContactUs',
				'action' => 'returnHandoff'
		));
		
		$this->add( '/reset/test', 'GET', array(
				'controller' => 'Home',
				'action' => 'resetTest'
		));
		
		$this->add( '/testing/randomaccount', 'GET', array(
		    'controller' => 'Home',
		    'action' => 'viewRandomUser'
		));
		
		$this->add( '/', 'GET|POST', array(
				'controller' => 'Home',
				'action' => 'index'
		));
		$this->add( '/navgen', 'GET', array(
				'controller' => 'Nav',
				'action' => 'index'
		));
			
		$this->add( '/user/garage/remove/@id', 'GET|POST', array(
				'controller' => 'Garage',
				'action' => 'removeVehicle'
		));
		
		$this->add( '/connect/test', 'GET', array(
				'controller' => 'Connect',
				'action' => 'test'
		));
		
		$this->add('/login', 'GET', array(
				'controller' => 'Login',
				'action' => 'index'
		));
		
		$this->add('/sign-in', 'GET', array(
				'controller' => 'Login',
				'action' => 'only'
		));
		
		$this->add('/login', 'POST', array(
				'controller' => 'Login',
				'action' => 'auth'
		));
		
		$this->add('/logout', 'GET|POST', array(
				'controller' => 'Login',
				'action' => 'logout'
		));
	
		
		$this->add('/register', 'GET', array(
				'controller' => 'Login',
				'action' => 'register'
		));
		
		$this->add('/register', 'POST', array(
				'controller' => 'Login',
				'action' => 'create'
		));
		
		$this->add('/login/social', 'GET|POST', array(
				'controller' => 'Login',
				'action' => 'social'
		));
		
		$this->add('/login/social/auth/@provider', 'GET|POST', array(
				'controller' => 'Login',
				'action' => 'provider'
		));
		
		$this->add('/login/completeProfile', 'GET', array(
				'controller' => 'Login',
				'action' => 'completeProfileForm'
		));
		
		$this->add('/login/completeProfile', 'POST', array(
				'controller' => 'Login',
				'action' => 'completeProfile'
		));
		
		$this->add('/login/validate', 'GET', array(
				'controller' => 'Login',
				'action' => 'validate'
		));
		$f3 = \Base::instance();
		$f3->route('POST /login/validate', function ($f3)
		{
			$token = $f3->get('REQUEST.token');
			$f3->reroute('/login/validate/token/' . $token);
		});
		
		$f3->route('GET /user', function ($f3)
		{
			$f3->reroute('/shop/account');
		});
		
		
		
		$this->add('/search-prediction', 'GET', array(
		    'controller' => 'Search',
		    'action' => 'prediction'
		));
		
		
		
		$this->add('/catalog_search_results.cfm', 'GET|POST', array(
				'controller' => 'Search',
				'action' => 'reroute'
		));

		$this->add('/part_number.cfm', 'GET', [
			'controller' => 'Products',
			'action' => 'reroute'
		]);
		
	}
}