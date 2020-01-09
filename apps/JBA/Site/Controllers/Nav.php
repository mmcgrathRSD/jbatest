<?php 
namespace RallySport\Site\Controllers;

class Nav extends \Dsc\Controller 
{
    public function index()
    {
     
        
        echo $this->theme->render('RallySport\Site\Views::nav/index.php');
    }
    
   
}