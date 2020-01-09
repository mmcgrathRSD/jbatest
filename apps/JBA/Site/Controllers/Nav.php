<?php 
namespace JBA\Site\Controllers;

class Nav extends \Dsc\Controller 
{
    public function index()
    {
     
        
        echo $this->theme->render('JBA\Site\Views::nav/index.php');
    }
    
   
}