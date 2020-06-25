<?php 

namespace JBAShop\Site;


class Listener extends \Prefab
{

    public  function onCreateManufacturerSeoTitle($event) {
        $brand = $event->getArgument('model');
        $title = '';
        if(!empty($brand->get('seo.page_title'))) {
            $title = $brand->get('seo.page_title');
        } else {
    
            $title .= $brand->title .' parts Save with Package Deals & Free Shipping.';
    
        }
    
        $title = str_replace(['  ',' ,'],[' ',','], $title);
    
        $event->setArgument('seo_title', $title);
        return $event;
    }
    
    public  function onCreateCategorySeoTitle($event) {
        $category = $event->getArgument('model');
        $title = '';
        if(!empty($category->get('seo.page_title'))) {
            $title = $category->get('seo.page_title');
        } else {
            $title = 'Performance ';
    
            $title .= $category->title;
            $title .=  ' parts ';
            $makes = ['Subaru', 'Ford', 'Scion', 'Mazda'];
            //TODO ADD SOME BRAND WEIGHTING HERE
            if(!empty($makes)) {
                $title .=  ' for ';
                foreach($makes as $make) {
                    if(strlen($title) < 53) {
                        $title .= trim($make);
                    } else {
                        break;
                    }
                    if(strlen($make) < 53) {
                        $title .= ', ';
                    } else {
                        break;
                    }
                }
                $title .=  ' and more ';
            } else {
                $title .=  ' Parts - Save with Package Deals & Free Shipping.';
            }
             
            $title = str_replace(['  ',' ,'],[' ',','], $title);
             
            if(strlen($title) < 40) {
                $title .= ' '. 'Free Shipping';
            }
        }
    
        $event->setArgument('seo_title', $title);
        return $event;
    }
    public  function onCreateProductSeoTitle($event) {
        $product = $event->getArgument('model');
        $title = '';
        if(!empty($product->get('seo.page_title'))) {
            $title = $product->get('seo.page_title');
        } else {
             
            //This prepends "Jeep JK: to parts only for one model
            if(!empty($product->get('ymmtext.primary')) && strpos(strtolower($product->get('ymmtext.primary')), 'jeep' ) !== false ) {
                if(!empty($product->get('title_suffix')) && strpos($product->get('title_suffix'), '/' ) == false ) {
                    $title .= 'Jeep ' .$product->get('title_suffix').' ';
                }
            }
            //removeing hyphens and stuff
            $title .= preg_replace('/[^a-zA-Z0-9 ]/', '', $product->title);;
            if(!empty($product->get('ymmtext.primary'))) {
                $title .= ' - '. $product->get('ymmtext.primary');
            }
    
            //adds oem model number
            if(!empty($product->get('tracking.model_number'))) {
                $title .= ' | '. trim(substr($product->get('tracking.model_number'), 3));
            }
    
            //clean up
            $title = str_replace(['  ',' ,'],[' ',','], $title);
             
    
            if(strlen($title) < 55) {
                $title .= ' - '. 'Free Shipping';
            }
    
        }
    
        $event->setArgument('seo_title', $title);
        return $event;
    }
    
    function productBackInstock($event) {
    	
    	$product = $event->getArgument('product');
    	
    	$collection = \Dsc\Mongo\Helper::getCollection('shop.notifications');
    	$docs = $collection->find(['product_id' => $product->id]);
    	
    	foreach ($docs as $doc) {
    		\Base::instance()->set('product', $event->getArgument('product'));
    		
    		$email = $doc['email'];
    		
    		$mailer = \Dsc\System::instance()->get('mailer');
    		if ($content = $mailer->getEmailContents('rallyshop.item_back_instock_notification', array(
    				'product' => $product,
    		))) {
    		   $mailer->sendEvent( $email, $content);
    		   try {
    		       (new \Shop\Models\Notifications())->bind($doc)->deleteDocument();
    		   } catch (\Exception $e) {
    		   }
    		   
    		}
    		
    	}
    	
    }
    
    
    function productOutOfStock($event) {
    	$product = $event->getArgument('product');
		
    	//if this product is clearance send email to make that it is out of stock, ,maybe get emails from settings
    	 
    	/*foreach ($docs as $doc) {
    		\Base::instance()->set('product', $event->getArgument('product'));
    
    		$email = $doc['email'];
    
    		$mailer = \Dsc\System::instance()->get('mailer');
    		if ($content = $mailer->getEmailContents('rallyshop.item_back_instock_notification', array(
    				'product' => $product,
    		))) {
    			$mailer->send( $email, $content['subject'], $content['body'], $content['fromEmail'], $content['fromName'] );
    		}
    	}*/
    }

