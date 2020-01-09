<?php 
namespace JBAShop\Modules\HomepageProducts\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   	
        if ($module != "shop.homepageproducts::\JBAShop\Modules\HomepageProducts\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Products';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'JBAShop/Modules/HomepageProducts/Admin/Views' )
        ->renderView('JBAShop/Modules/HomepageProducts/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }
    

}