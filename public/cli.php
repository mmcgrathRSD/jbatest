<?php
if (php_sapi_name() != "cli") {
	die('no way bro');
}
//AUTOLOAD all your composer libraries now.
(@include_once (__dir__ . '/../vendor/autoload.php')) OR die("You need to run php composer.phar install for your application to run.");
//Require FatFree Base Library https://github.com/bcosca/fatfree
$app = Base::instance();
//Set the PATH so we can use it in our apps
$app->set('PATH_ROOT', __dir__ . '/../');
//This autoload loads everything in apps/* and 
$app->set('AUTOLOAD',  $app->get('PATH_ROOT') . 'apps/;');
//load the config files for enviroment
$app->set('APP_NAME', 'cli');

require $app->get('PATH_ROOT') . 'config/config.php';
//SET the "app_name" or basically the instance so we can server the admin or site from same url with different loaded classes

\Cloudinary::config(array(
	"cloud_name" => $app->get('cloudinary.cloud_name'),
	"api_key"    => $app->get('cloudinary.api_key'),
	"api_secret" => $app->get('cloudinary.api_secret')
));

$app->route('GET /algolia-unpublished', function() {
    (new \Search\Models\Algolia\Products)->removeUnpublished();
});

$app->route('GET /algolia-ymm',
    function() {
        (new \Search\Models\Algolia\YearMakeModels())->generateHashes();
        //(new \RallySport\Models\Algolia\YearMakeModels())->algoliaTotalSync();
    }
);

$app->route('GET /test-user',
    function() {
        $user = \Netsuite\Models\Customer::getCustomerFromEmail('chris@ammonitenetworks.com');
        var_dump($user->externalId);
    }
);


$app->route('GET /ns-test',
    function() {


$config = array(
   // required -------------------------------------
   "endpoint" => "2017_1",
   "host"     => "https://webservices.sandbox.netsuite.com",
   "email"    => "chris.french@rallysportdirect.com",
   "password" => "F0rgetting01!",
   "role"     => "3",
   "account"  => "4132604",
   "app_id"   => "30940DC2-D795-4AEC-9AD1-1B9A8C2A8013",
   // optional -------------------------------------
   "logging"  => true,
   "log_path" => "/home/french/rallysportdirect.com/tmp"
);

$service = new \NetSuite\NetSuiteService($config);



       $request = new \NetSuite\Classes\GetRequest();
$request->baseRef = new \NetSuite\Classes\RecordRef();
$request->baseRef->internalId = "47760";
$request->baseRef->type = "customer";

$getResponse = $service->get($request);

if ( ! $getResponse->readResponse->status->isSuccess) {
    echo "GET ERROR";
} else {
    $customer = $getResponse->readResponse->record;
}

    }
);

$app->route('GET /debug-test',
    function() {
       $orde = 5;
    }
);

$app->route('GET /subitems', function() {
    \Shop\Models\Collections::updateActiveProducts('5bb3c0bbd6f5530f8b23cbd7');
});

$app->route('GET /assign-manufacturers-sales-channels', function() {
    (new \Shop\Models\Manufacturers)->assignSalesChannels();
});

$app->route('GET /blind-test',
    function() {
       $order = (new \RallyShop\Models\Orders)->setCondition('number', 'WS-1194134')->getItem();
      
       $order->sendOrderShippedEmail();
    }
);

$app->route('GET /kit-test', function() {
    $product = (new \Shop\Models\Products)
        ->setCondition('tracking.model_number', 'RSD BRACING-KIT')
        ->getItem();

    var_dump($product->isConfigurable());
});

$app->route('GET /sync-algolia', function() {
    (new \Search\Models\Algolia\Products())->algoliaStandardSync();
});

$app->route('GET /process-algolia-items', function() {
    (new \Search\Models\AlgoliaSyncItem())->algoliaStandardSync();
});

$app->route('GET /sync-algolia-full', function() {
    (new \Search\Models\Algolia\Products())->algoliaTotalSync();
});

/**Christmas bandaid**/
$app->route('GET /bandaid-sync-giftcards', function() {
    (new \Search\Models\Algolia\Products())->syncGiftcard();
});