	function siteMapRegisterRoutes($event) {
	    $time = time();
	    $climate = new \League\CLImate\CLImate;
        $routes = $event->getArgument('routes');
        $sitemap = $event->getArgument('sitemap');
        $salesChannel = $event->getArgument('salesChannel');
		$product_counts = 0;
        $product_category_counts = 0;
        $brand_counts = 0;
        $brand_category_counts = 0;
        $ymm_counts = 0;
        $ymm_category_counts = 0;
        $ymm_category_product_counts = 0;
       
        /*
         * GET ALL THE STANDARD PRODUCT LINKS
         */
         
        $products = (new \Shop\Models\Products)->collection()->find([
            'publication.status' => 'published',
            'publication.sales_channels.slug' => $salesChannel->get('slug')
        ], [
            'sort' => [
                'metadata.last_modified.time' => -1
            ]
        ]);
        $productRoutes = [];
        $questions = [];
        $reviews = [];
    
        foreach($products as $product) {  
            $climate->blue(time() - $time . ' adding product ' . $product['slug']);
            $product_counts++;
            $modelInstance = (new \JBAShop\Models\Products)->bind($product);
            $created = @$product['metadata']['created']['time'];
            $lastMod = @$product['metadata']['last_modified']['time'];
            $priority = \Dsc\Sitemap::priority($lastMod, $created);
            $sitemap->addItem(
                $modelInstance->generateStandardURL(),
                $priority,
                'daily',
                @$product['metadata']['last_modified']['time']
            );
            //add canonical url
            $sitemap->addItem(
                $modelInstance->generateCanonicalURL(false),
                $priority,
                'daily',
                @$product['metadata']['last_modified']['time']
            );

            //add all product category urls.
            foreach($modelInstance->generateAncestorCategoryUrls($salesChannel['slug']) as $ancestralCategoryUrl){
                $sitemap->addItem(
                    $ancestralCategoryUrl,
                    $priority,
                    'daily',
                    @$product['metadata']['last_modified']['time']
                );
            }

            $sitemap->addItem(
                $modelInstance->generateStandardURL().'/questions',
                $priority,
                'daily',
                @$product['metadata']['last_modified']['time']
            );
            $sitemap->addItem(
                $modelInstance->generateStandardURL().'/reviews',
                $priority,
                'daily',
                @$product['metadata']['last_modified']['time']
            );
        }

        /*
        * FITS ROUTES /FITS/@SLUG, /FITS/@SLUG/@CAT/, /FITS/@SLUG/@CAT/@PRODUCT
        */
        // $ymmRoutes = [];
        // // ymms
        // $ymms = (new \Shop\Models\YearMakeModels)->collection()->find(
        //     ['publication.status' => 'published'],
        //     ['sort' => ['metadata.last_modified_time' => -1]]
        // );
        
        // foreach($ymms as $vehicle) {
        //     $climate->blue(time() - $time . ' adding vehicle ' . $vehicle['slug']);
        //     $ymm_counts++;
        //     $created = @$vehicle['metadata']['created']['time'];
        //     $lastMod = @$vehicle['metadata']['last_modified']['time'];
        //     $sitemap->addItem(
        //         '/fits/'.$vehicle['slug'].'',
        //         '1.0',
        //         'daily',
        //         @$vehicle['metadata']['last_modified']['time']
        //     );
        //     /* Lets build the vehicle pages by category */
        //     $products_model = (new \Shop\Models\Products);
        //     $products_model->setState('filter.publication_status',  'published');
        //     $products_model->setState('filter.ymm.slug',  $vehicle['slug']);
        //     $conditions = $products_model->conditions();
        //     $catids = $products_model->collection()->distinct('categories.id', $conditions);
        //     $categories = (new  \Shop\Models\Categories)->collection()->find([
        //         '_id' => ['$in' => $catids],
        //         '$or' => [
        //             ['sales_channels.0' => ['$exists' => false]],
        //             ['sales_channels.slug' => \Base::instance()->get('sales_channel')]
        //         ]
        //     ],
        //     ['sort' => ['title' => 1]]);

        //     foreach ($categories as $ymmCat) {
        //         $ymm_category_counts++;
        //         $created = @$ymmCat['metadata']['created']['time'];
        //         $lastMod = @$ymmCat['metadata']['last_modified']['time'];
        //         $sitemap->addItem(
        //             '/fits/'.$vehicle['slug'].'/'.$ymmCat['slug'],
        //             '1.0',
        //             'daily',
        //             @$ymmCat['metadata']['last_modified']['time']
        //         );
        //         $products_model = (new \Shop\Models\Products);
        //         $products_model->setState('filter.publication_status',  'published');
        //         $products_model->setState('filter.ymm.slug',  $vehicle['slug']);
        //         $products_model->setState('filter.category.slug',  $ymmCat['slug']);
        //         /*   $lists = $products_model->getItems();
                
        //         foreach($lists as $item) {
        //             $ymm_category_product_counts++;
        //             $ymmRoutes[] = [
        //                 'loc' => '/fits/'.$vehicle['slug'].'/'.$ymmCat['slug'].'/'.$item->slug,
        //                 'pri' => '1.0',
        //                 'change' => 'daily',
        //                 'mod' => @$item->get('metadata.last_modified.time')
        //             ];
        //         }*/
        //     }
        // }
    
        /*
        * CATEGORY PAGES
        */
        $categoryRoutes = [];
        $categories = (new \JBAShop\Models\Categories)->collection()->find([
            '$or' => [
                ['sales_channels.0' => ['$exists' => false]],
                ['sales_channels.slug' => $salesChannel->get('slug')]
            ]
        ], [
            'projection' => [
                'slug' => 1,
                'metadata.last_modified.time' => 1,
                'metadata.created.time' => 1
            ],
            'sort' => ['metadata.last_modified.time' => -1]
        ]);
        foreach($categories as $category) {
            $climate->blue(time() - $time . ' adding category ' . $category['slug']);
            $product_category_counts++;
            $sitemap->addItem(
                '/scp/'.$category['slug'],
                \Dsc\Sitemap::priority($lastMod, $created),
                'daily',
                $category['metadata']['last_modified']['time']
            );
        }

        $sitemap->addItem(
            '/reviews',
            '1.0',
            'daily',
            time()
        );
	}
  
  public function onBeforeGetShipments($event){

        $event->setArgument('backorderAllItems', false);

        $cart = $event->getArgument('cart');

        $checkoutType = \Base::instance()->get('GET.checkoutType');
        if($checkoutType && $checkoutType == 'affirm'){
            foreach ($cart->items as $item){
                $cartItem = (new \Shop\Models\Prefabs\CartItem)->bind($item);
                if($cartItem->isBackorder()){
                    return $event->setArgument('backorderAllItems', true);
                }
            }
        }
    }
}
