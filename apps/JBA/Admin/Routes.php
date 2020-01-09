<?php

namespace JBA\Admin;

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
					'namespace' => '\JBA\Admin\Controllers',
					'url_prefix' => '/admin/rallysport'
				)
		);
		
		$this->add('/site/home', 'GET|POST', array(
				'controller' => 'Settings',
				'action' => 'siteHome'
		));
		
		$this->add('/shop/home', 'GET|POST', array(
				'controller' => 'Settings',
				'action' => 'shopHome'
		));
		$this->add('/test', 'GET|POST', array(
				'controller' => 'Testing',
				'action' => 'testing'
		));
		
		$this->app->route( 'GET /admin/rallysport/testing/@task', '\JBA\Admin\Controllers\Testing->@task' );
		$this->app->route( 'GET /admin/rallysport/testing/@task/page/@page', '\JBA\Admin\Controllers\Testing->@task' );
		
		$this->app->route( 'GET /admin/login', '\JBA\Admin\Controllers\Login->login' );
		$this->app->route( 'POST /admin/login/rsd', '\JBA\Admin\Controllers\Login->auth' );
		
		
		$this->addSettingsRoutes();
	}
}