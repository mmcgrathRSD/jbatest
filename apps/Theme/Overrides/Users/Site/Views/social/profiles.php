<?php 
	// find out how many social account the user can link to
	$settings = \Users\Models\Settings::fetch();
	$providers = $settings->enabledSocialProviders();
	$unlinked = $user->unlinkedSocialProfiles();
?>

<div class="container">
    <ol class="breadcrumb">
        <li>
            <a href="./user">My Account</a>
        </li>
        <li class="active">Social Profiles</li>
    </ol>
    
    <legend class="clearfix">
        Social Profiles
        <div class="pull-right"><a class="btn btn-link" href="./user">Cancel</a></div>
    </legend>
    
    <p class="help-block">Link your social profiles and enjoy one-click logins.</p>
    
    <h3>Connected Accounts</h3>
    
	<?php if (empty( $user->social )) { ?>
		<p>Currently, you do not have any social profiles linked to your account.</p>        
	<?php } ?>
    	
	<?php $n=0; $count=count($user->social); 
	foreach( (array) $user->social as $network => $profile ) 
	{  
		$profile_img = \Dsc\ArrayHelper::get($profile, 'profile.photoURL');
		$name = \Dsc\ArrayHelper::get($profile, 'profile.displayName');
		
		if( empty( $profile_img ) ) {
			$profile_img = './minify/Users/Assets/images/empty_profile.png';
		}
        ?>
        
        <?php if ($n == 0 || ($n % 4 == 0)) { ?><div class="row"><?php } ?>
                
        <div class="col-xs-6 col-sm-6 col-md-3 col-lg-3">
            
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <?php echo $network; ?>
                    
                    <a class="btn btn-xs btn-danger pull-right" data-bootbox="confirm" href="./user/social/unlink/<?php echo $network; ?>">
                        <i class="fa fa-times"></i>
                    </a>                                        
                </div>
                <div class="panel-body">
                    <p><img src="<?php echo $profile_img; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></p>
                    <a href="<?php echo \Dsc\ArrayHelper::get($profile, 'profile.profileURL'); ?>" target="_blank"><?php echo $name;?></a>
                </div>
            </div>
    
        </div>
                    
        <?php $n++; if (($n % 4 == 0) || $n==$count) { ?></div><?php } ?>
        <?php 
	} 
    ?>
    
    <?php if( !empty($unlinked) ) {
        // TODO Set this AFTER clicking the link, if possible.  When it's here, this hijacks all successive login redirects  
    	\Dsc\System::instance()->get( 'session' )->set( 'site.login.redirect', '/user/social-profiles' );
    	?>

    	<h3>Link your profile with</h3>
    	<div class="social-login-providers">
    	
    		<?php foreach( $unlinked as $network ) { ?>
            <div class="form-group">
                <a href="./user/social/link/<?php echo $network; ?>" class="btn btn-<?php echo strtolower($network); ?> btn-default">
                <i class="fa fa-<?php echo strtolower($network); ?>"></i> <span><?php echo $network; ?></span>
                </a>
            </div>    		
    		<?php } ?>
    	
    	</div>
    <?php } ?>
    
</div>