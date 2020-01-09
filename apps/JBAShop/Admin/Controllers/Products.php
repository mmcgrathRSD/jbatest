<?php 
namespace JBAShop\Admin\Controllers;

class Products extends \Shop\Admin\Controllers\Products
{
	use \Dsc\Traits\Controllers\AdminList;
	use \Dsc\Traits\Controllers\SupportPreview;
	
	protected $list_route = '/admin/shop/products';
	
	protected function getModel()
	{
		$model = new \JBAShop\Models\Products;
		return $model;
	}
	
	public function index()
    {	
    	$time_start = microtime(true);
    	
        $model = $this->getModel();
        $state = $model->emptyState()->populateState()->getState();
        $this->app->set('state', $state );
        
        $paginated = $model->paginate();     
        $this->app->set('paginated', $paginated );
        
        $this->app->set('meta.title', 'Products | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
     
        $conditions = $model->conditions();
        
        //var_dump($conditions); die();
               
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop\Admin\Views::products/list.php');
    }
    
   
	
	
	

	
}