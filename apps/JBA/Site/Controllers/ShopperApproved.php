<?php
namespace JBA\Site\Controllers;

class ShopperApproved extends \Dsc\Controller
{
    public function index()
    {
        $this->registerName(__METHOD__);

        $this->app->set('meta.title', 'Rally Sport Direct Customer Reviews');


        $this->app->set('meta.description', 'Over 18000 positive reviews from our great customers');

        echo $this->theme->render('JBA\Site\Views::shopperapproved/default.php');
    }



}
