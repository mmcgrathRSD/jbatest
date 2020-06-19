<?php 
namespace JBAShop\Modules\HomepageMidSlider\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   	
        if ($module != "shop.homepagemidslider::\JBAShop\Modules\HomepageMidSlider\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Slider Options';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'JBAShop/Modules/HomepageMidSlider/Admin/Views' )
        ->renderView('JBAShop/Modules/HomepageMidSlider/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    }


}