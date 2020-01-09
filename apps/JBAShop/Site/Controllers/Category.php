<?php 
namespace JBAShop\Site\Controllers;

class Category extends \Shop\Site\Controllers\Category
{ 
	use \Dsc\Traits\Controllers\SupportPreview;
	
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

      
}
