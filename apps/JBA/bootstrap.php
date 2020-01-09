<?php

class JBABootstrap extends \Dsc\Bootstrap
{
    protected $dir = __DIR__;
    protected $base = __DIR__;
    protected $namespace = 'JBA';
	
    /*
     * preSite runs before the runSite methods, each bootstrap will run its down preSite/runSite/postSite 
     * 
     * This is useful to add dependancy injections here.
     * */
    protected function preSite()
    { 
    	parent::preSite();
    }
    
   
    protected function runCli()
    {
    	//Register the views file with the theme so it can render the passed namespaces
    	\Dsc\System::instance()->get('router')->mount( new \JBA\Site\Routes\Cli, $this->namespace );
    
    	parent::runSite();
    }
    
    /*
     * runSite runs before the postSite methods, each bootstrap will run its down preSite/runSite/postSite
    *
    * runSite is the most important it is loading and processing the routes and content for the uses
    * */
     protected function runSite()
    {  
    	//Register the views file with the theme so it can render the passed namespaces
    	\Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Site/Views/', 'JBA/Site/Views' );
    	\Dsc\System::instance()->get('router')->mount( new \JBA\Site\Routes, $this->namespace );
    	\Dsc\System::instance()->get('router')->mount( new \JBA\Site\Routes\Users, $this->namespace );
  
        parent::runSite();
    }
    
    protected  function postSite() {
    	

	    	/*if (\Base::instance()->get('DEBUG')) {
	    		
	    	$f3 = \Base::instance();
	    	$global_app_name = 'site';
	    	
	    	$files = array();
	    	if ($prioritized_files = (array) \Base::instance()->get($global_app_name . '.dsc.minify.css')) {
	    		foreach ($prioritized_files as $priority=>$paths) {
	    			foreach ((array)$paths as $path) {
	    				$files[] = $path;
	    			}
	    		}
	    	}
    	
    
    			
    		$minified = \Web::instance()->minify($files, null, true, '/');
    		
    		file_put_contents('/var/www/rallysportdirect.com/apps/Theme/Assets/css/styles.min.css',$minified);
    	
    		
    	}*/
    	parent::postSite();
    }
    
    
    
  
    
    protected function preAdmin()
    {
    	 
    	\Dsc\System::instance()->get('auth')->loginWithRememberMe();
    
    	parent::runAdmin();
    }
    
     protected function runAdmin()
    {  
    	
    	// register the modules path
    	\Modules\Factory::registerPath( \Base::instance()->get('PATH_ROOT') . "apps/JBA/Modules/" );
    	
        \Dsc\System::instance()->get('router')->mount( new \JBA\Admin\Routes, $this->namespace );

        \Base::instance()->route( 'GET /admin/login', '\JBA\Admin\Controllers\Login->login' );
        \Base::instance()->route( 'POST /admin/login', '\JBA\Admin\Controllers\Login->auth' );
       
        \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Admin/Views/', 'JBA/Admin/Views' );
   		 
        
        parent::runAdmin();
    }
    protected function postAdmin()
    {
    	 
    
    	\Base::instance()->route( 'GET /admin/login', '\JBA\Admin\Controllers\Login->login' );
    	\Base::instance()->route( 'POST /admin/login', '\JBA\Admin\Controllers\Login->auth' );
    	 
   
    	parent::postAdmin();
    }
    
    
    
    protected function runDaignostic()
    {
    	 
    	
    
    	parent::runDaignostic();
    }


    protected function preWholesale()
    {
        \Dsc\System::instance()->getDispatcher()->addListener(\JBA\Listener::instance());
    }
    
}
$app = new JBABootstrap();