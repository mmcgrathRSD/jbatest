<?php 
namespace RallyShop\Admin\Controllers;

class Category extends \Shop\Admin\Controllers\Category
{
    use \Dsc\Traits\Controllers\CrudItemCollection;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/categories';
    protected $create_item_route = '/admin/shop/category/create';
    protected $get_item_route = '/admin/shop/category/read/{id}';    
    protected $edit_item_route = '/admin/shop/category/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \RallyShop\Models\Categories;
        return $model; 
    }
    
    protected function getItem() 
    {
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        $model = $this->getModel()
            ->setState('filter.id', $id);

        try {
            $item = $model->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $this->app->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    protected function displayCreate() 
    {
        $this->app->set('pagetitle', 'Edit Category');

        $model = new \Shop\Models\Categories;
        $all = $model->emptyState()->getList();
        $this->app->set('all', $all );
        
        $this->app->set('selected', null );
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCategoriesEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        $this->app->set('meta.title', 'Create Category | Shop');
        
        echo $view->render('Shop/Admin/Views::categories/create.php');        
    }
    
    protected function displayEdit()
    {
        $this->app->set('pagetitle', 'Edit Category');

        $model = new \Shop\Models\Categories;
        $categories = $model->emptyState()->getList();
        \Base::instance()->set('categories', $categories );
        
        $flash = \Dsc\Flash::instance();
        $selected = $flash->old('parent');
        \Base::instance()->set('selected', $selected );
        $this->app->set( 'allow_preview', $this->canPreview( true ) );
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopCategoriesEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        
        $this->app->set('meta.title', 'Edit Category | Shop');

        echo $view->render('Shop/Admin/Views::categories/edit.php');
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $this->app->reroute( $route );
    }
    
    protected function displayRead() {}
    
    public function quickadd()
    {
    	$model = $this->getModel();
    	$categories = $model->getList();
    	$this->app->set('categories', $categories );
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->renderLayout('Shop/Admin/Views::categories/quickadd.php');
    }
    
    
    protected function doUpdate(array $data, $key = null)
    {
    	if (empty($this->list_route))
    	{
    		throw new \Exception('Must define a route for listing the items');
    	}
    
    	if (empty($this->create_item_route))
    	{
    		throw new \Exception('Must define a route for creating the item');
    	}
    
    	if (empty($this->edit_item_route))
    	{
    		throw new \Exception('Must define a route for editing the item');
    	}
    
    	if (!isset($data['submitType']))
    	{
    		$data['submitType'] = "save_edit";
    	}
    
    	$f3 = \Base::instance();
    	$flash = \Dsc\Flash::instance();
    	$model = $this->getModel();
    	$this->item = $this->getItem();
    	if( $this->item == null ){
    		\Dsc\System::instance()->addMessage( 'This record does not exist', 'error' );
    		$f3->reroute( $this->list_route );
    	}
    
    	// save
    	$save_as = false;
    	try
    	{
    		$values = $data;
    		unset($values['submitType']);
    		// \Dsc\System::instance()->addMessage(\Dsc\Debug::dump($values), 'warning');
    		if ($data['submitType'] == 'save_as')
    		{
    			$this->item = $this->item->saveAs($values);
    			\Dsc\System::instance()->addMessage('Item cloned. You are now editing the new item.', 'success');
    		}
    		else
    		{
    			$this->item = $this->item->update($values);
    			\Dsc\System::instance()->addMessage('Item updated', 'success');
    		}
    	}
    	catch (\Exception $e)
    	{
    		 
    		\Dsc\System::instance()->addMessage('Save failed with the following errors:', 'error');
    		\Dsc\System::instance()->addMessage($e->getMessage(), 'error');
    		if (\Base::instance()->get('DEBUG'))
    		{
    			\Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
    		}
    
    		if ($f3->get('AJAX'))
    		{
    			// output system messages in response object
    			return $this->outputJson($this->getJsonResponse(array(
    					'error' => true,
    					'message' => \Dsc\System::instance()->renderMessages()
    			)));
    		}
    
    		// redirect back to the edit form with the fields pre-populated
    		\Dsc\System::instance()->setUserState('use_flash.' . $this->edit_item_route, true);
    		$flash->store($data);
    		$id = $this->item->get($this->getItemKey());
    		$route = str_replace('{id}', $id, $this->edit_item_route);
    
    		$custom_redirect = \Dsc\System::instance()->get('session')->get('update.redirect');
    		$route = $custom_redirect ? $custom_redirect : $route;
    
    		$this->setRedirect($route);
    
    		return false;
    	}
    
    	// redirect to the editing form for the new item
    	if (method_exists($this->item, 'cast'))
    	{
    		$this->item_data = $this->item->cast();
    	}
    	else
    	{
    		$this->item_data = \Dsc\ArrayHelper::fromObject($this->item);
    	}
    
    	if ($f3->get('AJAX'))
    	{
    		return $this->outputJson($this->getJsonResponse(array(
    				'message' => \Dsc\System::instance()->renderMessages(),
    				'result' => $this->item_data
    		)));
    	}
    
    	switch ($data['submitType'])
    	{
    		case "save_new":
    			$route = $this->create_item_route;
    			break;
    		case "save_close":
    			$route = $this->list_route;
    			break;
    		case "save_next":
    			$surrounding = $model->surrounding($this->item->id);
    			
    			if($surrounding['found']) {
    				$route = '/admin/shop/category/edit/'.$surrounding['next']->id;
    			} else {
    				$route = $this->list_route;
    			}
    			
    			break;
    		case "save_as":
    		default:
    			$flash->store($this->item_data);
    			$id = $this->item->get($this->getItemKey());
    			$route = str_replace('{id}', $id, $this->edit_item_route);
    			break;
    	}
    
    	$custom_redirect = \Dsc\System::instance()->get('session')->get('update.redirect');
    	$route = $custom_redirect ? $custom_redirect : $route;
    
    	\Dsc\System::instance()->get('session')->set('update.redirect', null);
    	$this->setRedirect($route);
    
    	return $this;
    }
    
}