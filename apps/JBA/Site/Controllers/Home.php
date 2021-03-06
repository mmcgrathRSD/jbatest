<?php
namespace JBA\Site\Controllers;

class Home extends \Dsc\Controller
{
    public function index()
    {
        $this->registerName(__METHOD__);

    	if(!empty($_SERVER['HTTP_REFERER'])) {
    		$parse = parse_url($_SERVER['HTTP_REFERER']);
    		if(!empty($parse['path']) && strlen($parse['path']) && !empty($parse['host']) && $parse['host'] == 'www.rallysportdirect.com') {
    			//LETS CHECK IF WE HAVE A REDIRECT
    			$model = new \Redirect\Admin\Models\Routes();
    			$route = $model->setState('filter.url.alias', $parse['path'])->getItem();

    			if (!empty($route->id) && !empty(trim($route->{'url.redirect'})))
    			{
    				// count the number of times a redirect is hit and track the date of the last hit
    				$route->hits = (int) $route->hits + 1;
    				$route->last_hit = \Dsc\Mongo\Metastamp::getDate('now');
    				$route->store();

    				$redirect = trim($route->{'url.redirect'});
    				\Base::instance()->reroute($redirect);
    			}
    		}
    	}

        $this->app->set('page', 'home');
        $this->app->set('isHome', true);
        $this->app->set('canonical', \Base::instance()->get('SCHEME') . "://" . \Base::instance()->get('HOST'));

        $salesChannel = \Shop\Models\SalesChannels::getSalesChannel();
        
        //Setting sales channel data for home page in case it is needed
        $this->app->set('salesChannel', $salesChannel );
        
        //Setting home page medata data overrides from admin config if available
        $this->app->set('metaDataOverride', [
            "h1" => !empty($salesChannel['seo']['homepage']['h1']) ? $salesChannel['seo']['homepage']['h1'] : null,
            "title" => !empty($salesChannel['seo']['homepage']['title']) ? $salesChannel['seo']['homepage']['title'] : null,
            "canonical" => !empty($salesChannel['seo']['homepage']['canonical']) ? $salesChannel['seo']['homepage']['canonical'] : null,
            "description" => !empty($salesChannel['seo']['homepage']['description']) ? $salesChannel['seo']['homepage']['description'] : null,
            "topheadercopy" => !empty($salesChannel['seo']['homepage']['topheadercopy']) ? $salesChannel['seo']['homepage']['topheadercopy'] : null,
            "keywords" => !empty($salesChannel['seo']['homepage']['keywords']) ? $salesChannel['seo']['homepage']['keywords'] : null
        ]);
        
        if (!filter_var(\Base::instance()->get('disable_order_tracking', true), FILTER_VALIDATE_BOOLEAN) && empty($dataLayer))
		{
            $identity = $this->auth->getIdentity();
            $dataLayer = \Users\Models\Users::getDataLayer($identity);

			$this->app->set('gtm.dataLayer', $dataLayer);
			$this->app->set('gtm.event', ['ecommerce' => ['currencyCode' => 'USD']]);
		}
        echo $this->theme->render('JBA\Site\Views::home/default.php');
    }

    public function version()
    {
        $settings = \JBA\Models\Settings::fetch();
        $this->app->set('settings', $settings);

        $title = $settings->{'site_home.page_title'} ? $settings->{'site_home.page_title'} : 'jbautosports.com - Performance Car Parts Online';
        $this->app->set('meta.title', $title);

        $desc = $settings->{'site_home.page_description'};
        $this->app->set('meta.description', $desc);

        $version_number = (int) \Base::instance()->get('PARAMS.version_number');

        // If the file doesn't exist, just use the default
        if (!$this->theme->findViewFile( 'JBA\Site\Views::home/version_' . $version_number . '.php' ))
        {
            echo $this->theme->render('JBA\Site\Views::home/default.php');
            return;
        }

        echo $this->theme->render('JBA\Site\Views::home/version_' . $version_number . '.php');
    }

    public function loginAsUser() {
    	if ($this->app->get('DEBUG') > 0) {
            $id = $this->app->get('PARAMS.id');

            if(\Dsc\Mongo\Helper::isValidId($id))  {
                $user = (new \Users\Models\Users)->setState('filter.id', $id)->getItem();
            } else {
                $user = (new \Users\Models\Users)->setCondition('gp.customer_number', $id)->getItem();
            }

            if(empty($user)) {
                \Dsc\System::addMessage('User not Found', 'error');
                $this->app->reroute('/');
            }

            $this->auth->login($user);
            //$this->auth->setIdentity($user);

            $this->app->reroute('/shop/account');
        }
    }

    public function viewRandomUser() {
       /* $random = (new \RallyShop\Models\Orders)->setState('list.limit', 1)->getItemsRandom();

        $user = (new \Users\Models\Users)->setState('filter.id',   $random[0]->user_id)->getItem();

        $this->auth->setIdentity($user);

        $this->app->reroute('/shop/account');  */
    }

}