$app->route('GET /bandaid-kits-and-matrix', function() {
    (new \Search\Models\Algolia\Products())->syncKitsAndMatrix();
});
/**END Christmas bandaid**/

$app->route('GET /remove-algolia-lost-children', function() {
    (new \Search\Models\Algolia\Products())->removeAnyLostChildren();
});

$app->route('GET /sync-algolia-wholesale-orders', function() {
    // TODO: change this route name and make it sync ALL orders from NS
    (new \Search\Models\Algolia\Orders())->algoliaTotalSync();
});

$app->route('GET /remove-invalid-addresses', function() {
    foreach (\Shop\Models\CustomerAddresses::find([]) as $addressArray) {
        /** @var \Shop\Models\CustomerAddresses $address */
        $address = (new \Shop\Models\CustomerAddresses)->bind($addressArray);
        if (!$address->validation()->isValid()) {
            $address->remove();
        }
    }
});

$app->route('GET /testfailedemail', function() {

    $docs = \Mailer\Models\Emails::collection()->find(["sender_response" => 'failing']);

    foreach($docs as $queuedEmail) {
         $model = (new \Mailer\Models\Emails())->bind($queuedEmail);

                            $email = (new \Mailer\Email())->fromModel($model);
                            $mailer = \Mailer\Factory::instance()->sender()
                                ->bindEmail($email)
                                ->init();
                            $result = $mailer->sendEmail($email, false);

                            var_dump($result); die();
    }

});

$app->route('GET /test-email-deal', function() {
    $mailer = \Dsc\System::instance()->get('mailer');
        $mailer->send('chris@ammonitenetworks.com',  'This is a test email of the over riding - chris@ammonitenetworks.com', ' If you get this email forward it to chris french.');
});

$app->route('GET /invalid-kits', function() {
    $cursor = \Shop\Models\Products::collection()->find([
        'product_type' => 'dynamic_group',
        'publication.status' => [
            '$in' => ['published', 'discontinued']
        ]
    ]);

    $invalidKits = [];

    foreach ($cursor as $product) {
        foreach ((array) $product['kit_options'] as $option) {
            foreach((array) $option['values'] as $value) {
                $model = $value['model_number'];

                $subItem = (new \Shop\Models\Products)
                    ->setCondition('tracking.model_number', $model)
                    ->getItem();

                if (empty($subItem) || in_array($subItem->get('publication.status'), ['unpublished', 'discontinued']) || empty($subItem->get('netsuite.internalId'))) {
                    $kit = (new \Shop\Models\Products)->bind($product);
                    $kit->set('publication.status', 'unpublished');
                    $kit->store();

                    $invalidKits[] = $product['tracking']['model_number'];

                    break 2;
                }
            }
        }
    }

    if (!empty($invalidKits)) {
        /** @var \Mailer\Factory $mailer */
        $mailer = \Dsc\System::instance()->get('mailer');
        $mailer->send('christopher.west@rallysportdirect.com', count($invalidKits) . 'NETSUITE -  Invalid Kit(s)', implode("<br>", $invalidKits));
        $mailer->send('chris.french@rallysportdirect.com', count($invalidKits) . 'NETSUITE -  Invalid Kit(s)', implode("<br>", $invalidKits));

    }
});

$app->route('GET /bing',
    function() {

        (new \Shop\Services\BingProductsFeed)->generateFeeds();
    }
    );

$app->route('GET /cloud',
    function() {
        \Shop\Services\Cloudinary::getAllResourcesToDate();
    }
    );

$app->route('GET /clearcache',
		function() {
			apcu_clear_cache();
		}
);
$app->route('GET /rallyrecapfromyesterday', function() {
   (new \RallySport\Site\Controllers\Diagnostics)->sendRallyRecap();
});

$app->route('GET /sendemails', '\Mailer\Async->run');

$app->route('GET /github',
    function() {
       (new \GithubApp\Models\Issues)->closeallissues();
    }
    );

$app->route('GET /updatekitpricing',
    function() {
        //\Shop\Models\Products::updateKitParentPricing();
    }
);

$app->route('GET /remarketing',
    function() {
       \Marketing\Models\Emails\Remarketing\CustomerRemarket::buildRemarketReportDocuments();
    }
    );
