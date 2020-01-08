<?php
	$lightTheme = false;
	$fixName = 'Account';
	if($this->auth->getIdentity()->id) {
		$fixName = $this->auth->getIdentity()->fullName();
		if(strlen($fixName) > 10) {
			$fixName = $this->auth->getIdentity()->first_name;
		}
		if(strlen($fixName) > 10) {
			$fixName = 'Account';
		}
	}
?>
<?php if($checkoutmode == 0) : ?>
<div class="topMobile visible-xs">
	<a class="btn btn-link navBtn fgWhite btn-default glyphicon glyphicon-menu-hamburger pull-left" id="slideOut">
		<span class="sr-only">Toggle navigation</span>
	</a>
	<a href="/shop/cart" class="btn btn-link navBtn fgWhite btn-default glyphicon glyphicon-shopping-cart pull-right">
		<span class="sr-only">Toggle navigation</span>
	</a>
    <a href="/"><img src="/theme/img/logo.png"></a>
</div>
<div class="mobileNavHead visible-xs">
	<a href="#" class="goBack active secondHead" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-left"></i>Back</a>
	<?php if($this->auth->getIdentity()->id) : ?>
		<a href="/shop/account" class="accountName"> <?php echo $fixName; ?></a>
		<div class="accountDropdown">
			<div>
				<a href="/shop/account">MY ACCOUNT <i class="fa fa-chevron-right" style="transition: all 0.0s ease-in-out;font-size: 10px;"></i></a><br />
				<a href="/shop/wishlist">WISHLIST <i class="fa fa-chevron-right" style="transition: all 0.0s ease-in-out;font-size: 10px;"></i></a><br />
				<a href="/logout"><button class="btn btn-primary footerSubmit">LOG OUT</button></a>
			</div>
		</div>
	<?php else : ?>
		<a href="#" data-toggle="modal" data-target="#loginModal" class="viewLoginModal">Sign In</a>
	<?php endif; ?>
</div>
<div class="bigButtonNav visible-xs">
</div>
<div class="mobileSearch visible-xs col-xs-12">
	<i class="fa fa-search RSDSearchIcon"></i><input id="search-box-mobile" type="text" name="q" class="RSDSearch" autocomplete="off" placeholder="Search for..." value="<?php echo @$terms; ?>" no-validation="true">
</div>
<?php else : ?>
<div class="topMobile visible-xs">
    <a href="/"><img src="/theme/img/logo.png"></a>
</div>
<?php endif; ?>