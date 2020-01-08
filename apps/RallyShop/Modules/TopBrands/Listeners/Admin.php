<?php 
namespace RallyShop\Modules\TopBrands\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   
        if ($module != "shop.topbrands::\RallyShop\Modules\TopBrands\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Top Brands Options';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'RallyShop/Modules/TopBrands/Admin/Views' )
        ->renderView('RallyShop/Modules/TopBrands/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    } 
}