$app->route('GET /remarketing/send',
    function() {
        \Marketing\Models\Emails\RemarketingSender::sendAllEmails();
    }
    );


$app->route('GET /testfeed',
    function() {
        $sitemap = new \Dsc\Sitemap;
         
        $domain = 'https:///www.rallysportdirect.com/';
        $xmldomain = 'http://static.rallysportdirect.com/';
        $routes = ['base' => []];
        $event =  \Dsc\System::instance()->trigger('siteMapRegisterRoutes', ['routes' => $routes]);
        
        $routes = $event->getArgument('routes');
         
        $sitemap->setDomain($domain);
        $sitemap->setPath('/var/www/static_rallysports/sitemaps/');
        $sitemap->setFilename('google');
         
        foreach ($routes as $app => $routes) {
            foreach($routes as $key => $route) {
                $sitemap->addItem($route['loc'], $route['pri'], $route['change'], $route['mod']);
            }
        }
        
        $sitemap->createSitemapIndex($xmldomain.'sitemaps/', 'Today');
    }
    );

$app->route('GET /buildymm',
    function() {
        $items = (new \Shop\Models\Products)->setCondition('ymms.0', ['$exits' => true])->getList();
        
        foreach ($items as $item) {
            $item->buildGeneralYmms();
        }
    }
    );


$app->route('GET /testrm',
    function() {
        $item = (new \RallyShop\Models\Returns)->setCondition('number', 'RM-RS1000689')->getItem();
        //$item->set('discount', 0.00);
        //$item->save();
        $item->queueXML();
    }
    );

$app->route('GET /sitemaptest',
    function() {
        $sitemap = new \Dsc\Sitemap;
         
        $domain = 'http:///www.rallysportdirect.com/';
        $xmldomain = 'http://static.rallysportdirect.com/';
        $routes = ['base' => []];
        $event =  \Dsc\System::instance()->trigger('siteMapRegisterRoutes', ['routes' => $routes]);
        
        $routes = $event->getArgument('routes');
         
        $sitemap->setDomain($domain);
        $sitemap->setPath('/var/www/static_rallysports/sitemaps/');
        $sitemap->setFilename('google');
         
        foreach ($routes as $app => $routes) {
            foreach($routes as $key => $route) {
                $sitemap->addItem($route['loc'], $route['pri'], $route['change'], $route['mod']);
            }
        }
        
        $sitemap->createSitemapIndex($xmldomain.'sitemaps/', 'Today');
        
        
        
    }
    );


$app->route('GET /xxxx',
    function() {
        $product = (new \Shop\Models\Products)
            ->setCondition('tracking.model_number', 'ABCD123')
            ->getItem();

        \Shop\Models\PriceChanges::addChange($product, 'd1_whole', 999.99);
    }
);

$app->route('GET /testordernumber',
		function() {
			$order = (new \RallyShop\Models\Orders);
			do {
				//waiting
			} while ($order->getValidOrderNumber() == false);
			var_dump($order);
		}
);

$app->route('GET /setymmpublished',
		function() {
			\RallyShop\Models\YearMakeModels::setPublished();
		}
);

$app->route('GET /stands',
		function() {
		
			 (new \RallyShop\Services\Strands)->generateFeeds();
		}
);

$app->route('GET /richrel',
		function() {

			(new \RallyShop\Services\RichRevelance)->generateFeeds()->compressFeeds(); 
		}
);

$app->route('GET /sendegiftcards',
		function() {
			\Shop\Models\OrderedGiftCards::sendEGiftCardNotifications();
		}
);

$app->route('GET /fixdynamickits', function() {
	$products = (new \Shop\Models\Products)->setCondition('product_type','dynamic_group')->getItems();

	foreach ($products as $product) {
	    $product->clear('prices.');
		$product->upgradeLegacyKitOptions();
	}
});

$app->route('GET /runtest',
		function() {

			$product = (new \Shop\Models\Products)->setCondition('slug', 'tbsfist-turbo-back-exhaust-system-13-fiesta-st')->getItem();

			$product->upgradeLegacyKitOptions();

		}
);


