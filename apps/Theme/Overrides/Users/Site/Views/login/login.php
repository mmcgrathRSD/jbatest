
    <?php 
    $settings = \Users\Models\Settings::fetch();
    if ($settings->isSocialLoginEnabled()) 
    {
        ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3">
            <legend>
                Sign in with a social profile
            </legend>
                        
            <?php echo $this->renderLayout('Users/Site/Views::login/social.php'); ?>
            
            <p>&nbsp;</p>
            
            </div>
        </div>
        <?php  
    } 
    ?>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 paddingTop">
            <legend>
                <strong>Sign in</strong>
            </legend>
            
            <form action="./login" method="post" class="form paddingTop" role="form">
                <div class="form-group">
                    <label>Email Address</label>
                    <input class="form-control" name="login-username" placeholder="Email Address" type="email" required no-validation="true"/>
                </div>
                
                <div class="form-group">
                    <label>Password</label>
                    <input class="form-control" name="login-password" placeholder="Password" type="password" required no-validation="true"/>
                </div>
                <div class="form-group">
                    <label>Remember me</label>
                    <input name="remember" type="checkbox" checked="checked" />
                </div>
                <div class="form-group form-inline ">
                	                      
                    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign In</button>
                    
		               <?php if ($settings->{'general.registration.enabled'}) { ?>
		                  <div class="text-center text-muted paddingBottom paddingTop">
                			-or-
                		</div>
		                    <a href="./register" class="btn btn-lg btn-default btn-block">Create Account</a>
		              
		                <?php } ?>
                <br/><br/>
                    <a href="./user/forgot-password">Forgot your password?</a>
                </div>

                
            </form>
        </div>
    </div>
    