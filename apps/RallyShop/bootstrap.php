<?php


/* NOTE THIS app is meant to extend and override all the controllers both frontend and admin of SHOP for Rallysport,
 * so you will write your controllers, here the views for the frontend would be in the themes override, and the admin views are in the admin Overrides. 
 * This allows for custom controls but shared views. 
 *  
 */
class RallyShopBootstrap extends \Dsc\Bootstrap
{
    protected $dir = __DIR__;
    protected $base = __DIR__;
    protected $namespace = 'RallyShop';
	
    /*
     * preSite runs before the runSite methods, each bootstrap will run its down preSite/runSite/postSite 
     * 
     * This is useful to add dependency injections here.
     * */
    protected function preSite()
    {
    	parent::preSite();
    }
    /*
     * runSite runs before the postSite methods, each bootstrap will run its down preSite/runSite/postSite
    *
    * runSite is the most important it is loading and processing the routes and content for the uses
    * */
     protected function runSite()
    { 
    	\Dsc\System::instance()->get('router')->mount( new \RallyShop\Site\Routes, $this->namespace );
  		
    	//if no YMM is selected check the cookie, set active is session
    	if(empty(\Dsc\System::instance()->get('session')->get('activeVehicle'))) {
    		if($id = \Dsc\Cookie::get('activeVehicle')) {
    			if(\Dsc\Mongo\Helper::isValidId($id)) {
    				$ymm = (new \RallyShop\Models\YearMakeModels)->collection()->findOne(array('_id' => new \MongoDB\BSON\ObjectID($id)));
    			} else {
    				$ymm = (new \RallyShop\Models\YearMakeModels)->collection()->findOne(array('slug' => $id));
    			}
    		
    			if(!empty($ymm)) {
    				$this->session->set('activeVehicle', $ymm);
    			}
    		}
    	}
		
    	\Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Services/RichRelevance/Views', 'RallyShop/Services/RichRelevance/Views' );
    		
        parent::runSite();
    }
    
    
    protected function runCli()
    {    	     	
    	
    	\Dsc\System::instance()->getDispatcher()->addListener(\RallyShop\Site\Listener::instance());
    
    	
    	parent::runCli();
    }
    
     protected function runAdmin()
    {  

    	
    	// register the modules path
    	\Modules\Factory::registerPath( \Base::instance()->get('PATH_ROOT') . "apps/RallyShop/Modules/" );
    	 
        \Dsc\System::instance()->get('router')->mount( new \RallyShop\Admin\Routes, $this->namespace );
     
        \Dsc\System::instance()->get('theme')->registerViewPath( __dir__ . '/Admin/Views/', 'RallyShop/Admin/Views' );
   		 
        
       /* \Shop\Models\Reports::register('\RallyShop\Reports\BestSellers', array(
        		'title'=>'Best Sellers',
        		'icon'=>'fa fa-shopping-cart',
        		'type'=>'products',
        		'slug'=>'bestsellers',
        ));*/
        
        parent::runAdmin();
    }
}

$app = new RallyShopBootstrap();
