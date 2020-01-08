<?php
namespace RallyShop\Models;

class CustomerAddresses extends \Shop\Models\CustomerAddresses
{
    /**
     * Get the current user's addresses
     *
     * @return array<\RallyShop\Models\CustomerAddresses>
     */
    public static function fetch()
    {
        $identity = \Dsc\System::instance()->get('auth')->getIdentity();
        if (empty($identity->id))
        {
            return array();
        }
        
        $items = (new static)
            ->setCondition('user_id', new \MongoDB\BSON\ObjectID((string) $identity->id))
            ->setCondition('deleted_at', null)
            ->getItems()
        ;

        return $items;
    }
    
    /**
     * Get the addresses for a specified user id
     *
     * @return array<\RallyShop\Models\CustomerAddresses>
     */
    public static function fetchForId($id)
    {
        $items = (new static)
            ->setCondition('user_id', new \MongoDB\BSON\ObjectID((string) $id))
            ->setCondition('deleted_at', null)
            ->getItems()
        ;

        return $items;
    }

    public function softDelete()
    {
        $this->update(['deleted_at' => new \MongoDB\BSON\UTCDateTime()], ['overwrite' => false]);

        return $this;
    }
}