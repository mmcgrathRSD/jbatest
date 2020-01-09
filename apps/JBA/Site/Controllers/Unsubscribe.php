<?php 
namespace RallySport\Site\Controllers;

class Unsubscribe extends \Dsc\Controller 
{
    public function index()
    {
       
    	$this->autoLogin();
        
    	//ELSE redirect to login and redirect to account with message
		
    	$identity = $this->getIdentity();
  
    	if (empty($identity->id))
    	{
    			\Dsc\System::instance()->get('session')->set('site.login.redirect', '/unsubscribe');
    			$this->app->reroute('/login');
    	}
    	else
    	{
    		\Dsc\System::addMessage('Choose your email preferences below', 'info');
    		$this->app->reroute('/shop/account');
    	}
        
        
    }
    
    protected function autoLogin() {

    	$user_id = $this->input->get('u', '', 'alnum');
    	$token = $this->input->get('t', '', 'alnum');
        	
    		if (!empty($user_id) || !empty($token))
    		{
    			$this->auth->loginWithToken($user_id, $token, '/unsubscribe' );
    		}
    }

    
}