$app->route('GET /findcloudimages',
		function() {		
			do { 
				$doc = (new \RallyShop\Models\Products)->collection()->findAndModify(['publication.status' => 'published', 'images.0' => ['$exists' => false], 'cloudified' => ['$exists' => false]], [ '$set' => ['cloudified' => time()]]);
				$product = (new \RallyShop\Models\Products)->bind($doc);
				$result = $product->getImagesForProductFromCloudinary();
				$product->set('cloudified', time())->save();
				sleep(2);
			} while ((new \RallyShop\Models\Products)->collection()->count([ 'cloudified' => ['$exists' => false],  'publication.status' => 'published', 'images.0' => ['$exists' => false]]));
		}
);

$app->route('GET /groupimages', function() {
	$products = (new \RallyShop\Models\Products)->setCondition('group_items', ['$exists' => true])->getItems();

	foreach ($products as $product) {
		$product->getImagesForProductFromCloudinary();
	}
});

$app->route('GET /queueabandonedcartemails', '\RallyShop\Models\CartsAbandoned::queueEmailsForNewlyAbandonedCarts');

/*
 * loading matrix items and saving them so they run through the saving logic and disable discontinued variants or fix images etc. 
 */
$app->route('GET /save-matrix-items',
		function() {	
			$docs = (new \RallyShop\Models\Products)->collection()->find(['product_type' => 'matrix' ]);
			foreach ($docs as $doc) {	
			    try {
			        (new \RallyShop\Models\Products)->bind($doc)->save();
			    } catch (\Exception $e) {
			    }
			}
		}
);

$app->route('GET /usercontentupdate',
    function() {


        	
        $docs = (new \RallyShop\Models\UserContent)->collection()->find();
        	
        foreach ($docs as $doc) {
            $usercontent = (new \RallyShop\Models\UserContent)->bind($doc);
            $usercontent->updateProduct();
            $usercontent->updateUser();
        }
    }
);


$app->route('GET /wishlistreport',
    function() {


    //   db['shop.wishlists'].aggregate([{ $unwind : "$items" }, { $group: { _id : "$items.model_number", total: { $sum: 1 } } }, {$match : { total : { $gt : 1} } }, {$sort: {total : -1}} ])


        $c = \RallyShop\Models\Wishlists::collection();
        
  
        $pipeline = array(
            array(
                '$unwind' => '$items'
            ),
            array(
                '$group' => array(
                    '_id' => '$items.model_number',
                    'total' => array('$sum' => 1 )
                )
            ),
            array(
                '$match' => array(
                    'total' => array('$gt' => 1)
                )
            ), 
            array(
                '$sort' => array(
                    'total' => -1
                )
            ),
        );
        
        $out = $c->aggregate($pipeline);
        
        
        
        foreach ($out as $array) {
            
        }
    


    }
    );




$app->route('GET /shrink',
		function() {
			$collection = (new \RallyShop\Models\Orders)->collection();
			$docs = $collection->find(['status' => 'closed']);
			foreach ($docs as $doc) {
				$order = (new \RallyShop\Models\Orders)->setCondition('_id', $doc['_id'])->getItem();
				$order->makeHistorical();
			}
		}
);

$app->route('GET /product',
		function() {


			$product = (new \RallyShop\Models\Products)->getItem();
				
			$cast = $product->castForOrder();
			var_dump($cast);
			die();			
		}
);


$app->route('GET /shippedemail',
		function() {
				
			do {
					
				
				$doc = (new \RallyShop\Models\Orders)->collection()->findOne(['tracking_numbers.0' => ['$exists' => true]]);
					

				$order = (new \RallyShop\Models\Orders)->bind($doc);
				die();
				$result = $product->getImagesForProductFromCloudinary();

				sleep(2);

			} while ((new \RallyShop\Models\Products)->collection()->count(['publication.status' => 'published', 'images' => ['$exists' => false]]));
				
		}
);


