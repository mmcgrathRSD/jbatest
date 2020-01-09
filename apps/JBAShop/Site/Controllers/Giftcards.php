<?php 
namespace JBAShop\Site\Controllers;

class Giftcards extends \Users\Site\Controllers\Auth
{    
   
    
    public function index()
    {
    	
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::giftcards/index.php');
    	
    }
    
    public function checkCard()
    {
    	 
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::giftcards/index.php');
    	 
    }
    
    protected function showCard()
    {
    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::giftcards/balance.php');
    
    }
    
}