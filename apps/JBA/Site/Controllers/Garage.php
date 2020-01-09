<?php 
namespace JBA\Site\Controllers;

class Garage extends \Dsc\Controller 
{
    public function index()
    {
        $settings = \JBA\Models\Settings::fetch();
        $this->app->set('settings', $settings);
        
        $title = $settings->{'site_home.page_title'} ? $settings->{'site_home.page_title'} : 'jbautosports.com - Performance Car Parts Online';
        $this->app->set('meta.title', $title);
        
        $desc = $settings->{'site_home.page_description'};
        $this->app->set('meta.description', $desc);
        
        echo $this->theme->render('JBA\Site\Views::home/default.php');
    }
    
    
    
   public function removeVehicle() {
   		$this->requireIdentity();
   		
   		$user = $this->getIdentity();
   		
   		$id = $this->app->get('PARAMS.id');
   		
   		
   		$cars = $user->garage;
   		
   		unset($cars[(string)$id]);
   		
   		$user->set('garage', $cars);
   		$user->save()->reload();
   		$this->auth->setIdentity($user);
   		
   		if ($this->app->get('AJAX')) {
   			return $this->outputJson( $this->getJsonResponse( array(
   					'result'=>true
   			) ) );
   		} else {
   			\Dsc\System::addMessage('Removed Vehicle');
   			$this->app->reroute('/user#garage');
   		}
   	
   	
   	
   }
}