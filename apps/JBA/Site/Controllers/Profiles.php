<?php
namespace RallySport\Site\Controllers;

class Profiles extends \Dsc\Controller
{
    public function read()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->{'general.profiles.enabled'})
        {
            $this->app->reroute( "/" );
        }

        $user = $this->getItem();

        $this->app->set('user', $user);
        $this->app->set('noindex', true);
        
        $this->app->set('meta.title', $user['first_name'] . ' | Profile');

        $view = \Dsc\System::instance()->get('theme');
        if($user->hide_profile) {
        	 echo $view->render( 'Users/Site/Views::profile/private.php' );
        } else {
        	echo $view->render( 'Users/Site/Views::profile/read.php' );
        }
      
    }
    
    public function readSelf()
    {
    	$this->requireIdentity();
    	
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->{'general.profiles.enabled'})
        {
            $this->app->reroute( "/user/settings" );
        }
    
        $identity = $this->getIdentity();
        if (empty($identity->id)) 
        {
            $this->app->reroute( '/login' );
            return;
        }
        
        if (!empty($identity->__safemode)) 
        {
        	$user = $identity;
        } 
            else 
        {
            $model = $this->getModel()->setState( 'filter.id', $identity->id );
            $user = $model->getItem();
        }

        $this->app->set('user', $user);
        
        $this->app->set('meta.title', 'My Profile | My Account');
            
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render( 'Users/Site/Views::profile/readSelf.php' );
    }
    
    public function settings()
    {
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            $this->app->reroute( '/login' );
            return;
        }
    
        if (!empty($identity->__safemode))
        {
            $user = $identity;
        }
        else
        {
            $model = $this->getModel()->setState( 'filter.id', $identity->id );
            $user = $model->getItem();
        }
    
        $this->app->set('user', $user);
    
        $this->app->set('meta.title', 'Settings | My Account');
    
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render( 'Users/Site/Views::profile/edit.php' );
    }
    
    protected function getModel()
    {
        $model = new \Users\Models\Users;
        return $model;
    }
    
    protected function getItem()
    {
        $id = $this->inputfilter->clean( $this->app->get( 'PARAMS.id' ), 'alnum' );
    
        try {
            $item = $this->getModel()
                ->setCondition('_id', new \MongoDB\BSON\ObjectID($id))
                ->getItem();
        } catch (\Exception $e) {}

        if (empty($item)) {
            $item = $this->getModel()
                ->setCondition('$or', [
                    ['number' => $id],
                    ['gp.customer_number' => $id],
                    ['gp.additional_customer_numbers' => $id]
                ])
                ->getItem();
        }

        if (empty($item)) {
            \Dsc\System::instance()->addMessage('Profile does not exist.', 'error' );
            $this->app->reroute('/');
            return;
        }

        return $item;
    }
    
    /**
     * Displays the logged-in user's list of linked social profiles
     */
    public function socialProfiles()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->isSocialLoginEnabled())
        {
            \Base::instance()->reroute( "/user" );
        }
        
        $identity = $this->getIdentity();
        if (empty($identity->id))
        {
            $this->app->reroute( '/login' );
            return;
        }
        
        if (!empty($identity->__safemode))
        {
            $this->app->reroute( '/user' );
            return;
        }
        
        $user = $identity;
        $this->app->set('user', $user);
        
        $this->app->set('meta.title', 'Linked Social Profiles | My Account');
        
        $view = \Dsc\System::instance()->get('theme');
        echo $view->render( 'Users/Site/Views::social/profiles.php' );        
    }
    
    public function unlinkSocialProfile()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->isSocialLoginEnabled())
        {
            \Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/user" );
        }
                
    	$provider = strtolower( $this->inputfilter->clean( $this->app->get( 'PARAMS.provider' ), 'alnum' ) );
    	 
    	$identity = $this->getIdentity();
    	if (empty($identity->id))
    	{
    		$this->app->reroute( '/login' );
    		return;
    	}
    	
    	if (!empty($identity->__safemode))
    	{
    	    $this->app->reroute( '/user' );
    	    return;
    	}
    	
    	$user = $identity;
    	
    	try {
    	    foreach ($user->social as $network=>$id)
    	    {
                if (strtolower($network) == strtolower($provider)) 
                {
                    $user->clear( 'social.'.$network );
                }
    	    }
    	    $user->save();
    	    \Dsc\System::addMessage( 'Profile unlinked.', 'success' );
    	}
    	catch(\Exception $e) {
    	    \Dsc\System::addMessage( 'Could not unlink profile.', 'error' );
    	    \Dsc\System::addMessage( $e->getMessage(), 'error' );
    	}
    	
    	$this->app->reroute( '/user/social-profiles' );
    	return; 
    }
    
    public function linkSocialProfile()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->isSocialLoginEnabled())
        {
        	\Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/user" );
        }
        
        $user = $this->getIdentity();
        if (empty($user->id) || !empty($user->__safemode))
        {
            $this->app->reroute( '/user' );
            return;
        }
        
        $provider = strtolower( $this->app->get( 'PARAMS.provider' ) );
        if (!$settings->isSocialLoginEnabled($provider))
        {
            \Dsc\System::addMessage( 'This social profile is not supported.', 'error' );
            \Base::instance()->reroute( "/user" );
        }
        
        $hybridauth_config = \Users\Models\Settings::fetch();
        $config = (array) $hybridauth_config->{'social'};
        
        \Dsc\System::instance()->get( 'session' )->set( 'social_login.failure.redirect', '/user/social-profiles' );
        
        if (empty($config['base_url'])) {
            $config['base_url'] = $this->app->get('SCHEME') . '://' . $this->app->get('HOST') . $this->app->get('BASE') . '/login/social';
        }
        
        $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' );
        
        try
        {
            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new \Hybrid_Auth( $config );
            // try to authenticate the selected $provider
            $adapter = $hybridauth->authenticate( $provider );
            
            // grab the user profile
            $user_profile = $adapter->getUserProfile();
        
            // OK, we have the social identity.  
            // Let's make sure it's unique in our system
            $filter = 'social.' . $provider . '.profile.identifier';
            $found = (new \Users\Models\Users)->setCondition( $filter, $user_profile->identifier )->getItem();            
            if (!empty( $found->id ) && (string) $found->id != (string) $user->id)
            {
                // errrrr, only allow a social ID to be linked to one account at a time
                \Dsc\System::addMessage( 'This social profile is already registered with us.', 'error' );
                        
                // redirect to the requested target, or the default if none requested
                $redirect = $custom_redirect ? $custom_redirect : '/user';
                \Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
                \Base::instance()->reroute( $redirect );
                return;
            }
            
            // add the social id to the user
            $user->set( 'social.' . $provider . '.profile', (array) $adapter->getUserProfile() );
            $user->set( 'social.' . $provider . '.access_token', (array) $adapter->getAccessToken() );
            
            $user->save();
        
        }
        catch ( \Exception $e )
        {
            $user_error = null;
        
            switch ($e->getCode())
            {
            	case 0 :
            	    $error = "Unspecified error.";
            	    break;
            	case 1 :
            	    $error = "Hybridauth configuration error.";
            	    break;
            	case 2 :
            	    $error = "Provider not properly configured.";
            	    break;
            	case 3 :
            	    $error = "Unknown or disabled provider.";
            	    break;
            	case 4 :
            	    $error = "Missing provider application credentials.";
            	    break;
            	case 5 :
            	    $error = "Authentication failed. The user has canceled the authentication or the provider refused the connection.";
            	    $user_error = "Authentication failed.";
            	    break;
            	case 6 :
            	    $error = "User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.";
            	    $user_error = "We were unable to get your profile.  Please authenticate again with the profile provider.";
            	    $adapter->logout();
            	    break;
            	case 7 :
            	    $error = "User not connected to the provider.";
            	    $user_error = "No profile found with the provider.  Missing connection.";
            	    $adapter->logout();
            	    break;
            }
        
            if ($this->app->get( 'DEBUG' ))
            {
                // if debug mode is enabled, display the full error
                $error .= "<br /><br /><b>Original error message:</b> " . $e->getMessage();
                $error .= "<hr /><pre>Trace:<br />" . $e->getTraceAsString() . "</pre>";
            }
            else
            {
                // otherwise, display something simple
                $error = $user_error;
            }
        
            \Dsc\System::addMessage( 'Linking failed', 'error' );
            \Dsc\System::addMessage( $error, 'error' );
        
            $redirect = $custom_redirect ? $custom_redirect : '/user';
            $this->app->reroute( $redirect );
        }
        
        // redirect to the requested target, or the default if none requested
        $redirect = $custom_redirect ? $custom_redirect : '/user';
        \Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
        $this->app->reroute( $redirect );
                
    }
}
