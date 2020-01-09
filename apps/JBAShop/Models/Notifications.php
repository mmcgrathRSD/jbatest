<?php 
namespace RallyShop\Models;

class Notifications extends \Dsc\Mongo\Collections\Nodes
{
   
    protected $__collection_name = 'shop.notifications';
    protected $__type = 'stockalert';
        
    public $product; // array(id, title, slug)
    public $variant_id; // string INDEX
    public $user; // array(id, name)
    public $email; // 
   
    protected function fetchConditions()
    {
        parent::fetchConditions();
        $this->setCondition('type', $this->__type );


        $filter_product = $this->getState('filter.product');
        $filter_product_slug = $this->getState('filter.product_slug');
        $filter_user = $this->getState('filter.user');

        if (strlen($filter_product))
        {
            $this->setCondition('product.id',  new \MongoDB\BSON\ObjectID($filter_product) );
        }
        if (strlen($filter_product_slug))
        {
            $this->setCondition('product.slug',  $filter_product_slug );
        }
        if (strlen($filter_user))
        {
            $this->setCondition('user.id',  new \MongoDB\BSON\ObjectID($filter_user) );
        }
    } 	     
}