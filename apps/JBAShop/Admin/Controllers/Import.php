<?php
namespace JBAShop\Admin\Controllers;

class Import extends \Shop\Admin\Controllers\Export
{
    public function beforeRoute()
    {
        $this->app->set('meta.title', 'Import | Shop');
    }

    public function index()
    {
        $this->app->set('meta.title', 'Export | Shop');
        
        echo $this->theme->render('Shop/Admin/Views::import/index.php');
    }
    
    
    public function doImport() {
    	
    	 $importer = $this->app->get('PARAMS.importer');
    	 
    	 
    	 if(!empty($importer)) {
    	 	$namespace = '\JBAShop\Models\Importers';
    	 	$class = $namespace . '\\' . ucfirst($importer);
    	 	
    	 	if(class_exists($class)) {
    	 		
    	 	
    	 		$importer = new $class;
    	 		if(method_exists($importer, 'import')) {
    	 			try {
    	 				$importer->import();
    	 				
    	 			} catch (\Exception $e) {
    	 				\Dsc\System::addMessage($e->getMessage(), 'error');
    	 				
    	 			} finally {
    	 				
    	 				$this->app->reroute('/admin/shop/import');
    	 			}
    	 			
    	 		}
    	 		
    	 	} else {
    	 		echo "Importer Not Found";
    	 	}
    	 	
    	 }
    	 
    	
    }
    
}