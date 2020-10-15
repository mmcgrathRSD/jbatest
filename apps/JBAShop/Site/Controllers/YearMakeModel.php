<?php 
namespace JBAShop\Site\Controllers;

class YearMakeModel extends \Shop\Site\Controllers\YearMakeModel
{	
	protected function getModel()
	{
		$model = new \JBAShop\Models\YearMakeModels;
		return $model;
	}
	
	public function getItem(){
		$this->app->error( '410', 'Invalid YMM' );
		return;
	}
    
    
}