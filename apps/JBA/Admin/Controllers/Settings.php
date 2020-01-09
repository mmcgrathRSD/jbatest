<?php 
namespace JBA\Admin\Controllers;

class Settings extends \Admin\Controllers\BaseAuth 
{
	use \Dsc\Traits\Controllers\Settings;
	
	protected $layout_link = 'RallySport/Admin/Views::settings/default.php';
	protected $settings_route = '/admin/rallysport/settings';
    
    protected function getModel()
    {
        $model = new \JBA\Models\Settings;
        return $model;
    }
    
    public function siteHome()
    {
        $this->settings_route = '/admin/rallysport/site/home';
    
        $f3 = \Base::instance();
        switch ($f3->get('VERB')) {
        	case "POST":
        	case "post":
        	    // do the save and redirect to $this->settings_route
        	    return $this->save();
        	    break;
        }
    
        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
    
        $settings = \JBA\Models\Settings::fetch();
        $flash->store( $settings->cast() );
        
        $this->app->set('meta.title', 'Homepage | Amrita');
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderTheme('RallySport/Admin/Views::settings/site_home.php');
    }
    
    public function shopHome()
    {
        $this->settings_route = '/admin/rallysport/shop/home';
        
        $f3 = \Base::instance();
        switch ($f3->get('VERB')) {
        	case "POST":
        	case "post":
        	    // do the save and redirect to $this->settings_route 
        	    return $this->save();
        	    break;
        }

        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
        
        $settings = \JBA\Models\Settings::fetch();
        $flash->store( $settings->cast() );        
        
        $this->app->set('meta.title', 'Shop Home | Amrita');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->renderTheme('RallySport/Admin/Views::settings/shop_home.php');
    }
}