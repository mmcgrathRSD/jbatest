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


/*** MAGENTO SYNC ROUTES ***/

$app->route('GET /sync-brands', function() {
    (new JBAShop\Services\Magento)->syncBrands();
});

$app->route('GET /sync-categories', function() {
    (new JBAShop\Services\Magento)->syncCategories();
});

$app->route('GET /sync-product-info', function() {
    (new JBAShop\Services\Magento)->syncProductInfo();
});

/**************************/


// bootstap each mini-app  these are in apps folder, as well as in vender/dioscouri
\Dsc\Apps::instance()->bootstrap();

// load routes; Routes are defined by their own apps in the Routes.php files
\Dsc\System::instance()->get('router')->registerRoutes();

// trigger the preflight event PreSite, PostSite etc
\Dsc\System::instance()->preflight();



//excute everything.
$app->run();
