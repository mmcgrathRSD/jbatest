<?php
$app->config($app->get('PATH_ROOT') . 'config/common.config.ini');

$app->set('lang', 'en');
$app->set('dns', 'apc');

$app->set('SiteVersion', 7);
$app->set ('cachebuster', rand());

// Rich Relevance
$app->set('RichRelVersion', $app->get('rich_relevance.version'));
$app->set('RichRelApi', $app->get('rich_relevance.api_key'));
if ($app->get('DEBUG')) {
    $app->set('RichRelLocation', "window.location.protocol+'//integration.richrelevance.com/rrserver/'");
} else {
    $app->set('RichRelLocation', "window.location.protocol+'//recs.richrelevance.com/rrserver/'");
}

$app->set('og.url', \Dsc\Url::full());

$app->set('LOGS', realpath ($app->get('PATH_ROOT') . 'logs/') . '/');
$app->set('TEMP', realpath ($app->get('PATH_ROOT') . 'tmp/') . '/');
$app->set('db.jig.dir', realpath($app->get('PATH_ROOT') . 'jig/') . '/');

if ($app->get('DEBUG')) {
    ini_set('display_errors', 1);
    if (!$app->get('CACHE')) {
        \Cache::instance()->reset();
    }
    $whoops = new \Whoops\Run;
    if ($app->get('APP_NAME') == 'cli') {
        $whoops->pushHandler(new \Whoops\Handler\PlainTextHandler);
    } else {
        $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    }
    
    $whoops->register();
} else {
    error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
}

\Cloudinary::config(array(
    "cloud_name" => $app->get('cloudinary.cloud_name'),
    "api_key"    => $app->get('cloudinary.api_key'),
    "api_secret" => $app->get('cloudinary.api_secret'),
    "private_cdn" => filter_var($app->get('cloudinary.private_cdn'), FILTER_VALIDATE_BOOLEAN),
    "cname" => $app->get('cloudinary.cname'),
    "secure_distribution" => $app->get('cloudinary.secure_distribution'),
    'cdn_subdomain' => filter_var($app->get('cloudinary.cdn_subdomain'), FILTER_VALIDATE_BOOLEAN),
    'secure' => true
));

\Braintree_Configuration::environment($app->get('braintree.environment'));
\Braintree_Configuration::merchantId($app->get('braintree.merchant_id'));
\Braintree_Configuration::publicKey($app->get('braintree.public_key'));
\Braintree_Configuration::privateKey($app->get('braintree.private_key'));

if ($frontCounterIp = $app->get('shop.front_counter_ip')) {
    $server = $_SERVER;
    $ip = empty($_SERVER['REMOTE_ADDR']) ? '0.0.0.0' : $_SERVER['REMOTE_ADDR'];
    if ((isset($server['HTTP_X_FORWARDED_FOR']) && $server['HTTP_X_FORWARDED_FOR'] == $frontCounterIp) || $ip == $frontCounterIp) {
        $app->set('isFrontCounter', true);
    }
}

$app->set('disable_order_tracking', filter_var($app->get('disable_order_tracking'), FILTER_VALIDATE_BOOLEAN));
$app->set('shop.free_ltl', filter_var($app->get('shop.free_ltl'), FILTER_VALIDATE_BOOLEAN));

if (extension_loaded('newrelic') && !empty($app->get('new_relic.app_name'))) {
    newrelic_set_appname($app->get('new_relic.app_name'));
}

if (!empty($app->get('SITE_TYPE')) && $app->get('SITE_TYPE') == 'wholesale') {
    require $app->get('PATH_ROOT') . 'config/dealer_portal.php';
}

if (empty($app->get('netsuite.default_price_level')) || empty($app->get('netsuite.wholesale_price_levels'))) {
    throw new \Exception('Prices are misconfigured.');
}

$wholesalePriceLevels = array_map('trim', explode(',', $app->get('netsuite.wholesale_price_levels')));
$app->set('netsuite.wholesale_price_levels', $wholesalePriceLevels);

$checkoutExpiration = (int) $app->get('shop.checkout_expiration');
if (empty($checkoutExpiration)) {
    $app->set('shop.checkout_expiration', 1440); // 24 hours
}
