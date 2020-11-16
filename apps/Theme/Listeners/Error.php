<?php
namespace Theme\Listeners;

class Error extends \Dsc\Singleton
{
    public function onError( $event )
    {
        $f3 = \Base::instance();
        //fixing google base links
        if(strpos($f3->hive()['PATH'],'&source=GoogleBase') ) {
        	$path = $f3->hive()['PATH'];
        	$path = str_replace('&source=GoogleBase', '?source=GoogleBase',$path);
        	\Base::instance()->reroute($path);
        }




      if($f3->get('DEBUG') == 0) {

        if ($f3->get('ERROR.code') == '404')
        {

        	// reroute old ymm/category links
        	$parsedUrl = parse_url(\Dsc\Url::full());
			$path = rtrim($parsedUrl['path'], '/');

			$redirect = (new \Redirect\Admin\Models\Routes)
				->setCondition('old_slug', substr($path, strrpos($path, '/') + 1))
				->getItem();

			if (!empty($redirect)) {
				$product = (new \JBAShop\Models\Products)
					->setCondition('_id', $redirect->product_id)
					->getItem();

				if (!empty($product)) {
					$this->app->reroute($product->url());
				}
			}

			
            if ($f3->get('APP_NAME') == 'site')
            {
				$response = $event->getArgument('response');

				/*
				 * IF this is a bot and this page has 404'd we are going to submit it as 410 gone.  This is not something to have one shortly after launch but goog after
				 */
				if(\Audit::instance()->isbot() && $response->action !== 'redirect') {
					$f3->error('410');
				}

                if(!empty($response->action)) {
                	return;
                }

                $html = \Dsc\System::instance()->get('theme')->render('Theme\Views::404.php');

                $response->action = 'html';
                $response->html = $html;

                $event->setArgument('response', $response);
            }

        } else {

        	//all other errors
        	if ($f3->get('APP_NAME') == 'site')
        	{

        		$html = 'ERROR: '. $f3->get('ERROR.text') . '| ' . $f3->get('ERROR.status');
        		$html .= '<br> <hr>';

        		$response = $event->getArgument('response');

        		if(!empty($response->action)) {
        			return;
        		}

        		$html = \Dsc\System::instance()->get('theme')->render('Theme\Views::Error.php');

        		$response->action = 'html';
        		$response->html = $html;


        		$event->setArgument('response', $response);
        	}



        }
       }
    }
}
