<?php 
namespace RallyShop\Site\Controllers;

class YearMakeModel extends \Shop\Site\Controllers\YearMakeModel
{	
	protected function getModel()
	{
		$model = new \RallyShop\Models\YearMakeModels;
		return $model;
	}
	  
    
    
}