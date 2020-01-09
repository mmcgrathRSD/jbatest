<?php 
namespace JBAShop\Site\Controllers;

class Home extends \Shop\Site\Controllers\Home 
{    
    protected function model($type=null) 
    {
        switch (strtolower($type)) 
        {
        	case "products":
        	case "product":
        	    $model = new \JBAShop\Models\Products;
        	    break;
        	default:
        	    $model = new \JBAShop\Models\Categories;
        	    break;
        }
        
        return $model; 
    }
    
    public function index()
    {
    
    	//THERE IS NO USE CURRENTLY FOR A HOME PAGE AND A SHOP HOME PAGE SO WE ARE MAKING THEM THE SAME
    	\Base::instance()->reroute('/');
    	
    /*	$slug = $this->inputfilter->clean( $f3->get('PARAMS.slug'), 'cmd' );
    	$products_model = $this->model('products');
    	
    	try {
    		$paginated = $products_model->populateState()->paginate();
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "Invalid Items: " . $e->getMessage(), 'error');
    		$f3->reroute( '/' );
    		return;
    	}
    	
    	$state = $products_model->getState();
    	\Base::instance()->set('state', $state );
    	
    	\Base::instance()->set('paginated', $paginated );
    	
    	$this->app->set('meta.title', 'Shop');
     	
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::home/index.php');*/
    }
}