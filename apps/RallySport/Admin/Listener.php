<?php
namespace RallySport\Admin;

class Listener extends \Prefab
{
	public function onSystemRebuildMenu($event)
    {
    /*    if ($model = $event->getArgument('model'))
        {
            $root = $event->getArgument('root');
            $rallysport = clone $model;
            
            $rallysport->insert(array(
                'type' => 'admin.nav',
                'priority' => 10,
                'title' => 'Rally Sport',
                'icon' => 'fa fa-car',
                'is_root' => false,
                'tree' => $root,
                'base' => '/admin/rallysport'
            ));
            
            $children = array(
                array(
                    'title' => 'Homepage',
                    'route' => './admin/rallysport/site/home',
                    'icon' => 'fa fa-home'
                ),
                array(
                    'title' => 'Shop Homepage',
                    'route' => './admin/rallysport/shop/home',
                    'icon' => 'fa fa-shopping-cart'
                ),
                           
            );
            $rallysport->addChildren($children, $root);
            
            
            // Find the Catalog Menu Item
            $catalog_item = (new \Admin\Models\Nav\Primary())->load(array(
            		'type' => 'admin.nav',
            		'path' => '/admin-primary-navigation/shop/catalog',
            		'title' => 'Catalog'
            ));
            
            $children = array(
            		array(
            				'title' => 'YYMs Filters',
            				'route' => './admin/shop/yearmakemodels',
            				'icon' => 'fa fa-home'
            		)
            		 
            );
            
            $catalog_item->addChildren($children);
         
            
            \Dsc\System::instance()->addMessage('Rally Sport added its admin menu items.');
        } */
    }
    
    public function onDisplayShopCustomers($event)
    {
        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
    
        \Base::instance()->set('item', $event->getArgument('item'));
        $view = \Dsc\System::instance()->get('theme');
        $html = $view->renderLayout('RallySport/Admin/Views::shop_customer/read.php');
    
        $tabs[] = 'RallySport';
        $content[] = $html;
    
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }    
    
    public function onDisplayAdminUserEdit($event)
    {
        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
    
        \Base::instance()->set('item', $item);
        $view = \Dsc\System::instance()->get('theme');
    
        $tabs['rallysport'] = 'RallySport';
        $content['rallysport'] = $view->renderLayout('RallySport/Admin/Views::users/tab.php');
    
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }

    public function onSystemRegisterEmails($event)
    {
        if (class_exists('\Mailer\Factory'))
        {
            \Mailer\Models\Events::register('admin.status_report',
                [
                    'title' => 'Status Report',
                    'copy' => 'Emails when an employee completes a status report',
                    'app' => 'Admin',
                ],
                [
                    'event_subject' => 'Status Report',
                    'event_html'    => file_get_contents(__DIR__ . '/Emails/html/status_report.php'),
                    'event_text'    => file_get_contents(__DIR__ . '/Emails/text/status_report.php')
                ]
            );

            \Dsc\System::instance()->addMessage('Admin added its emails.');
        }
    }
}