$app->route('GET /testcloudly',
		function() {
			
			do {
							
				$brands =  \Dsc\Mongo\Helper::getCollection( 'imageuploads' )->distinct('brand', []);
				
				
				$doc = (new \RallyShop\Models\Products)->collection()->findAndModify(['cloudified' => ['$exists' => false], 'publication.status' => ['$in' => ['published', 'discontinued']], 'manufacturer.slug' => ['$in' => $brands], 'images.1' => ['$exists' => false]],  [ '$set' => ['cloudified' => time()]]);
			
	
				$product = (new \RallyShop\Models\Products)->bind($doc);
				$result = $product->getImagesForProductFromCloudinary();			
				
				sleep(2);
				
			} while ((new \RallyShop\Models\Products)->collection()->count(['publication.status' => 'published', 'images' => ['$exists' => false]]));
			
		}
);

$app->route('GET /categoriesimages',
    function() {
        $cats = (new \RallyShop\Models\Categories)->collection()->find();
		
		foreach($cats as $doc) {
			\RallyShop\Models\Categories::findImageFromProduct($doc['_id']);
		}
	   
    }
);

$app->route('GET /userdatacleanup',
		function() {
			$docs = (new \Dsc\Mongo\Collections\Assets)->collection()->find(['title' => ['$regex'=> 'user submitted']]);
			foreach($docs as $doc) {
				(new \Dsc\Mongo\Collections\Assets)->bind($doc)->deleteDocument();
			}
		}
);

$app->route('GET /updatespecs',
		function() {
			$docs = (new \RallyShop\Models\Products)->collection()->find();
			$specs = \Dsc\Mongo\Helper::getCollection( 'product.specs' );
			foreach($docs as $doc) {
				$new_specs = $specs->findOne(['part_number' => $doc['tracking']['model_number']]);
				$specsPair = preg_split("/[\r\n]+/", $new_specs['specs_attributes_new']);
				$specsPair = array_filter($specsPair);
				
				$newSpecs = [];
				foreach($specsPair as $line) {
					$key_value = preg_split("/[\t]+/", $line);
					
					
					if(strpos( $key_value[0], '.')) {
						var_dump($key_value[0]);
						die('key has a dot');
					}
					$newSpecs[$key_value[0]] = $key_value[1];
				}
				
				$productModel = (new \RallyShop\Models\Products)->bind($doc);
				$productModel->set('specs', $newSpecs);
				$productModel->save();
				
				
			}
		}
);

$app->route('GET /testinventory',
		function() {
			
		 \RallyShop\Models\Importers\Inventory::updateInventoryForProduct('RSD 33906', 9, false);
		}	
	
);

$app->route('GET /resetorders',
		function() {
			$Orders = ['RSD766320','RSD766852','RSD767332','RSD766351','RSD767349','RSD767367','RSD767575','RSD764929',
					'RSD766904','RSD767700','RSD767813','RSD767915','RSD767986','RSD768016','RSD768140','RSD768172',
					'RSD768221','RSD768192','RSD768221','RSD768257','RSD768292'];
			
			$specs = \Dsc\Mongo\Helper::getCollection( 'orders.backup' );
			foreach($Orders as $order) {
				$new_order = $specs->findOne(['order_id' => $order]);
			
			
				if(!empty($new_order)) {
					$new_order['attempts'] = 0;
					
					$specs->update(['_id' => $new_order['_id']], $new_order);
				}


			}
		}
);


$app->route('GET /movetoCDN',
		function() {
			$docs = (new \Dsc\Mongo\Collections\Assets)->collection()->find(['storage' => 'gridfs']);
			foreach($docs as $doc) {
				try {
					\Assets\Models\Storage\CloudFiles::gridfsToCDN((string) $doc['slug']);
				} catch (\Exception $e) {
					echo $e->getMessage();
				}
				
			}
		}
);

$app->route('GET /deleteFromCDN',
		function() {
			$docs = (new \Dsc\Mongo\Collections\Assets)->collection()->find(['storage' => 'gridfs']);
			foreach($docs as $doc) {
				try {
					\Assets\Models\Storage\CloudFiles::gridfsToCDN((string) $doc['slug']);
				} catch (\Exception $e) {
					echo $e->getMessage();
				}

			}
		}
);


$app->route('GET /productsupdatereviews',
		function() {
			$docs = (new \RallyShop\Models\Products)->collection()->find(['publication.status' => 'published']);
			foreach($docs as $doc) {
				try {
					(new \RallyShop\Models\Products)->bind($doc)->updateReviewCounts();
					
				} catch (\Exception $e) {
					echo $e->getMessage();
				}

			}
		}
);

