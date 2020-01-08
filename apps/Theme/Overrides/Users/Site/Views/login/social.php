<?php $settings = \Users\Models\Settings::fetch(); ?>

<div class="social-login-providers">

<?php if ($settings->isSocialLoginEnabled('facebook') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/facebook" class="btn btn-facebook btn-default">
    <i class="fa fa-facebook"></i> <span>Facebook</span>
    </a>
</div>
<?php } ?>
    
<?php if ($settings->isSocialLoginEnabled('twitter') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/twitter" class="btn btn-twitter btn-default">
    <i class="fa fa-twitter"></i> <span>Twitter</span>
    </a>
</div>
<?php } ?>

<?php if ($settings->isSocialLoginEnabled('google') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/google" class="btn btn-google btn-default">
    <i class="fa fa-google"></i> <span>Google</span>
    </a>
</div>
<?php } ?>

<?php if ($settings->isSocialLoginEnabled('linkedin') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/LinkedIn" class="btn btn-linkedin btn-default">
    <i class="fa fa-linkedin"></i> <span>LinkedIn</span>
    </a>
</div>
<?php } ?>

<?php if ($settings->isSocialLoginEnabled('github') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/GitHub" class="btn btn-github btn-default">
    <i class="fa fa-github"></i> <span>GitHub</span>
    </a>
</div>
<?php } ?>

<?php if ($settings->isSocialLoginEnabled('instagram') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/Instagram" class="btn btn-instagram btn-default">
    <i class="fa fa-instagram"></i> <span>Instagram</span>
    </a>
</div>
<?php } ?>

<?php if ($settings->isSocialLoginEnabled('paypalopenid') ) { ?>
<div class="form-group">
    <a href="./login/social/auth/PaypalOpenID" class="btn btn-paypal btn-default">
    <i class="fa fa-paypal"></i> <span>Paypal</span>
    </a>
</div>
<?php } ?>

</div>