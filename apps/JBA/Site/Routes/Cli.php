<?php
namespace JBA\Site\Routes;

class Cli extends \Dsc\Routes\Group
{

    public function initialize()
    {
        $f3 = \Base::instance();

        $this->setDefaults(array(
            'namespace' => '\JBA\Site\Controllers',
            'url_prefix' => ''
        ));

        $this->add('/diagnostics', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'run'
        ));

        $this->add('/jobs/updatenewproductflags', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'updateFlags'
        ));

        $this->add('/jobs/sendshopperapprovedemails', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'sendShopperApprovedEmails'
        ));

        $this->add('/jobs/sendreviewproductsemails', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'sendReviewProducts'
        ));
        $this->add('/jobs/buildsitemap', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'buildSiteMap'
        ));

        $this->add('/jobs/creategoogleproductfeeds', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'createGoogleProductFeeds'
        ));
        
        $this->add('/jobs/createrichrevelancefeeds', 'GET', array(
            'controller' => 'Diagnostics',
            'action' => 'RichRevelance'
        ));









    }
}
