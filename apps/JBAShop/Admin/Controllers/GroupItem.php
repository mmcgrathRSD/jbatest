<?php 
namespace RallyShop\Admin\Controllers;

class GroupItem extends \RallyShop\Admin\Controllers\Product
{
   
   
    protected $edit_item_route = '/admin/shop/product/edit/{id}#tab_tab-group';
    
  
    
    public function removeItemFromGroup() {
    	$product = $this->getItem();
    	
    	$groupid =  $this->app->get('PARAMS.groupitemid');
    	
    	$groupItems = $product->group_items;
    	
    	unset($groupItems[$groupid]);
    	
    	$product->set('group_items', $groupItems);
    	
    	$product->save();

    	\Dsc\System::addMessage('Group Item Removed');
    	$id = $product->id;
    	$route = str_replace('{id}', $id, $this->edit_item_route );
    	$this->app->reroute( $route );
    }
    
    public function addItemToGroup() {
			
    	$sku = $this->app->get('POST.part_number');
    	$quantity = $this->app->get('POST.quantity');
    	$product = $this->getItem();
    	
    	$groupItem =  $this->getModel()->setCondition('variants.model_number', $sku)->getItem();
    	
    	$source = array(
        'id'=> (string) $groupItem->id,                         // (string) \MongoDB\BSON\ObjectID
        'sku'=>$sku,      
    	'quantity'=> $quantity
   		 );
    	
    	$new = new \Shop\Models\Prefabs\GroupItem($source);
    	
    	$product->addGroupItem($new);
    	
    	\Dsc\System::addMessage('Group Item Added');
    	$id = $product->id;
    	$route = str_replace('{id}', $id, $this->edit_item_route );
    	$this->app->reroute( $route );
    }
    
   
}