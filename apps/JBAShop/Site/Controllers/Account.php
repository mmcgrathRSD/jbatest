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

    public function updateCustomer(){
        //get the user
        $identity = $this->getIdentity()->reload();
        try{
            //get the account
            $account = (new \Shop\Models\Account)->setCondition('_id', $identity->id)->getItem();
            //set the first_name
            $account->set('first_name',  $this->inputfilter->clean( $this->app->get('POST.first_name'), 'string' ));
            //set the last name
            $account->set('last_name', $this->inputfilter->clean( $this->app->get('POST.last_name'), 'string' ));
            //save the account data.
            $account->save();
            //push message to user.
            \Dsc\System::addMessage('The account information has been saved.', 'success');
        }catch(\Exception $e){
            //do nothing besides push default error message to user.
            \Dsc\System::addMessage('Something went wrong.', 'error');
        }
        //redirect back to user's account.
        $this->app->reroute('/shop/account');
    }
}
