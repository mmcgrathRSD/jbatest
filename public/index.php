<?php
header('Access-Control-Allow-Origin: *');

ini_set('memory_limit', '512M');

if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
$_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}
//AUTOLOAD all your composer libraries now.
(include_once (__dir__ . '/../vendor/autoload.php')) OR die("You need to run php composer.phar install for your application to run.");
$time_start = microtime(true);
//Require FatFree Base Library https://github.com/bcosca/fatfree
$app = Base::instance();
/*
 * LETS TRY TO FIX SOME URLS BEFORE THEY 404
 */
$path = $app->hive()['PATH'];

if (!empty($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'COOK')  {
	exit;
}

$reroute = false;

// cleanup urlencoded param keys
$path = preg_replace_callback('/(%\d+)([a-zA-Z_]+=)/', function($matches) {
	$reroute = true; 
	return urldecode($matches[0]);
}, $path);

//if the PATH contains a & without a ? replace the first & with a ? 
if (strpos($path,'&') !== false) {
	if (strpos($path,'?') == false) {
		$reroute = true;
		//replace the first & with ? 
		$path =  preg_replace('/&/', '?', $path, 1); // outputs '123def abcdef abcdef'
		$app->reroute($path);
	}
}

if ($reroute) {
	$app->reroute($path);
}

//Set the PATH so we can use it in our apps
$app->set('PATH_ROOT', __dir__ . '/../');

//This autoload loads everything in apps/* and 
$app->set('AUTOLOAD',  $app->get('PATH_ROOT') . 'apps/;');

$app->set('APP_NAME', 'site');

//load the config files for environment
require $app->get('PATH_ROOT') . 'config/config.php';
//SET the "app_name" or basically the instance so we can server the admin or site from same url with different loaded classes

if (strpos(strtolower($app->get('URI')), $app->get('BASE') . '/admin') !== false)
{
    $app->set('APP_NAME', 'admin');
    //stupid javascript bugs with debug off
    $app->set('DEBUG', 1);
}


// bootstap each mini-app  these are in apps folder, as well as in vender/dioscouri
\Dsc\Apps::instance()->bootstrap();

// load routes; Routes are defined by their own apps in the Routes.php files
\Dsc\System::instance()->get('router')->registerRoutes();

/*
 * Had to move this to before the Flight, because it can effect other apps that are loaded before shops
 */
if(!empty(\Base::instance()->get('sales_channel')) && class_exists('\Shop\Models\SalesChannels') && $app->get('APP_NAME') != 'admin') {
    \Shop\Models\SalesChannels::setSalesChannel(\Base::instance()->get('sales_channel'));
}

// trigger the preflight event PreSite, PostSite etc
\Dsc\System::instance()->preflight();


$app->route('GET /sitemap.xml',
		function() {
			\Base::instance()->reroute(sprintf('http://static.%s.com/sitemaps/google-index.xml', \Base::instance()->get('sales_channel')));
		}
);

$app->route('GET /affiliate/fp/@id', function() {});

$app->route('GET /ping',
		function() {
			echo 'test'; 
		}
);

$app->route('GET /test-order',
		function() {
		    \Search\Models\Algolia\BoomiOrders::syncOrder(3945);
		    
	//	$order = 	\Search\Models\Algolia\BoomiOrders::syncOrder(3945);
	//	var_dump($order->getReturnableItems());
	//	die();
		}
);

$app->route('GET /release-waiting-order/@externalid', function($f3) { 
     $db = \Netsuite\Models\BaseSQL::getDB();
     $externalId = strtolower($f3->get('PARAMS.externalid'));
     $orders =   $db->exec("select * from  [stageSalesOrderHeader]  where [externalId]= {$externalId}");
        foreach($orders as $order) {
            $externalId = $order['externalId'];
            $db->exec("update [stageSalesOrderHeader] set [syncFlag]='0', [syncStatusCode]='PEND' where [externalId]='{$externalId}'");
            $db->exec("update [stageSalesOrderLine] set [syncFlag]='0', [syncStatusCode]='PEND' where [headerExternalId]='{$externalId}'");
            $db->exec("update [eGiftCardPayments] set [syncFlag]='0', [syncStatusCode]='PEND' where [salesOrderExternalId]='{$externalId}'");
            $db->exec("update [replSalesOrderPromotion] set [syncFlag]='0', [syncStatusCode]='PEND' where [salesOrderExternalId]='{$externalId}'");
        }
});

$app->route('GET /lock-order/@externalid', function($f3) { 
     $db = \Netsuite\Models\BaseSQL::getDB();
     $externalId = strtolower($f3->get('PARAMS.externalid'));
     $orders =   $db->exec("select * from  [stageSalesOrderHeader]  where [externalId]= {$externalId}");
        foreach($orders as $order) {
            $externalId = $order['externalId'];
            $db->exec("update [stageSalesOrderHeader] set [syncFlag]='1', [syncStatusCode]='WAIT' where [externalId]='{$externalId}'");
            $db->exec("update [stageSalesOrderLine] set [syncFlag]='1', [syncStatusCode]='WAIT' where [headerExternalId]='{$externalId}'");
            $db->exec("update [eGiftCardPayments] set [syncFlag]='1', [syncStatusCode]='WAIT' where [salesOrderExternalId]='{$externalId}'");
            $db->exec("update [replSalesOrderPromotion] set [syncFlag]='1', [syncStatusCode]='WAIT' where [salesOrderExternalId]='{$externalId}'");
        }
});

$app->run();
