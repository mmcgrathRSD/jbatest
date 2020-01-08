<?php 
namespace RallyShop\Admin\Controllers;

class Stores extends \Shop\Admin\Controllers\Categories 
{
    use \Dsc\Traits\Controllers\AdminList;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/stores';

    protected function getModel()
    {
        $model = new \Shop\Models\Stores;
        return $model;
    }
    
    public function index()
    {
        $model = $this->getModel();
        $state = $model->emptyState()->populateState()->getState();
        $this->app->set('state', $state );
        $paginated = $model->paginate();
        $this->app->set('paginated', $paginated );
        $this->app->set('selected', 'null' );
        
        $this->app->set('meta.title', 'Stores | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render('Shop/Admin/Views::stores/list.php');
    }
    
    public function getDatatable()
    {
        $model = $this->getModel();
        
        $state = $model->populateState()->getState();
        $this->app->set('state', $state );
        
        $paginated = $model->paginate();
        $this->app->set('paginated', $paginated );
    
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::stores/list_datatable.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getAll()
    {
        $model = $this->getModel();
        $stores = $model->getList();
        $this->app->set('stores', $stores );

        $this->app->set('selected', 'null' );
        
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::stores/list_parents.php');
        
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function getCheckboxes()
    {
        $model = $this->getModel();
         $stores = $model->getList();
        $this->app->set('stores', $stores );
    
        $selected = array();
        $data = \Base::instance()->get('REQUEST');
        
        $input = $data['category_ids'];
        foreach ($input as $id) 
        {
            $id = $this->inputfilter->clean( $id, 'alnum' );
            $selected[] = array('id' => $id);
        }

        $flash = \Dsc\Flash::instance();
        $flash->store( array( 'metadata'=>array('stores'=>$selected) ) );
        $this->app->set('flash', $flash );
        
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('Shop/Admin/Views::stores/checkboxes.php');
    
        return $this->outputJson( $this->getJsonResponse( array(
                'result' => $html
        ) ) );
    
    }
    
    public function selectList( $selected=null )
    {
        $model = $this->getModel();
         $stores = $model->getList();
        $this->app->set('stores', $stores );
        $this->app->set('selected', $selected );
         
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderLayout('Shop/Admin/Views::stores/list_parents.php');
    }
    
    
}