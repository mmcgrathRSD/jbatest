<?php
namespace JBA\Site\Routes;

class Users extends \Dsc\Routes\Group
{

    public function initialize()
    {
        $f3 = \Base::instance();
        
        $this->setDefaults(array(
            'namespace' => '\JBA\Site\Controllers',
            'url_prefix' => '/profiles'
        ));
        
        $this->add('', 'GET', array(
            'controller' => 'Profiles',
            'action' => 'readSelf'
        ));
        
        $this->add('/settings', 'GET', array(
            'controller' => 'Profiles',
            'action' => 'settings'
        ));        
        
        $this->add('/@id', 'GET', array(
            'controller' => 'Profiles',
            'action' => 'read'
        ));
        
       
    }
}