$app->route('GET /movecats',
		function() {
			$items = (new \RallyShop\Models\Products)->setCondition('categories.slug', 'interior')->getItems();
			
			foreach($items as $doc) {
				
				$doc->set('category_ids', array('55118e738149814d0a8b456a'));
				$doc->save();
			}
		}
);


$app->route('GET /testemail',
		function() {
			
			$user = (new \Users\Models\Users)->setCondition('email', 'chris.french@rallysportdirect.com')->getItem();
	
			$order = (new \Shop\Models\Orders)->getItem();
			
			$mailer = \Dsc\System::instance()->get('mailer');
			
			if ($content = $mailer->getEmailContents('shop.new_order', array(
					'order' => $order
			))) {
			 $mailer->send( $user->email, $content['subject'], $content['body'], $content['fromEmail'], $content['fromName'] );
			}
			
			
		}
);

$app->route('GET /email-quote-creation', function() {
    $quotes = (new \Shop\Models\Quotes)
        ->setCondition('emails.quote_creation', ['$exists' => false])
        ->getItems();

    /** @var \Shop\Models\Quotes $quote */
    foreach ($quotes as $quote) {
        $quote->sendCustomerEmail();
    }
});

$app->route('GET /email-quote-followup', function() {
    /** @var \Mailer\Factory $mailer */
    $mailer = \Dsc\System::instance()->get('mailer');

    $quotes = (new \Shop\Models\Quotes)
        ->setCondition('followup_sent', ['$ne' => true])
        ->setCondition('metadata.created.time', ['$lte' => strtotime('-7 days')])
        ->getItems();

    foreach ($quotes as $quote) {
        if ($content = $mailer->getEmailContents('customerservice.quote_followup', ['quote' => $quote])) {
            $content['subject']  .= $quote->number;
            $content['fromEmail'] = $quote['user_email'];
            $content['fromName']  = !empty($quote['contact_name']) ? $quote['contact_name'] : 'Guest';

            $mailer->sendEvent('customerservice@rallysportdirect.com', $content);
        }

        $quote->set('followup_sent', true);
        $quote->store();
    }
});

$app->route('GET /email-invoice-followup', function() {
    (new \RallyShop\Admin\Controllers\Followups())
        ->sendInvoiceFollowups();
});

$app->route('GET /fixdims', function() {
	$packages = (new RallyShop\Models\Packages())->getItems();

	foreach($packages as $package) {
		if (preg_match('/(\d+)In.*?(\d+)In.*?(\d+)In/', $package->title, $matches)) {
			$package->set('imperial', [
				'length' => $matches[1],
				'width'  => $matches[2],
				'height' => $matches[3]
			]);

			$package->save();
		}
	}
});

$app->route('GET /list-ltl', function() {
    /** @var \MongoDb $mongo */
    $mongo = \Dsc\System::instance()->get('mongo');

    /** @var \MongoCollection $collection */
    $collection = \Dsc\Mongo\Helper::getCollection('shop.products');

    $products = $collection->find([
        'publication.status' => 'published',
        'product_type' => ['$nin' => [
            'dynamic_group',
            'matrix'
        ]]
    ]);

    $partNumbers = [];

    foreach ($products as $doc) {
        /** @var \Shop\Models\Products $product */
        $product = (new \Shop\Models\Products)->bind($doc);

        if ($product->shipsLtl()) {
            $partNumbers[] = $product->{'tracking.model_number'};
        }
    }

    print_r($partNumbers);
});



