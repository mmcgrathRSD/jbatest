<!-- cart BOF -->
<?php 
//if empty try and fetch the cart.
if(empty($cart)){
    $cart = \Shop\Models\Carts::fetch();
}
?>
<div>
    <a class="header-switch-trigger summary icon-white" href="/shop/cart/">
    <span>My Cart</span><span class="qty"><?php echo $cart->get('quantity'); ?></span>	</a>
    <div class="header-dropdown" style="display: none; opacity: 0;">
    <div class="cart-promotion"><strong>Shopping Cart</strong></div>
    <p class="block-subtitle text-recently">Recently added item(s)</p>
    <ol id="cart-sidebar" class="mini-products-list">
    <?php
    //get the displayCartItems, which groups products if non standard products.
    $lastThreeItems = $cart->displayCartItems(true);
    //get the last three items and reverse the order for display.
    $lastThreeItems = array_reverse(array_splice($lastThreeItems['items'], -3));
    foreach($lastThreeItems as $item){ ?>
    <li class="item clearfix">
        <?php if($item instanceof \Shop\Models\Products){//if the item is an instance of products assume we are dealing with non standard items. ?>
            
            <a href="/part/<?php echo $item->get('slug'); ?>" title="<?php echo "{$item->get('title')} - {$item->get('title_suffix')}"; ?>" class="product-image">
            <img src="<?php echo \Shop\Models\Products::product_thumb($item->get('featured_image.slug')); ?>" alt="<?php echo "{$item->get('title')} - {$item->get('title_suffix')}"; ?>"></a>
            <div class="product-details">
                <form action="/shop/cart/remove-group/<?php echo $item->get('__group_id'); ?>" class="removeFromCartForm">
                    <button class="btn-remove icon-white" onclick="return confirm('Are you sure you would like to remove this item from the shopping cart?');" ></button>
                </form>
                <p class="product-name"><a href="#"><?php echo "{$item->get('title')} - {$item->get('title_suffix')}"; ?></a></p>
                <strong>1</strong> x <span class="price">$<?php echo number_format($item->get('kit_price_with_discount'), 2); ?></span>											
            </div>
        <?php }else{ ?>

            <a href="/part/<?php echo $item['product']['slug']; ?>" title="<?php echo "{$item['product']['title']} - {$item['product']['title_suffix']}"; ?>" class="product-image">
            <img src="<?php echo \Shop\Models\Products::product_thumb($item['image']); ?>" alt="<?php echo "{$item['product']['title']} - {$item['product']['title_suffix']}"; ?>"></a>
            <div class="product-details">
                <form action="/shop/cart/remove/<?php echo $item['hash']; ?>" class="removeFromCartForm">
                    <button class="btn-remove icon-white" onclick="return confirm('Are you sure you would like to remove this item from the shopping cart?');" ></button>
                </form>
                <p class="product-name"><a href="#"><?php echo "{$item['product']['title']} - {$item['product']['title_suffix']}"; ?></a></p>
                <strong><?php echo $item['quantity']; ?></strong> x <span class="price">$<?php echo number_format($item['price'], 2); ?></span>											
            </div>
        <?php } ?>
    </li>
    <?php } ?>
    </ol>
    <div class="subtotal">
        <span class="label">Total:</span> <span class="price">$<?php echo number_format(array_sum(array_map(function($item){ return $item['price'] * $item['quantity'];}, $cart['items'])), 2); ?></span>												
    </div>
    <div class="buttons clearfix">
        <button type="button" title="View Cart" class="button inverted btn-continue" onclick="setLocation('/shop/cart/')"><span><span>View Cart</span></span></button>
        <button type="button" title="Checkout" class="button btn-checkout" onclick="setLocation('/shop/checkout')"><span><span>Checkout</span></span></button>
    </div>
    </div>
</div>
<!-- cart EOF -->