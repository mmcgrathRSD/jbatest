<?php 
namespace RallyShop\Site\Controllers;

class Category extends \Shop\Site\Controllers\Category
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

      
}
