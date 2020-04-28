
<!-- cart BOF -->
<?php 
if(empty($cart)){
    $cart = \Shop\Models\Carts::fetch();
}
?>
<div id="my-cart">
    <a class="header-switch-trigger summary icon-white" href="/shop/cart/">
    <span>My Cart</span><span class="qty"><?php echo count($cart->get('items')); ?></span>	</a>
    <div class="header-dropdown" style="display: none; opacity: 0;">
    <div class="cart-promotion"><strong>Shopping Cart</strong></div>
    <p class="block-subtitle text-recently">Recently added item(s)</p>
    <ol id="cart-sidebar" class="mini-products-list">
    <?php
        $lastThreeItems = array_reverse(array_map(function($item){
        return [
            'slug' => $item['product']['slug'],//or this?
            'hash' => $item['hash'],//or this?
            'price' => $item['price'],
            'quantity' => $item['quantity'],
            'model_number' => $item['model_number'],
            'title' => $item['product']['title'],
            'title_suffix' => $item['product']['title_suffix'],//umm or this as well?
            'image' => \Shop\Models\Products::product_thumb($item['image']),
        ];
    }, array_splice($cart->get('items'), -3)));
    foreach($lastThreeItems as $item){ ?>
    <li class="item clearfix">
    
            <a href="/part/<?php echo $item['slug']; ?>" title="<?php echo "{$item['title']} - {$item['title_suffix']}"; ?>" class="product-image">
            <img src="<?php echo \Shop\Models\Products::product_thumb($item['image']); ?>" alt="<?php echo "{$item['title']} - {$item['title_suffix']}"; ?>"></a>
            <div class="product-details">
                <a href="/shop/cart/remove/<?php echo $item['hash']; ?>" title="Remove This Item" onclick="return confirm('Are you sure you would like to remove this item from the shopping cart?');" class="btn-remove icon-white">Remove This Item</a>
                <a href="/part/<?php echo $item['slug']; ?>" title="Edit item" class="btn-edit icon-white">Edit item</a>
                <p class="product-name"><a href="#"><?php echo "{$item['title']} - {$item['product']['title_suffix']}"; ?></a></p>
                <strong><?php echo $item['quantity']; ?></strong> x <span class="price">$<?php echo number_format($item['price'], 2); ?></span>											
            </div>
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