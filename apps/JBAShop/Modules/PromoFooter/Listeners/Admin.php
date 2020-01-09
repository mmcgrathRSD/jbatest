<?php 
namespace JBAShop\Modules\PromoFooter\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   
        if ($module != "shop.promofooter::\JBAShop\Modules\PromoFooter\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Promo Footer Options';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'JBAShop/Modules/PromoFooter/Admin/Views' )
        ->renderView('JBAShop/Modules/PromoFooter/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    } 
}