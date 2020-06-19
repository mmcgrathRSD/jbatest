<?php
    $identity = $this->getIdentity();
    $wishlist = \Shop\Models\Wishlists::fetch();
    $wishlistItems = array_slice((array) $wishlist->items, -3, 3);
?>
<div class="col-left sidebar grid_5 custom_left">
    <div class="block block-account">
        <div class="block-title">
            <strong><span>My Account</span></strong>
        </div>
        <div class="block-content">
            <ul>
                <li><a href="/shop/account">Account Dashboard</a></li>
                <li><a href="/shop/account/information">Account Information</a></li>
                <li><a href="/shop/orders">My Orders</a></li>
                <li><a href="/profiles/<?php echo $identity->id; ?>">My Product Reviews</a></li>
                <li><a href="/shop/wishlist">My Wishlist</a></li>
                <li><a href="/shop/giftcards/balance">Gift Card</a></li>
            </ul>
        </div>
    </div>
    <tmpl type="modules" name="account-page-sidebar" />

    <?php if (count($wishlistItems)): ?>
        <div class="block block-wishlist">
            <div class="block-title">
                <strong><span>My Wishlist <small>(<?php echo count((array) $wishlist->items); ?>)</small></span></strong>
            </div>
            <div class="block-content">
                <p class="block-subtitle">Last Added Items</p>
                <ol class="mini-products-list" id="wishlist-sidebar">
                    <?php foreach($wishlistItems as $item) : ?>
                        <?php $product = $wishlist->product( $item ); ?>
                        <li class="item clearfix last odd">
                            <a class="product-image" href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>" title="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>">
                                <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                    <img  class="img-responsive" src="<?php echo  \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get($item, 'image'));?>" alt="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" width="50"/>
                                <?php } else { ?>
                                    <img  class="img-responsive" src="<?php echo  \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get(null, 'image'));?>" alt="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" width="50"/>
                                <?php } ?>
                            </a>

                            <div class="product-details">
                                <p class="product-name"><a href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?></a></p>
                                <a href="./shop/wishlist/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" title="Remove This Item" onclick="return confirm('Are you sure you would like to remove this item from the wishlist?');" class="btn-remove icon-white">Remove This Item</a>
                                <div class="price-box">
                                    <p class="special-price">
                                        <span class="price-label"></span>
                                        <span class="price" id="product-price-23525-wishlist">
                                            <?php echo \Shop\Models\Currency::format($product->price()); ?>
                                        </span>
                                    </p>
                                </div>
                                <a href="./shop/wishlist/<?php echo $wishlist->id; ?>/cart/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="link-cart">Add to Cart</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ol>
                <script type="text/javascript">decorateList('wishlist-sidebar');</script>
                <div class="actions">
                    <a href="/shop/wishlist/">Go to Wishlist</a>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>