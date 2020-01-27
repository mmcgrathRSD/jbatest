<?php
define('PATH_ROOT', realpath( __dir__ . '/../' ) . '/' );

//AUTOLOAD all your composer libraries now.
(@include_once (__dir__ . '/../vendor/autoload.php')) OR die("You need to run php composer.phar install for your application to run.");
//Require FatFree Base Library https://github.com/bcosca/fatfree
$app = Base::instance();

$app->set('PATH_ROOT', PATH_ROOT);
$app->set('AUTOLOAD', $app->get('PATH_ROOT') . 'apps/;');

$app->set('APP_NAME', 'cli');
$app->set('ONERROR',function($app){

$html = 'ERROR: '. $app->get('ERROR.text') . '| ' . $app->get('ERROR.status');

        $html .= '<br> <hr>';

           $trace=debug_backtrace(FALSE);
		        
        $html .= \Dsc\Debug::dump($trace);

        $emailSent = \Dsc\System::instance()->get('mailer')->send('christopher.west@rallysportdirect.com', 'QUEUE PROCESS ERROR' . $app->get('ERROR.code'), array($html) );

});
require $app->get('PATH_ROOT') . 'config/config.php';

// bootstap each mini-app
\Dsc\Apps::instance()->bootstrap();


// process the queue!
\Dsc\Queue::process('syncs');
