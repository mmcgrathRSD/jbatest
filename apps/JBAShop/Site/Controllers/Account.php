<?php 
namespace JBAShop\Site\Controllers;

class Account extends \Shop\Site\Controllers\Account 
{
    public function beforeRoute()
    {
        $this->requireIdentity();
    }

    public function information()
    {
        $this->app->set('identity', $this->getIdentity()->reload());
        $this->app->set('meta.title', 'Account Information'); // TODO: rename this
        $this->app->set('page', 'my-account');

		echo $this->theme->render('Shop/Site/Views::account/information.php');
    }

    public function orders()
    {
        $this->app->set('identity', $this->getIdentity()->reload());
        $this->app->set('meta.title', 'My Orders'); // TODO: rename this
        $this->app->set('page', 'my-account');

		echo $this->theme->render('Shop/Site/Views::account/orders.php');
    }
}
