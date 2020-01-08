<div class="cc-danger set_car_toconfirm">
	<a href="/set-vehicle" class="selectCarBtn confirmFitment">Set your car to confirm fitment</a>
</div>
<br/>
<?php if (!empty( $item )) { ?>
<button class="btn btn-warning btn-lg btn-block addToCartButton"<?php if($item->price($item->get('variants.0.id')) < 0.02 || (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1)) echo ' disabled'; ?> data-analytics='<?php echo $item->gaProduct(); ?>'>
					<i class="glyphicon glyphicon-shopping-cart"></i> Add To Cart
				</button>
<?php } ?>	
<?php if (!empty( $product )) { ?>			
						<button class="btn btn-warning btn-lg btn-block addToCartButton"<?php if($item->price($item->get('variants.0.id')) < 0.02 || (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1)) echo ' disabled'; ?> data-analytics='<?php echo $product->gaProduct(); ?>'>
					<i class="glyphicon glyphicon-shopping-cart"></i> Add To Cart
				</button>
<?php } ?>	
