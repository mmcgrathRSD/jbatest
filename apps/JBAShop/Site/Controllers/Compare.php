<?php 
namespace JBAShop\Site\Controllers;

class Compare extends \Dsc\Controller
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
    	
    	// TODO Check ACL against both category and item.
    	$url_params = $this->app->get('PARAMS');

    	$param = $this->inputfilter->clean( $this->app->get('PARAMS.*'), 'string' );
    	$pieces = explode('?', $param);
    	$path = $pieces[0];
    	$products_model = $this->model('products');

    	try {

    		$category = $this->model('categories')->setState('filter.path', $path)->getItem();

    		$productids = $_REQUEST['compare'];

    		$products_model = $this->model('products');
    		$products = $products_model->setState('filter.ids', $productids)->setState('filter.category.id',$category->id)->getItems();

    		$this->app->set('products', $products);
    		$this->app->set('category', $category);

    	} catch (\Exception $e) {



    	}

    	$view = \Dsc\System::instance()->get('theme');
    	if ($this->app->get( 'AJAX' ))
    	{
    		echo $view->renderLayout('Shop/Site/Views::compare/index.php');
    	}
    	else
    	{
    		echo $view->render('Shop/Site/Views::compare/index.php');
    	}
    	return;
    	

    	
    }
}