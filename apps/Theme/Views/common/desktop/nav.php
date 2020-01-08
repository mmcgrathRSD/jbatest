<div class="container hidden-xs">
	<?php if ($this->app->get('isHome')) : ?>
		<h1 class="logoHeader"><a href="/"><i class="sprite sprite-logo-_1" alt="Rally Sport Direct"></i></a></h1>
	<?php else : ?>
		<a href="/"><i class="sprite sprite-logo-_1"></i></a>
	<?php endif; ?>
	<?php
		if($checkoutmode == 0): 
			$cart = \Shop\Models\Carts::fetch();
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
		<form action="/search" method="GET">
			<i class="fa-search fa desktopSearchIcon"></i>
            <input id="search-box" type="text" name="q" class="form-control RSDSearch" autocomplete="off" placeholder="Search" value="" no-validation="true">
			<div class="autocomplete-suggestions" style="display:none"></div>
		</form>
		<div class="pull-right text-right">
			<ul>
        <a href="/contact-us"><li><i class="fa fa-phone" style="transition: all 0.0s ease-in-out;"></i> 1-888-45-RALLY</li></a>
				<?php if($this->auth->getIdentity()->id) : ?>
				<a href="/shop/account" class="accountName"><li><?php echo $fixName; ?></li></a>
				<div class="accountDropdown">
					<div class="mousehelper"></div>
					<div>
						<a href="/shop/account">MY ACCOUNT <i class="fa fa-chevron-right" style="transition: all 0.0s ease-in-out;font-size: 10px;"></i></a><br />
						<a href="/shop/wishlist">WISHLIST <i class="fa fa-chevron-right" style="transition: all 0.0s ease-in-out;font-size: 10px;"></i></a><br />
						<a href="/logout"><button class="btn btn-primary footerSubmit">Log Out</button></a>
					</div>
				</div>
			<?php else : ?>
				<a href="#" data-toggle="modal" data-target="#loginModal" class="viewLoginModal"><li>Sign In</li></a>
			<?php endif; ?>
				<a href="/shop/cart">
					<li>
						<i class="glyphicon glyphicon-shopping-cart"></i>
						Cart <span class="badge cartCount"><?php echo (int) $cart->quantity(); ?></span>
					</li>
				</a>
			</ul>
		</div>
	<?php endif;?>
</div>
