<?php 
namespace JBAShop\Admin\Controllers;

class YearMakeModel extends \Admin\Controllers\BaseAuth 
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/yearmakemodels';
    protected $create_item_route = '/admin/shop/yearmakemodel/create';
    protected $get_item_route = '/admin/shop/yearmakemodel/read/{id}';    
    protected $edit_item_route = '/admin/shop/yearmakemodel/edit/{id}';
    
     protected function getModel($type = 'ymm')
    {
        $model = null;
        switch( $type){
            case 'ymm' :
                $model = new \JBAShop\Models\YearMakeModels;
                break;
            case 'products':
                $model = new \JBAShop\Models\Products;
                break;
        }
        return $model;
    }
    
    protected function getItem() 
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $model = $this->getModel()
            ->setState('filter.id', $id);

        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $f3->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayYMMCreate', array('tabs' => array(), 'content' => array()) );
        $this->app->set('meta.title', 'Create Year Make Model | Shop');
        
        echo $view->render('Shop/Admin/Views::yearmakemodels/form.php');
    }
    
    protected function displayEdit()
    {
        $this->app->set('meta.title', 'Edit Year Make Model | Shop');
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayYMMCreate', array('tabs' => array(), 'content' => array()) );

        echo $view->render('Shop/Admin/Views::yearmakemodels/form.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $f3 = \Base::instance();
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $f3->reroute( $route );
    }
    
    protected function displayRead() {}
    
    public function quickadd()
    {
    	$model = $this->getModel();
    	$manufacturers = $model->getList();
    	\Base::instance()->set('manufacturers', $manufacturers );
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderLayout('Shop/Admin/Views::yearmakemodels/quickadd.php');
    }

    public function createAndAddToProduct() {
        $response = new \stdClass();
        $response->result = false;
        
        $product = $this->inputfilter->clean( $this->app->get("POST.product"), 'alnum');
        if( empty($product)){
            $response->error_msg  = "Invapid Product ID";
            return $this->outputJson( $response );
        }
        
        $item = $this->getModel('products')->setState('filter.id', $product)->getItem();
        if($item == null){
            $response->error_msg  = "Invapid Product ID";
            return $this->outputJson( $response );            
        }

        $data = array();
        $data['vehicle_year'] = trim($this->inputfilter->clean( $this->app->get('POST.year'), 'int'));
        $data['vehicle_make'] = trim($this->inputfilter->clean( $this->app->get('POST.make'), 'string'));
        $data['vehicle_model'] = trim($this->inputfilter->clean( $this->app->get('POST.model'), 'string'));
        $data['vehicle_sub_model'] = trim($this->inputfilter->clean( $this->app->get('POST.subModel'), 'string'));
        $data['vehicle_engine_size'] = trim($this->inputfilter->clean( $this->app->get('POST.engine'), 'string'));
        
        $data['title'] = '';
        if( !empty($data['vehicle_year'])){
            $data['title'] .= $data['vehicle_year'].' ';
        }
        if( !empty($data['vehicle_make'])){
            $data['title'] .= $data['vehicle_make'].' ';
        }
        if( !empty($data['vehicle_model'])){
            $data['title'] .= $data['vehicle_model'].' ';
        }
        if( !empty($data['vehicle_sub_model'])){
            $data['title'] .= $data['vehicle_sub_model'].' ';
        }
        if( !empty($data['vehicle_engine_size'])){
            $data['title'] .= $data['vehicle_engine_size'];
        }
        
        // first create YMM
        try{
            $ymm = $this->getModel('ymm');
            $ymm->bind($data);
            $ymm->save();
            $ymm->publish();
        } catch( \Exception $e){
            $response->error_msg = $e->getMessage();
            return $this->outputJson($response);
        }
        $item_ymms = (array)$item->ymms;
        $item_ymms []= array('id' => $ymm->id, 'slug' => $ymm->slug, 'title' => $ymm->title );
        usort($item_ymms, function($a, $b){
            if($a['slug'] == $b['slug']){ // just for piece of mind
                return 0;
            }
            return $a['slug'] < $b['slug'] ? -1 : 1;
        });
        try {
            $item->ymms = $item_ymms;
            $item->save();
        } catch( \Exception $e){
            $response->error_msg = $e->getMessage();
            return $this->outputJson($response);
        }
        $flash = \Dsc\Flash::instance();
        $flash->store( array( 'ymms' => $item_ymms ));
        $this->app->set('flash', $flash);
        $response->table = $this->theme->renderLayout('Shop/Admin/Views::products/fields_ymm_table.php');
        
        $response->result = true;
        return $this->outputJson( $response );        
    }
    
    public function addToProduct()
    {
        $response = new \stdClass();
        $response->result = false;
        
        $product = $this->inputfilter->clean( $this->app->get("POST.product"), 'alnum');
        if( empty($product)){
            $response->error_msg  = "Invapid Product ID";
            return $this->outputJson( $response );
        }
        
        $item = $this->getModel('products')->setState('filter.id', $product)->getItem();
        if($item == null){
            $response->error_msg  = "Invapid Product ID";
            return $this->outputJson( $response );            
        }
        
        $slugs = $this->inputfilter->clean( $this->app->get('POST.slugs'), 'array');
        array_walk( $slugs, function( &$item, $key) {
            $item = \Dsc\System::instance()->get('inputfilter')->clean($item, 'string');
        });
        
        $item_ymms = (array)$item->ymms;
        $orig_ymms = \Dsc\ArrayHelper::getColumn($item_ymms, 'slug' );
        $ymms = $this->getModel('ymm')->setState('filter.in_slugs', $slugs)->getItems();
        
        $response->added = 0;
        foreach ($ymms as $ymm) {
            if( in_array($ymm->slug, $orig_ymms) === false){ // not there yet
                $title = '';
                if( $ymm->vehicle_year){
                    $title = $ymm->vehicle_year.' ';
                }
                if( $ymm->vehicle_make){
                    $title .= $ymm->vehicle_make.' ';
                }
                if( $ymm->vehicle_model){
                    $title .= $ymm->vehicle_model.' ';
                }
                if( $ymm->vehicle_sub_model){
                    $title .= $ymm->vehicle_sub_model.' ';
                }
                if( $ymm->vehicle_engine_size){
                    $title .= $ymm->vehicle_engine_size.' ';
                }
            
                $item_ymms []= array('id' => $ymm->id, 'slug' => $ymm->slug, 'title' => $title);
                $response->added += 1;
            }
        }

        if( $response->added ){
            // sort ymms
            usort($item_ymms, function($a, $b){
                if($a['slug'] == $b['slug']){ // just for piece of mind
                    return 0;
                }
                return $a['slug'] < $b['slug'] ? -1 : 1;
            });
            
            $flash = \Dsc\Flash::instance();
            $flash->store( array( 'ymms' => $item_ymms ));
            $this->app->set('flash', $flash);
            $response->table = $this->theme->renderLayout('Shop/Admin/Views::products/fields_ymm_table.php');
            
            $item->ymms = $item_ymms;
            try {
            	$item->save();
            } catch (\Exception $e) {
            	$response->error_msg  = $e->getMessage();
            	return $this->outputJson( $response );
            }
          
        }
        $response->result = true;
        return $this->outputJson( $response );
    }
}