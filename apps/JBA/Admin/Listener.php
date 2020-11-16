<?php
namespace JBA\Admin;

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
        $html = $view->renderLayout('JBA/Admin/Views::shop_customer/read.php');
    
        $tabs[] = 'JBA';
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
    
        $tabs['rallysport'] = 'JBA';
        $content['rallysport'] = $view->renderLayout('JBA/Admin/Views::users/tab.php');
    
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

    public function afterSaveShopModelsProducts($event){
        try{
            $app = \Base::instance();
            $product = $event->getArgument('model');//get the product from the event.
            $hadRsdRetail = $product->checkPublicationChannel($app->get('sales_channel'), true);//check the previous model info for sales channel.
            $noLongerHasRsdRetail = !$product->checkPublicationChannel($app->get('sales_channel'));//check current model info for sales channel.
            if($app->get('listrak.push_product_updates') && $hadRsdRetail && $noLongerHasRsdRetail){//if the product had the sales channel and no longer has sales channel then we need to update listrak.
                $path = $app->get('TEMP');//target the {{release}}/tmp directory.
                //Update product data in Listrak.
                $item = $product->getListrakItem();//get the listrak data array for this product.
                $now = \Carbon\Carbon::now()->timestamp;//get now.
                $salesChannel = (new \Shop\Models\SalesChannels())->setCondition('slug', $app->get('sales_channel'))->getItem();
                if (!file_exists($path)) {
                    if(!mkdir($path)){
                        throw new \Exception('Error creating directory for Listrak update.');
                    }
                }

                $fileName = "{$path}Products_{$now}{$product->get('tracking.model_number_flat')}.csv";//Set path the same path as datafeed.
                $writer = new \Ddeboer\DataImport\Writer\CsvWriter(',', '"', null, true);//new writer
                $writer->setStream(fopen($fileName, 'w'));
                $writer->writeItem(array_keys($item));//Add headers for csv.
                $writer->writeItem($item);//Add product data to file
                $spl = new \SplFileObject($fileName);

                if ($connection = ftp_connect('ftp.listrakbi.com')) {//connect to ftp
                    if (ftp_login($connection, $app->get("listrak.{$salesChannel->get('slug')}_username"), $app->get("listrak.{$salesChannel->get('slug')}_password"))) {//login to ftp
                        if (ftp_pasv($connection, true)) {//set passive mode true.
                            if (ftp_put($connection, $spl->getBasename(), $fileName, FTP_BINARY)) {//upload file to ftp server
                                \Dsc\System::instance()->addMessage('Item queued Listrak update.', 'success');//inform the user
                            }else{
                                \Dsc\System::instance()->addMessage('Listrak sync failed.', 'warning');//warn the user
                            }
                        }
                    }

                    ftp_close($connection);//close the connection
                }

                unlink($fileName);//remove file from local.
            }
        }catch(\Exception $e){
            \Dsc\System::instance()->addMessage($e->getMessage(), 'warning');//inform the user
        }
    }
}
