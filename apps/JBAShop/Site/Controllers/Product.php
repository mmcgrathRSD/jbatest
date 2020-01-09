<?php 
namespace RallyShop\Site\Controllers;

class Product extends \Shop\Site\Controllers\Product
{
	use \Dsc\Traits\Controllers\SupportPreview;
	
    protected function model($type=null) 
    {
        switch (strtolower($type)) 
        {
        	case "products":
        	case "product":
        	    $model = new \RallyShop\Models\Products;
        	    break;
        	default:
        	    $model = new \RallyShop\Models\Categories;
        	    break;
        }
        
        return $model; 
    }
    
  
    
    public function stockStatus() {
    	
    	$variant_id = $this->inputfilter->clean( $this->app->get('PARAMS.variant_id'), 'string' );
    	
    	$item = \RallyShop\Models\Variants::getById($variant_id);
    	
    	$stock = \RallyShop\Models\Variants::quantity($variant_id);
    	 
    	if($stock) {
    		$stockStatus = 'instock';
    	} else {
    		$stockStatus = 'outofstock';
    	}
    	
    	$this->app->set('item', $item );
    	$this->app->set('stockStatus', $stockStatus );

    	
    	echo \Dsc\System::instance()->get('theme')->renderView ( 'Shop/Site/Views::product/blocks/stockstatus.php' );
    	 
    	    	
    }
    
    
    
}