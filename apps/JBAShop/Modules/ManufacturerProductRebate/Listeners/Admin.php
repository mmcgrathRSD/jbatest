<?php 
namespace JBAShop\Modules\ManufacturerProductRebate\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   	
        if ($module != "shop.homepageproducts::\JBAShop\Modules\ManufacturerProductRebate\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Products';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'JBAShop/Modules/ManufacturerProductRebate/Admin/Views' )
        ->renderView('JBAShop/Modules/ManufacturerProductRebate/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }
    

}