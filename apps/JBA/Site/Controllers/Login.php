<?php
namespace JBA\Site\Controllers;

class Login extends \Dsc\Controller
{

    /**
     * Displays a dual login/register form
     */
    public function index( $f3 )
    {    	
        $identity = $this->getIdentity();
        if (! empty( $identity->id ))
        {
            $f3->reroute( '/user' );
        }
        
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->{'general.registration.dual'}) 
        {
            $f3->reroute( '/sign-in' );
        }
        
        $this->app->set('meta.title', 'Log In');
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Users/Site/Views::login/dual.php' );
    }

    /**
     * Displays just a login form
     */
    public function only( $f3 )
    {
        $identity = $this->getIdentity();
        if (! empty( $identity->id ))
        {
            $f3->reroute( '/user' );
        }
        
        $this->app->set('meta.title', 'Log In');
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Users/Site/Views::login/login.php' );
    }

    /**
     * Displays just a registration form
     */
    public function register( $f3 )
    {
    	// check, if front-end registration is enabled
    	if( \Users\Models\Settings::fetch()->{'general.registration.enabled' } == '0' ){
    		$f3->reroute( '/user' );
    	}
    	 
        $identity = $this->getIdentity();
        if (! empty( $identity->id ))
        {
            $f3->reroute( '/user' );
        }
        
        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
        
        $this->app->set('meta.title', 'Register');
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Users/Site/Views::login/register.php' );
    }

    /**
     * Performs logout
     */
    public function logout()
    {
        \Dsc\System::instance()->get( 'auth' )->logout();
        
        if(!empty($_SERVER['HTTP_REFERER'])) {
        	\Base::instance()->reroute( $_SERVER['HTTP_REFERER'] );
        } else {
        	\Base::instance()->reroute( '/' );
        }
       
    }

    /**
     * Authenticates the user (performs the login)
     */
    public function auth()
    {
        $redirect = '/user';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        
        $input = $this->input->getArray();
        
        try
        {
            $this->auth->check( $input );
        }
        catch ( \Exception $e )
        {
            \Dsc\System::instance()->get( 'auth' )->logout();
            \Dsc\Cookie::forget('remember');
            
            $redirect = '/login';
        	if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.failed.redirect' ))
        	{
        		$redirect = $custom_redirect;
        	}

            \Dsc\System::addMessage( $e->getMessage(), 'error' );
            \Dsc\System::instance()->get( 'session' )->set( 'site.login.failed.redirect', null );
            \Base::instance()->reroute( $redirect );
            return;
        }
        
        if(!empty($input['remember'])) {
        	$user = $this->getIdentity();
        	$this->auth->createRememberEnviroment($user);
        }
        
        
        
        if(!empty($input['modallogin'])){
        	$this->app->reroute($_SERVER['HTTP_REFERER']);
        } else {
        	\Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
        	\Base::instance()->reroute( $redirect );
        }
        
     
        
        return;
    }

    /**
     * Creates the user
     * (target for the register form)
     */
    public function create()
    {
        $f3 = \Base::instance();
        // check, if front-end registration is enabled
        if( \Users\Models\Settings::fetch()->{'general.registration.enabled' } == '0' ){
        	$f3->reroute( '/login' );
        }
        
        $data = array(
        	'email' => trim( strtolower( $this->input->get( 'email', null, 'string' ) ) ),
            'username' => $this->input->get( 'username', null, 'string' ),
            'first_name' => $this->input->get( 'first_name', null, 'string' ),
            'last_name' => $this->input->get( 'last_name', null, 'string' ),
            'new_password' => $this->input->get( 'new_password', null, 'string' ),
            'confirm_new_password' => $this->input->get( 'confirm_new_password', null, 'string' )            
        );
        
        $registration_action = \Users\Models\Settings::fetch()->{'general.registration.action'};

        try
        {
        	$user = \Users\Models\Users::createNewUser( $data, $registration_action );

    		$flash = \Dsc\Flash::instance();
    		$flash->store(array());

    		$custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' );
    		
    		switch ($registration_action)
    		{
    			case "auto_login":
    				$redirect = $custom_redirect ? $custom_redirect : '/user';
    				\Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
    		
    				break;
    			case "auto_login_with_validation":
    				$redirect = $custom_redirect ? $custom_redirect : '/user';
    				\Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
    				break;
    			default:
    				$redirect = '/login/validate';
    				break;
    		}
    		
    		// redirect to the requested target, or the default if none requested
    		$f3->reroute( $redirect );

        } 
        catch( \Exception $e )
        {
        	\Dsc\System::addMessage( $e->getMessage(), 'error' );
        	
        	\Dsc\System::instance()->setUserState('users.site.register.flash_filled', true);
        	$flash = \Dsc\Flash::instance();
        	$flash->store($data);
        	
        	$f3->reroute('/register');
        }

    	return;
    }

    /**
     * Target for social logins
     */
    public function social()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->isSocialLoginEnabled()) 
        {
            \Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/login" );
        }
        
        try
        {
            \Hybrid_Endpoint::process();
        }
        catch ( \Exception $e )
        {
            \Dsc\System::addMessage( 'Social Login failed', 'error' );
            \Dsc\System::addMessage( $e->getMessage(), 'error' );

            $custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'social_login.failure.redirect' );
            $redirect = $custom_redirect ? $custom_redirect : '/login';
            \Base::instance()->reroute( $redirect );
        }
    }

    /**
     * 
     */
    public function provider()
    {
        $settings = \Users\Models\Settings::fetch();
        if (!$settings->isSocialLoginEnabled())
        {
            \Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/login" );
        }
        
        // check, if front-end registration is enabled
        if( $settings->{'general.registration.enabled' } == '0' ){
        	\Base::instance()->reroute( '/login' );
        }
                
        $f3 = \Base::instance();
        
        // IMPORTANT: lowercase should always be used in all keys
        $provider = strtolower( $f3->get( 'PARAMS.provider' ) );
        if (!$settings->isSocialLoginEnabled($provider))
        {
            \Dsc\System::addMessage( 'This social profile is not supported.', 'error' );
            \Base::instance()->reroute( "/login" );
        }        
        
        $hybridauth_config = \Users\Models\Settings::fetch();
        $config = (array) $hybridauth_config->{'social'};
        
     
        if (empty($config['base_url'])) {
            $config['base_url'] = $f3->get('SCHEME') . '://' . $f3->get('HOST') . $f3->get('BASE') . '/login/social';
        }
        
        try
        {
            // create an instance for Hybridauth with the configuration file path as parameter
            $hybridauth = new \Hybrid_Auth( $config );
           
            // try to authenticate the selected $provider
            $adapter = $hybridauth->authenticate( $provider );
             
            // grab the user profile
            $user_profile = $adapter->getUserProfile();
            // 1 - try to lookup the user based on the profile.identifier
            // if found, log them in to our system and redirect to their profile page
            $model = new \Users\Models\Users;
            $filter = 'social.' . $provider . '.profile.identifier';            
            $user = $model->setCondition( $filter, $user_profile->identifier )->getItem();            
            if (! empty( $user->id ))
            {   
                //Update the profile information from this network, and renew access token
                $user->set( 'social.' . $provider . '.profile', (array) $adapter->getUserProfile() );
                $user->set( 'social.' . $provider . '.access_token', (array) $adapter->getAccessToken() );
                
                $user->save();
                
                //Login the user
                \Dsc\System::instance()->get( 'auth' )->login( $user );
                
                // check the user's flags (active/suspended/banned)
                \Dsc\System::instance()->get( 'auth' )->checkUserFlags( $user );
                
                // redirect to the requested target, or the default if none requested
                $redirect = '/user';
                if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' ))
                {
                    $redirect = $custom_redirect;
                }
                \Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
                    

                \Base::instance()->reroute( $redirect );
            }
            
            // 2 - check if the user email we got from the provider already exists in our database ( for this example the email is UNIQUE for each user )
            if ($user_profile->email)
            {
                // 3 - if the email address returned by the provider does exist in our database,
                // then authenticate with that user
                $user = (new \Users\Models\Users)->setState( 'filter.email', $user_profile->email )->getItem();
                if (!empty($user->id))
                {
                    $user->set( 'social.' . $provider . '.profile', (array) $adapter->getUserProfile() );
                    $user->set( 'social.' . $provider . '.access_token', (array) $adapter->getAccessToken() );
                    $user->save();
                    
                    \Dsc\System::instance()->get( 'auth' )->login( $user );
                    
                    // redirect to the requested target, or the default if none requested
                    $redirect = '/user';
                    if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' ))
                    {
                        $redirect = $custom_redirect;
                    }
                    \Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
                    \Base::instance()->reroute( $redirect );
                }
                
                // email doesn't exist in our database 
                else 
                {
                	
                }
            }
            
            // email not provided by provider
            else 
            {
            	
            }
            
            // 4 - if social profile id does not exist in our database and email is not in use, then we are creating a new user
            // so first let's prepare the data 
            $data = array();
            $data['social'][$provider]['profile'] = (array) $adapter->getUserProfile();
            $data['social'][$provider]['access_token'] = (array) $adapter->getAccessToken();
            $data['email'] = $user_profile->email;
            $data['first_name'] = $user_profile->firstName;
            $data['last_name'] = $user_profile->lastName;
            $data['username'] = \Users\Models\Users::usernameFromString( $user_profile->displayName );
            
            // if last name is empty, try to extract last name from first name field
            if (empty($user_profile->lastName) && !empty($user_profile->firstName) && strrpos($user_profile->firstName, ' ') !== false ) 
            {
            	$pieces = explode(' ', $user_profile->firstName, 2);
            	$data['first_name'] = $pieces[0];
            	$data['last_name'] = $pieces[1];            	
            }
            
            // put the data array into the session, and bind the array to a Users\Models\Users object on the flip side
            \Dsc\System::instance()->get('session')->set('users.incomplete_provider_data', $data );
            
            // Now push the user to a "complete your profile" form prepopulated with data from the provider identity
            $f3->reroute( '/login/completeProfile' ); 

        }
        catch ( \Exception $e )
        {
            $user_error = $e->getMessage();
            
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
                        
            if ($f3->get( 'DEBUG' ))
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
            
            \Dsc\System::addMessage( 'Login failed', 'error' );
            \Dsc\System::addMessage( $error, 'error' );
            
            $f3->reroute( '/login' );
        }
    }

    /**
     * Displays a profile completion form
     */
    public function completeProfileForm()
    {
        $settings = \Users\Models\Settings::fetch();        
        if (!$settings->isSocialLoginEnabled())
        {
            \Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/login" );
        }
                
        $f3 = \Base::instance();
        
        $identity = $this->getIdentity();
        if (! empty( $identity->id ))
        {
            $f3->reroute( '/user' );
        }
        
        // bind the data to a model
        $data = \Dsc\System::instance()->get('session')->get('users.incomplete_provider_data' );
        $user = (new \Users\Models\Users)->bind($data);
        
        $flash = \Dsc\Flash::instance();
        $f3->set('flash', $flash );
        
        $flash_filled = \Dsc\System::instance()->getUserState('users.site.login.complete_profile.flash_filled');
        if (!$flash_filled) {
            $flash->store($user->cast());
        }
        
        // TODO If the profile is complete, redirect to /user
        \Base::instance()->set('model', $user);
        
        $this->app->set('meta.title', 'Complete Profile | Login');
    
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->renderTheme( 'Users/Site/Views::login/complete_profile.php' );
    }
    
    /**
     * Target for the completeProfileForm submission 
     */
    public function completeProfile()
    {
        $settings = \Users\Models\Settings::fetch();
        // check, if front-end registration is enabled
        if( $settings->{'general.registration.enabled' } == '0' ){
        	$f3->reroute( '/login' );
        }
        
        if (!$settings->isSocialLoginEnabled())
        {
            \Dsc\System::addMessage( 'Social login is not supported.', 'error' );
            \Base::instance()->reroute( "/login" );
        }
                
        $f3 = \Base::instance();
        
        try 
        {
            
            $data = \Dsc\System::instance()->get('session')->get('users.incomplete_provider_data' );
            $data['email'] = $this->input->get( 'email', null, 'string' );
            $data['username'] = $this->input->get( 'username', null, 'string' );
            
            // we just got an email from a customer, so we need to verify it
            $user = \Users\Models\Users::createNewUser($data, 'auto_login_with_validation');
            
            // social login should always login the user if successful,
            // so login the user if they aren't already logged in
            if (empty($this->getIdentity()->id)) {
                \Dsc\System::instance()->get( 'auth' )->login( $user );
            }
            
            \Dsc\System::instance()->get('session')->set('users.incomplete_provider_data', array() );
                        
        } 
        catch (\Exception $e) 
        {
            switch ($e->getCode()) 
            {
            	case \Users\Models\Users::E_EMAIL_EXISTS:
            	    
            	    // This email is already registered
            	    // Push the user back to the login page,
            	    // and tell them that they must first sign-in using another method (the one they previously setup),
            	    // then upon login, they can link this current social provider to their existing account
            	    \Dsc\System::addMessage( 'This email is already registered.', 'error' );
            	    \Dsc\System::addMessage( 'Please login using the registered email address or with the other social profile that also uses this email address.', 'error' );
            	    \Dsc\System::addMessage( 'Once you are logged in, you may link additional social profiles to your account.', 'error' );
            	    
            	    $f3->reroute( '/login' );
            	     
            	    break;
            	default:
            	    
            	    \Dsc\System::addMessage( 'Registration failed.', 'error' );
            	    \Dsc\System::addMessage( $e->getMessage(), 'error' );
            	    
            	    \Dsc\System::instance()->setUserState('users.site.login.complete_profile.flash_filled', true);
            	    $flash = \Dsc\Flash::instance();
            	    $flash->store($data);
            	    
            	    $f3->reroute('/login/completeProfile');
            	     
            	    break;
            }
            
            return;
        }

        // if we have reached here, then all is right with the world.
        // redirect to the requested target, or the default if none requested
        $redirect = '/user';
        if ($custom_redirect = \Dsc\System::instance()->get( 'session' )->get( 'site.login.redirect' ))
        {
            $redirect = $custom_redirect;
        }
        \Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', null );
        $f3->reroute( $redirect );
    }
    
    /**
     * Display a static page requesting email validation
     */
    public function validate()
    {
        $this->app->set('meta.title', 'Validate Email Address | Login');
        
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Users/Site/Views::login/validate.php' );
    }
    
    /**
     * Validates a token, usually from clicking on a link in an email
     * 
     * @throws \Exception
     */
    public function validateToken()
    {
        $f3 = \Base::instance();
        $token = $this->inputfilter->clean( $f3->get('PARAMS.token'), 'alnum' );
        
        try
        {
        	$user = \Users\Models\Users::validateLoginToken( $token );
        	\Dsc\System::addMessage( 'Thank you for validating your email address. You may now login.' );
        	if (!empty($this->getIdentity()->id)) {
        	    \Dsc\System::instance()->get( 'auth' )->logout();
        	}
        	
        	$f3->reroute( '/login' );
        } 
        catch( \Exception $e )
        {
        	\Dsc\System::addMessage( 'Email validation failed.  Please confirm the token and try again.', 'error' );
        	\Dsc\System::addMessage( $e->getMessage(), 'error' );
            if (!empty($this->getIdentity()->id)) {
        	    \Dsc\System::instance()->get( 'auth' )->logout();
        	}
        	
        	$f3->reroute( '/login/validate' );
        }
    }
    
    /**
     * Display a static page requesting email address
     * so we can send them a validation email
     * 
     */
    public function validateEmail()
    {
        $this->app->set('meta.title', 'Validate Email Address | Login');
    
        $view = \Dsc\System::instance()->get( 'theme' );
        echo $view->render( 'Users/Site/Views::login/validate_email_form.php' );
    }
    
    /**
     * 
     * @throws \Exception
     */
    public function validateEmailSubmit()
    {
        $email = $this->input->get( 'email', null, 'string' );
        
        try 
        {
            $user = (new \Users\Models\Users)->setState('filter.email', $email)->getItem();
            if (empty($user->id))
            {
                throw new \Exception;
            }
            
            $user->sendEmailValidatingEmailAddress();
            $this->app->reroute( '/login/validate' );
        }
         
        catch ( \Exception $e ) 
        {
            \Dsc\System::addMessage( 'Unrecognized email address.', 'error' );
            $this->app->reroute( '/login/validate-email' );
        }
    }
}
