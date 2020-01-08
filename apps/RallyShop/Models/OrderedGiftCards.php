<?php
namespace RallyShop\Models;

class OrderedGiftCards extends \Shop\Models\OrderedGiftCards
{
    public $code;
    public $invoice_number;
    public $initial_value;
    public $balance;
    public $created_at;
    public $recipient_email;

    protected $__collection_name = 'shop.orders.giftcards';
    protected $__type = 'shop.orders.giftcards';
    protected $__config = array(
        'default_sort' => array(
            'created_at' => -1
        )
    );

    protected function beforeValidate()
    {

    }

    public function validate()
    {
        if (empty($code) || empty($this->initial_value))
        {
            $this->setError('Gift Cards must have a code and an initial value');
        }
    }

    protected function beforeSave()
    {

    }

    protected function beforeCreate()
    {

    }

    protected function afterCreate()
    {

    }

    public function redeemForOrderAmount( $amount, \RallyShop\Models\Orders $order )
    {
    	// Deduct the amount from the balance
    	$balance_before = $this->balance();
    	$this->balance = $this->balance() - (float) $amount;

    	// TODO Track this in f3-activity

    	return $this->save();
    }

    public function redeemForOrder( $amount, \Shop\Models\Orders $order )
    {
        // Deduct the amount from the balance
        $balance_before = $this->balance();
        $this->balance = $this->balance() - (float) $amount;

        // TODO Track this in f3-activity

        return $this->save();
    }
    
    
  
    
    	
}
