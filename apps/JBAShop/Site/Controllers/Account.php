<?php 
namespace JBAShop\Site\Controllers;

class Account extends \Shop\Site\Controllers\Account 
{
    public function beforeRoute()
    {
        $this->requireIdentity();
        $this->app->set('invert', 1);
    }
}
