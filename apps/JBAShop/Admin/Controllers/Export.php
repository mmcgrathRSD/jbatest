<?php
namespace JBAShop\Admin\Controllers;

class Export extends \Shop\Admin\Controllers\Export
{
    public function beforeRoute()
    {
        $this->app->set('meta.title', 'Export | Shop');
    }

    public function index()
    {
        $this->app->set('meta.title', 'Export | Shop');
        
        echo $this->theme->render('Shop/Admin/Views::export/index.php');
    }
    
    public function all_wishlists()
    {
        $time = time();
        $filename = \Base::instance()->get('PATH_ROOT') . 'tmp/' . $time . '.csv';
        
        $writer = (new \Ddeboer\DataImport\Writer\CsvWriter(","))->setStream(fopen($filename, 'w'));
        
        // Write column headers:
        $writer->writeItem(array(
            'id',
            'items_count',
            'email',
            'first_name',
            'last_name'            
        ));

        $options = [
            'projection' => [
                '_id' => true,
                'user_id' => true,
                'items_count' => true
            ],
            'sort' => [
                'items_count' => -1
            ]
        ];
        
        // write items
        $cursor = (new \Shop\Models\Wishlists)->collection()->find(array(
            'items_count' => array( '$gt' => 0 ),
            'user_id' => array( '$nin' => array('', null) ),
        ), $options);
        
        foreach ($cursor as $doc)
        {
            $item = new \Shop\Models\Wishlists( $doc );
            $user = $item->user();
            
            $writer->writeItem(array(
                $doc['_id'],
                (int) $doc['items_count'],
                $user->email(),
                $user->first_name,
                $user->last_name
            ));
        }
        
        \Web::instance()->send($filename, null, 0, true);
    }
    
    
    public function universalpartproducts()
    {
    	$time = time();
    	$filename = \Base::instance()->get('PATH_ROOT') . 'tmp/' . $time . '.csv';
    
    	$writer = (new \Ddeboer\DataImport\Writer\CsvWriter(","))->setStream(fopen($filename, 'w'));
    
    	// Write column headers:
    	$writer->writeItem(array(
    			'tracking.model_number',
    			'title',
    			'site',
    			'admin',
    			'universal',
    			'inventory',
    			
    	));
    
    	// write items
    	$cursor = (new \JBAShop\Models\Products)->collection()->find(array(
    			'ymms.0' => array( '$exists' => false )
    	), ['sort' => ['inventory_count' => -1]]);
    
    	foreach ($cursor as $doc)
    	{
    		
    
    		$line = [];
    		$line[] = $doc['tracking']['model_number'];
    		$line[] = $doc['title'];
    		$line[] = 'http://www.rallysportdirect.com/shop/product/.'.$doc['slug'];
    		$line[] = 'https://www.rallysportdirect.com/admin/shop/product/edit/'.$doc['slug'].'#tab_tab-ymm.';
    		if(!empty($doc['universalpart'])) {
    			$line[] = $doc['universalpart'];
    		} else {
    			$line[] = 'FALSE';
    		}
    		
    		if(!empty($doc['inventory_count'])) {
    			$line[] = $doc['inventory_count'];
    		} else {
    			$line[] = 0;
    		}
    		
    		$writer->writeItem($line);
    	}
    
    	\Web::instance()->send($filename, null, 0, true);
    }
    
    
}