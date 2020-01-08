<?php /** @var \Shop\Models\Products $item */ ?>

<form action="/shop/cart/add" method="get" target="_top" class="addToCartForm" <?php if(\Dsc\ArrayHelper::get($item->policies, 'requires_assembly')) : ?>data-assembled="1"<?php endif; ?>>
	<div id="validation-cart-add" class="validation-message"></div>
		<?php if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) { ?>
			<select name="variant_id" class="form-control chosen-select select-variant variant_id matrixSelect" data-callback="Shop.selectVariant" no-validation="true" required>
				<option selected disabled>Please select an option</option>
				<?php foreach (  $item->variantsSorted() as $key => $variant ) {
					$wishlist_state = \Shop\Models\Wishlists::hasAddedVariant ( $variant ['id'], ( string ) $this->auth->getIdentity ()->id ) ? '0' : '1';
				?>
					<option value="<?php echo $variant['id']; ?>"  data-model="<?php echo $variant['model_number'] ?>"
					<?php if($variant['quantity'] == 0 && $item->{'tracking.model_number'} != 'RSD 50100') : ?>
					data-stock='outofstock' 
					<?php else: ?>
					data-stock='instock' 
					 <?php endif;?>
				<?php if(!empty($variant ['image'])) : ?> data-image='<?php echo $variant ['image']; ?>' <?php endif; ?> data-variant='<?php
						echo htmlspecialchars ( json_encode ( array (
							'id' => $variant ['id'],
							'key' => $variant ['key'],
							'image' => $variant ['image'],
							'quantity' => $variant ['quantity'],
							'price' => \Shop\Models\Currency::format($variant['price']) //Might not always be correct - Sweydo 11/18/16
						) ) );
						?>'
						data-wishlist="<?php echo $wishlist_state; ?>"><?php echo $variant['attribute_title'] ? $variant['attribute_title'] : $item->title; ?> 
					</option>
			    <?php } ?>
			</select><br/>
		<?php } elseif (count($item->variants) == 1) { ?>
			<input type="hidden" name="variant_id" value="<?php echo $item->get('variants.0.id'); ?>" class="variant_id" />
		<?php } ?>


        <?php
            if ($item->product_type == 'dynamic_group') {
				if (\Audit::instance()->isMobile()) {
					echo $this->renderView ('Shop/Site/Views::product/blocks/mobile/dynamic_group.php', ['cache'=> 'dynamic_options_mobile.'.$item->id]);
				} else {
					echo $this->renderView ('Shop/Site/Views::product/blocks/desktop/dynamic_group.php', ['cache'=> 'dynamic_options.'.$item->id]);
				}
            }
	
			if (\Dsc\ArrayHelper::get($item->policies, 'ships_email')) {
				echo '<input type="email" name="email" class="form-control" placeholder="Recipient\'s Email Address" required><br />';
			}
        ?>
		<div class="stockStatusContainer"></div>
		
		
		
			<input type="hidden" class="form-control" value="1" placeholder="Quantity" name="quantity" id="quantity" />
			<?php  if ( $item->confirmFitment () !=="noymm" || $item->{'universalpart'} ) { ?>
				<button class="btn btn-warning btn-lg btn-block addToCartButton"<?php if($item->price($item->get('variants.0.id')) < 0.02 || (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1)) echo ' disabled'; ?> data-analytics='<?php echo $item->gaProduct(); ?>'<?php if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) { ?> disabled<?php } ?>>
					<i class="glyphicon glyphicon-shopping-cart"></i> Add To Cart
				</button>
			<?php } else {
				echo $this->renderView ( 'Theme/Views::amp/blocks/noymm.php' );
			} ?>
		
		<?php if (\Base::instance()->get('isFrontCounter') &&  $item->stock()->getInventory() > 0) : ?>
		<div class="alert alert-success text-center"><strong>Available for Will Call</strong></div>
		<?php elseif(\Base::instance()->get('isFrontCounter') &&  $item->stock()->getInventory() <= 0): ?>
		<div class="alert alert-info text-center"><strong>Order Today for Home Delivery</strong></div>
		<?php endif; ?>

		<?php \Dsc\System::instance()->get('session')->set('shop.add_to_cart.product.redirect', '/shop/cart' ); ?>
		</form>

        <?php if($item->{'product_type'} != 'dynamic_group' && !\Dsc\ArrayHelper::get($item->policies, 'ships_email')) : ?>
			<div class="wishListButton text-center paddingTopSm" data-variant="<?php echo $item->variants[0]['id']; ?>">
				<i class="glyphicon glyphicon-refresh spinning"></i>
			</div>
		<?php endif; ?>

</br>