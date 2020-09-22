<?php

class ThemeBootstrap extends \Dsc\Bootstrap
{

    protected $dir = __DIR__;

    protected $base = __DIR__;

    protected $namespace = 'Theme';

    /**
     * Register this app's view files for all global_apps
     *
     * @param string $global_app
     */
    protected function registerViewFiles($global_app)
    {
        \Dsc\System::instance()->get('theme')->registerViewPath($this->dir . '/' . $global_app . '/Views/', $this->namespace . '/' . $global_app . '/Views');
    }

    /**
     * Triggered when the admin global_app is run
     */
    protected function runAdmin()
    {
        parent::runAdmin();

        // Tell the admin that this is an available front-end theme
        \Dsc\System::instance()->get('theme')->registerTheme('Theme', $this->app->get('PATH_ROOT') . 'apps/Theme/');

        if (class_exists('\Modules\Factory'))
        {
            // register this theme's module positions with the admin
            \Modules\Factory::registerPositions(array(
                'sidebar-slider',
                'homepage-mid-slider',
                'homepage-slider',
				'homepage-category-boxes',
            	'footer-promo',
                'footer-head',
				'homepage-end',
				'404-end',
				'cart-end',
				'confirmation-end',
				'product-post-description',
            ));
        }

        \Minify\Factory::registerPath($this->app->get('PATH_ROOT') . "public/theme/");
        $files = array(
        	'js/shop/returns.js',
        );

        foreach ($files as $file)
        {
            \Minify\Factory::js($file, array(
                'priority' => 1
            ));
        }

    }

    protected  function runCli() {
    	\Dsc\System::instance()->get('theme')->setTheme('Theme', $this->app->get('PATH_ROOT') . 'apps/Theme/');
    	\Dsc\System::instance()->get('theme')->registerViewPath($this->app->get('PATH_ROOT') . 'apps/Theme/Views/', 'Theme/Views');
    }
    /**
     * Triggered when the front-end global_app is run
     */
    protected function runSite()
    {
    	//$this->app->route('GET /system/style.min.css', '\Minify\Controller->css');
    	//$this->app->route('GET /system/scripts.min.js', '\Minify\Controller->js');

        // link the theme to public folder
        if (!is_dir($this->app->get('PATH_ROOT') . 'public/theme'))
        {
            $public_theme = $this->app->get('PATH_ROOT') . 'public/theme';
            $theme_assets = realpath( $this->app->get('PATH_ROOT') . 'apps/Theme/Assets' );
            $res = symlink($theme_assets, $public_theme);
        }

        \Dsc\System::instance()->get('theme')->setTheme('Theme', $this->app->get('PATH_ROOT') . 'apps/Theme/');
        \Dsc\System::instance()->get('theme')->registerViewPath($this->app->get('PATH_ROOT') . 'apps/Theme/Views/', 'Theme/Views');

        // tell Minify where to find Media, CSS and JS files
        \Minify\Factory::registerPath($this->app->get('PATH_ROOT') . "public/theme/");
		\Minify\Factory::registerPath($this->app->get('PATH_ROOT') . "public/shop-assets/");
        //\Minify\Factory::registerPath($this->app->get('PATH_ROOT') . "public/theme/css/");
        //\Minify\Factory::registerPath($this->app->get('PATH_ROOT') . "public/");

        // register the scss css files
        \Minify\Factory::registerScssSource($this->app->get('PATH_ROOT') . "apps/Theme/Assets/scss/theme_master_" . $this->app->get('sales_channel') . ".scss", $this->app->get('PATH_ROOT') . "apps/Theme/Assets/scss/scss_master.css");
        

        // add the OLD css assets to be minified
        $files = array(
			'css/search.css',
            'css/instantsearch.min.css',
            // 'scss/custom/jba/from_site_backup/jqueryfancybox.css',
            'scss/custom/jba/from_site_backup/styles.css',
            'scss/custom/jba/from_site_backup/widgets.css',
            'scss/custom/jba/from_site_backup/slideshow.css',
            'scss/custom/jba/from_site_backup/settings.css',
            'scss/custom/jba/from_site_backup/megamenu.css',
            'scss/custom/jba/from_site_backup/local.css',
            'scss/custom/jba/from_site_backup/animation.css',
            'scss/custom/jba/from_site_backup/grid.css',
            'scss/scss_master.css'
        );

		if(!$this->app->get('DEBUG'))  {
			$files[] = '/scss/scss_master.css';
		}

        foreach ($files as $file)
        {
            \Minify\Factory::css($file);
        }

		$files = array(
			'js/vendor/ie_assign_polyfill.js',
			//'js/vendor/instantsearch.min.js',
			'js/vendor/slick.min.js',
			'js/vendor/stickyickyicky.js',
			'js/vendor/simpleMask.js',
            'js/vendor/alphanum.js',
            'js/ymm.js',
            'js/stock_cutoff_timer.js',
			//'js/checkout.js',
            //End shop assets. Start theme assets
            'js/jba2.js',
            'js/vendor/jquery-1.11.2.min.js',
            'js/custom.js',
            'js/custom_includes/jba_custom_algolia.js',
            'js/shop_custom.js',
            'js/estimate_shipping.js',
            'js/custom_includes/jba_custom.js'
		);

        foreach ($files as $file)
        {
            \Minify\Factory::js($file, array(
                'priority' => 1
            ));
        }

        \Dsc\System::instance()->getDispatcher()->addListener(\Theme\Listeners\Error::instance());

        parent::runSite();
    }
}
$app = new ThemeBootstrap();