$app->route('GET /testinventoryupdate',
		function() {
			\RallyShop\Models\Importers\Inventory::updateInventoryForProduct('BEA A91012W-Q40', 0);
			\RallyShop\Models\Importers\Inventory::updateInventoryForProduct('BEA A91012W-Q40', 5);
		}
);
$app->route('GET /testfieldsupdate',
		function() {
			try {
				$singleton = (new \Dsc\Singleton);
					
				$queue_task = (new \Dsc\Mongo\Collections\QueueTasks)->setCondition('_id',new \MongoDB\BSON\ObjectID('5581d2fea0313c334c8b4567'))->getItem();
				
				$result = $singleton->app->call('\RallyShop\Models\Importers\Inventory::updateFieldsForProduct', $queue_task['parameters'] );
				

			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}
);

$app->route('GET /rebuild-carts', function() {
	$carts = (new \RallyShop\Models\Carts)->collection()->find([]);
	foreach ($carts as $cartArray) {
		/** @var \RallyShop\Models\Carts $cart */
		$cart = (new \RallyShop\Models\Carts)->bind($cartArray);
		$cart->rebuildCartItems();
	}
});



$app->route('GET /testimport',
		function() {
			try {
				

				\RallyShop\Models\Importers\Customers::sync($data);
		
			} catch (Exception $e) {
				echo $e->getMessage();
			}

		}
);

$app->route('GET /createymmtext', function() {
	$products = (new \RallyShop\Models\Products)->collection()->find(['publication.status' => 'published', 'ymms' => ['$exists' => true, '$not' => ['$size' => 0]]]);

	$total = $products->count();

	echo $total . " products found.\n";
	foreach ($products as $product) {
		$productDoc = (new \RallyShop\Models\Products)->bind($product);
		$productDoc->__rebuild_ymms = true;


		$productDoc->save();
	}

	echo "Complete!\n";
});

$app->route('GET /update-collection-products', function() {
    \RallyShop\Models\Collections::updateNewItems();

    $collections = \Shop\Models\Collections::collection()->find([], ['projection' => ['_id' => 1]]);
    foreach ($collections as $collection) {
        \Shop\Models\Collections::updateActiveProducts((string) $collection['_id']);
    }
});

$app->route('GET /flattenmodels', function() {
	$products = (new \RallyShop\Models\Products)->collection()->find();

	$total = $products->count();

	echo $total . " products found.\n";
	foreach ($products as $product) {
		$productDoc = (new \RallyShop\Models\Products)->bind($product);
		$productDoc->set('tracking.model_number_flat', strtoupper(preg_replace("/[^A-Za-z0-9]/", '', $productDoc->{'tracking.model_number'})));
		$productDoc->store();
	}
	echo "Complete!\n";
});

$app->route('GET /processmarketingemails', function() {
	(new \Marketing\Admin\Controllers\Emails())->process();
});

$app->route('GET /generate_google_product_feeds', function() {
	(new \RallySport\Site\Controllers\GoogleProductFeed())->generateFeeds();
});




	$app->route('GET /resaveallproducts', function() {
		$products = (new \RallyShop\Models\Products)->collection()->find([
			'publication.status' => 'published',
		    'ymms.0' => ['$exists' => true]
		]);
	
		foreach ($products as $product) {
			try {
				$product = (new \Shop\Models\Products)->bind($product)->createYmmText()->save();
				echo $product->url(). "\n"; 
			} catch (\Exception $e) {

			}
		}
	});

	/**
	 * This will send off the wishlist price drop emails when ran
	 * it is scheduled via a cron job that runs based off the items in a colleciton in mongo that has a expireAt index. 
	 */
	$app->route('GET /marketing/wishlistpricedropemails',
	        function() {
	             
	            $wishlists =  \Marketing\Models\Emails\WishlistPriceDrop::fetchWishListsDroppedPrices();
	    
	            if(!empty($wishlists)) {
	                foreach($wishlists as $wishlist) {
	                    \Marketing\Models\Emails\WishlistPriceDrop::queueEmailToCustomer($wishlist);
	                     
	                }
	            }
	             
	            exit;

	        }
    );

$app->route('GET /listtrac-orders',
    function() {
        (new \Shop\Services\Listtrac\DataFeeds('./', 'www.rallysportdirect.com', 'ftp.listrakbi.com', 'FAUser_RallySportDir', 'XyM5hhvc6mxF'))->GenerateFeeds();
    }
);

$app->route('GET /sync-algolia/@type/@channel', function ($f3) {
    $type = strtolower($f3->get('PARAMS.type'));
    $channel = strtolower($f3->get('PARAMS.channel'));
    switch ($type) {
        case 'products':
            \Shop\Models\SalesChannels::setSalesChannel($channel);
            (new \Search\Models\Algolia\Products())->algoliaStandardSync();
            break;
        case 'orders':
            \Shop\Models\SalesChannels::setSalesChannel($channel);
            (new \Search\Models\Algolia\Orders())->algoliaStandardSync();
            break;
    }
});

$app->route('GET /sync-algolia-full/@type/@channel', function ($f3) {
    $type = strtolower($f3->get('PARAMS.type'));
    $channel = strtolower($f3->get('PARAMS.channel'));
    switch ($type) {
        case 'products':
            \Shop\Models\SalesChannels::setSalesChannel($channel);
            (new \Search\Models\Algolia\Products())->algoliaTotalSync();
            break;
        case 'orders':
            \Shop\Models\SalesChannels::setSalesChannel($channel);
            (new \Search\Models\Algolia\Orders())->algoliaTotalSync();
            break;
    }
});

$app->route('GET /expired-new-flags', function(){
    \Search\Models\Algolia\Products::queuePartialUpdate();
});

$app->route('GET /save-all-cats-handy-somewhere', function(){
    $cursor = (new \Shop\Models\Categories)->collection()->find();
    $climate = new \League\CLImate\CLImate();
    $count = (new \Shop\Models\Categories)->collection()->count();
    $progress = $climate->progress()->total($count);
    forEach($cursor as $doc){
        $category = new \Shop\Models\Categories($doc);
        $category->save();
        $progress->advance(1, $category->get('title'));
    }
    $climate->green('Done');

});

$app->route('GET /ltl-averages', function() {
    $shiphawk = new \Shop\Services\ShipHawk;
    
    $items = [
        'SEISS0305INFG354D-TW' => 155,
        'SEIFL0003TYMRS-OE'    => 155,
        'SEIHD9200LXSC-OE'     => 155,
        'SEIHD0305HYTB-VSII'   => 155,
        'SEIDD0110LXSC-DRY'    => 185
    ];
    
    $postalCodes = [
        "36104", "99801", "85001", "72201", "95814", "80202", "06103", "19901", "32301", "30303", "96813", "83702",
        "62701", "46225", "50309", "66603", "40601", "70802", "04330", "21401", "02201", "48933", "55102", "39205",
        "65101", "59623", "68502", "89701", "03301", "08608", "87501", "12207", "27601", "58501", "43215", "73102",
        "97301", "17101", "02903", "29217", "57501", "37219", "78701", "84111", "05602", "23219", "98507", "25301",
        "53703", "82001", "96799", "20001", "96941", "96910", "96960", "96950", "96939", "00901", "00802"
    ];
    
    $averages = [];
    foreach ($items as $model => $flatRate) {
        $product = (new \Shop\Models\Products)
            ->setCondition('tracking.model_number', $model)
            ->getItem();
            
        if (empty($product)) {
            continue;
        }
        
        $cart = (new \Shop\Models\Carts)
            ->addItem($product, [], false);
            
        $cart->set('checkout.shipping_address.country', 'US');
            
        $allRates = [];
        foreach ($postalCodes as $postalCode) {
            $cart->set('checkout.shipping_address.postal_code', $postalCode);
            try {
                $rates = $shiphawk->rates($cart);
                if (empty($rates->rates[0]->price)) {
                    continue;
                }
                
                $allRates[$postalCode] = $rates->rates[0]->price;
            } catch (\Exception $e) {
                
            }
        }
        
        $averages[$model] = [
            'average' => array_sum($allRates) / count($allRates),
            'flat'    => $flatRate
        ];
    }
    
    var_dump($averages); die;
});

$app->route('GET /testkount',
    function() {

        \NetSuite\Models\Orders::doKountAuthCheck(2492);
    }
);

$app->route('GET /testapi',
    function() {
        $service = (new \Shop\Services\Listtrac\Listrak);
        $stuff =  $service->init()->checkContact('chris@ammonitenetworks.com');
        var_dump($stuff);
        die;
    }
);



// bootstap each mini-app  these are in apps folder, as well as in vender/dioscouri
\Dsc\Apps::instance()->bootstrap();

// load routes; Routes are defined by their own apps in the Routes.php files
\Dsc\System::instance()->get('router')->registerRoutes();

// trigger the preflight event PreSite, PostSite etc
\Dsc\System::instance()->preflight();



//excute everything.
$app->run();
