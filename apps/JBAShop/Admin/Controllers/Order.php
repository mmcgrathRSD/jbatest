<?php 
namespace JBAShop\Admin\Controllers;

class Order extends \Shop\Admin\Controllers\Order
{
    use \Dsc\Traits\Controllers\CrudItemCollection;

    protected $list_route = '/admin/shop/orders';
    protected $create_item_route = '/admin/shop/order/create';
    protected $get_item_route = '/admin/shop/order/read/{id}';    
    protected $edit_item_route = '/admin/shop/order/edit/{id}';
    
    protected function getModel() 
    {
        $model = new \JBAShop\Models\Orders;
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
    
    public function rePack()
    {
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    	
    	try {
    	
    		\JBAShop\Models\Orders::buildShippingPackages($id);
    		\Dsc\System::instance()->addMessage( "Items Repacked", 'success');
    	} catch (\Exception $e) {
    		echo $e->getMessage(); die();
    	}
    	
    	$route = str_replace('{id}', $id, $this->edit_item_route );
    	$f3->reroute( $route );
    

    }
    
    
    
    protected function displayCreate() 
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Orders;
        
        $this->app->set('meta.title', 'Create Order | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrdersEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );
        echo $view->render('Shop/Admin/Views::orders/create.php');        
    }
    
    protected function displayEdit()
    {
        $f3 = \Base::instance();

        $model = new \Shop\Models\Orders;
        
        $flash = \Dsc\Flash::instance();
        
        $this->app->set('meta.title', 'Edit Order | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopOrdersEdit', array( 'item' => $this->getItem(), 'tabs' => array(), 'content' => array() ) );        
        echo $view->render('Shop/Admin/Views::orders/edit.php');
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
    
    protected function doDelete(array $data, $key=null)
    {
        $this->setRedirect( $this->list_route );
        
        return $this;
    }
    
    public function generateXml () {
    	//queue the xml add a message
    	$item = $this->getItem();
    	$item->set('__regenerateXML', true);
    	
        $item->checkGenerateXmlStatus(true);

    	\Dsc\System::addMessage('The ORDER XML was batched to the Queue should appear in GP shortly.');
    	
    	\Base::instance()->reroute('/admin/shop/orders');
    }
    
    public function fulfill()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->fulfill();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function fulfillGiftCards()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
        
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
            	throw new \Exception;
            }
            $item->fulfillGiftCards();
        } 
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
        
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function close()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->close();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function cancel()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            
            if( $item->status == \Shop\Constants\OrderStatus::cancelled ){
            	\Dsc\System::addMessage( 'This order was already cancelled.', 'WARNING' );
            } else {
            	$item->cancel();
            }
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    public function open()
    {
        $data = \Base::instance()->get('REQUEST');
        if (!$this->canUpdate($data, $this->getItemKey())) {
            throw new \Exception('Not allowed to update record');
        }
    
        try {
            $item = $this->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
            $item->open();
        }
        catch(\Exception $e) {
            \Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
            \Dsc\System::instance()->addMessage($e->getMessage(), 'error');
            if (\Base::instance()->get('DEBUG')) {
                \Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
            }
        }
    
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
        $redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
        \Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
        $f3->reroute( $redirect );
    }
    
    
    public function chargePayment()
    {
    	$data = \Base::instance()->get('REQUEST');
    	if (!$this->canUpdate($data, $this->getItemKey())) {
    		throw new \Exception('Not allowed to update record');
    	}
    
    	try {
    		$item = $this->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}
    		
    		$item->fulfill();
    	}
    	catch(\Exception $e) {
    		\Dsc\System::instance()->addMessage('Fulfillment failed with the following errors:', 'error');
    		\Dsc\System::instance()->addMessage($e->getMessage(), 'error');
    		if (\Base::instance()->get('DEBUG')) {
    			\Dsc\System::instance()->addMessage($e->getTraceAsString(), 'error');
    		}
    	}
    
    	$f3 = \Base::instance();
    	$id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
    
    	$custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'order.fulfill.redirect' );
    	$redirect = $custom_redirect ? $custom_redirect : '/admin/shop/order/edit/' . $id;
    	\Dsc\System::instance()->get( 'session' )->set( 'order.fulfill.redirect', null );
    	$f3->reroute( $redirect );
    }
    
    
    public function editInline() {
    	 
    	 
    	try {
    		 
    
    
    		$id = $this->inputfilter->clean( $this->app->get('POST.pk'), 'alnum' );
    		$name = $this->inputfilter->clean( $this->app->get('POST.name'), 'string' );
    		$value = $this->inputfilter->clean( $this->app->get('POST.value'), 'string' );
    
    		if(empty($id) || empty($name) || empty($value) ) {
    			throw new \Exception('One of your values is empty');
    		}
    
    		if (!$this->canUpdate(array('id' => $id, 'name' => $name, 'value' => $value), $this->getItemKey())) {
    			throw new \Exception('Not allowed to edit record');
    		}
    
    
    
    		$mongoItem = $model = $this->getModel()
    		->setState('filter.id', $id)->getItem();;
    		
    		// log this in the order's internal history
    		$mongoItem->history[] = array(
    				'created' => \Dsc\Mongo\Metastamp::getDate('now'),
    				'verb' => 'updated '. $name,
    				'object' => 'From: '. $mongoItem->get($name) . ' To '. $value,
    				'actor' => $this->auth->getIdentity()->fullName()
    		);
    
    		$mongoItem->set($name, $value);
    		$mongoItem->save();
    
    		echo json_encode(array('success' => true));
    
    		 
    
    	} catch (\Exception $e) {
    		$this->app->error(404);
    		echo json_encode(array('success' => flase, 'msg'=>$e->getMessage() ));
    	}
    	 
    	 
    	 
    	 
    	 
    	 
    }
    
    
    
    
    
}