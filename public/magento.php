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

$CLImate = new \League\CLImate\CLImate();

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

$app->route('GET /sync-dynamic-group-products', function() {
	(new JBAShop\Services\Magento)->syncDynamicGroupProducts();
});
$app->route('GET /sync-product-images', function() {
	$input = $CLImate->confirm('Have you cleared the product_images folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->syncProductImages();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
});

$app->route('GET /sync-category-images', function() {
	$input = $CLImate->confirm('Have you cleared the category_images folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->syncCategoryImages();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
});

$app->route('GET /sync-usercontent-images', function() use ($CLImate) {
	$input = $CLImate->confirm('Have you cleared the user_content folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->syncUserContentImages();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
});

$app->route('GET /move-product-description-images', function() use ($CLImate) {
	$input = $CLImate->confirm('Have you cleared the content folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->moveProductDescriptionImages();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
});

$app->route('GET /move-category-description-images', function() use ($CLImate) {
	$input = $CLImate->confirm('Have you cleared the content folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->moveCategoryDescriptionImages();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
});

$app->route('GET /sync-ymms', function() {
	(new JBAShop\Services\Magento)->syncYmmsFromRally();
});

/**
 * This method syncs all users from magento to mongo
 * @param int $minutes - Optionally pass amount of minuets, to only sync users created within that timeframe
 * @return void
 */
$app->route(['GET /sync-magento-users-to-mongo', 'GET /sync-magento-users-to-mongo/@minutes'], function($f3, $params){
	$mins = $params['minutes'] ?? 0;
	(new JBAShop\Services\Magento)->syncMagentoUsersToMongo((int) $mins);
});

/**
 * This method syncs all product ratings to shop.usercontent
 */
$app->route('GET /sync-product-ratings', function($f3){
	(new JBAShop\Services\Magento)->syncProductRatings();
});

/**
 * This method builds and syncs matrix items
 */
$app->route('GET /sync-matrix-items', function($f3) use($CLImate){
	$input = $CLImate->confirm('Have you cleared the swatches folder in Cloudinary?');
	if ($input->confirmed()) {
		(new JBAShop\Services\Magento)->syncMatrixItems();
	} else {
		$CLImate->error('Well, what are you waiting for?!');
	}
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
