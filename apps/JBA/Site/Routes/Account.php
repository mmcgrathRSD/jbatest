<?php

namespace JBA\Site\Routes;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Account extends \Dsc\Routes\Group{
	
	
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
					'url_prefix' => '/account'
				)
		);
	
		$this->add( '/user', 'GET', array(
				'controller' => 'Account',
				'action' => 'modRoutes'
		));
		
	
	}
}