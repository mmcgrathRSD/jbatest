<?php 
namespace JBAShop\Models;

use Dsc\Traits\Models\Publishable;

class TempNotifications extends \Dsc\Mongo\Collection
{
    protected $__collection_name = 'shop.notifications_temporary';
    protected $__type = 'stockalert';

    /** @var array */
    public $product;

    /** @var int */
    public $want_to_be_notified;

    /** @var array */
    public $notifications;


    public function fetchConditions()
    {
        parent::fetchConditions();

        $filterProduct = $this->getState('filter.part_number');
        if (!empty($filterProduct)) {
            $this->setCondition('product.tracking.model_number', trim($filterProduct));
        }

        $filterEmail = $this->getState('filter.email');
        if (!empty($filterEmail)) {
            $this->setCondition('notifications.email', trim($filterEmail));
        }

        return $this;
    }

    public function publishableStatusLabel()
    {
        switch ($this->{'product.publication.status'}) {
            case "unpublished":
                $label_class = 'label-default';
                break;

            case "discontinued":
                $label_class = 'label-warning';
                break;

            case "published":
                $label_class = 'label-success';
                break;

            default:
                $label_class = 'label-default';
        }

        return $label_class;
    }
}
