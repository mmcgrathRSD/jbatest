<?php 
namespace RallyShop\Modules\DisruptorEngine\Listeners;

class Admin extends \Prefab 
{
    public function onDisplayAdminModuleEdit( $event ) 
    {
        $module = $event->getArgument('module');
   
        if ($module != "shop.disruptorengine::\RallyShop\Modules\DisruptorEngine\Module") {
            return;
        }

        $item = $event->getArgument('item');
        $tabs = $event->getArgument('tabs');
        $content = $event->getArgument('content');
        
        $tabs[] = 'Disruptor Engine Options';
        
        $temp_ui = dirname( __FILE__ ) . "/../Admin/Views/";
        
        \Base::instance()->set('item', $item);
        
        $content[] = \Dsc\System::instance()->get('theme')
        ->registerViewPath( $temp_ui, 'RallyShop/Modules/DisruptorEngine/Admin/Views' )
        ->renderView('RallyShop/Modules/DisruptorEngine/Admin/Views::form.php');
        
        $event->setArgument('tabs', $tabs);
        $event->setArgument('content', $content);
    } 
}