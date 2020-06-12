<?php
    $identity = $this->getIdentity();
    $wishlist = \Shop\Models\Wishlists::fetch();
    $wishlistItems = array_slice((array) $wishlist->items, -3, 3);
    $wishlistCount = count($wishlistItems);
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
    <!-- banner slider BOF -->
    <div id="banners_slider_sidebar" class="banners-slider-container icon-white">
        <div id="banners_slider_sidebar_list_nav" class="nav">
            <a class="prev" href="#">&nbsp;</a>
            <a class="next" href="#">&nbsp;</a>
        </div>
        <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
            <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
                <ul id="banners_slider_sidebar_list" class="banners jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: -696px; width: 1028px;">
                    <li class="slide37 jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                        <a href="/subispeed-gift-card-gift-certificate">
                            <span class="text-container top-left" style="display: inline;">
                                <div class="animation-wrapper animation-text" data-width="123" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">The Gift
                                    </span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="162" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">That Keeps
                                    </span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="108" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Giving</span></div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-link" data-width="86" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Check it out</em></div>
                            </span>
                            <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/Athlete-Banners-232x368-Subi-GC.jpg" alt="" width="232" height="368">
                        </a>
                    </li>
                    <li class="slide33 jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" jcarouselindex="2" style="float: left; list-style: none;">
                        <a href="/subispeed-sequential-full-led-headlights-2015-wrx-2015-sti#.WRnaDPkrKUk">
                            <span class="text-container top-left" style="display: inline;">
                                <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">SubiSpeed 
                                    </span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="160" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Headlight</span></div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-link" data-width="79" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Order now</em></div>
                            </span>
                            <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/subispeedheadlight.jpg" alt="" width="232" height="368">
                        </a>
                    </li>
                    <li class="slide30 jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" jcarouselindex="3" style="float: left; list-style: none;">
                        <a href="/2015-wrx-sti-top-modifications">
                            <span class="text-container top-left" style="display: inline;">
                                <div class="animation-wrapper animation-text" data-width="78" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">WRX 
                                    </span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="150" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Top Mods</span></div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-link" data-width="76" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Click here</em></div>
                            </span>
                            <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/youtubechannel.jpg" alt="" width="232" height="368">
                        </a>
                    </li>
                    <li class="slide33 jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" jcarouselindex="5" style="float: left; list-style: none;">
                        <a href="/subispeed-sequential-full-led-headlights-2015-wrx-2015-sti#.WRnaDPkrKUk">
                            <span class="text-container top-left" style="display: inline;">
                                <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 149px; height: 38px;"><span class="text" style="">SubiSpeed 
                                    </span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="160" data-height="38" style="width: 160px; height: 38px;"><span class="text" style="">Headlight</span></div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-link" data-width="79" data-height="22" style="width: 79px; height: 22px;"><em class="link" style="">Order now</em></div>
                            </span>
                            <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/subispeedheadlight.jpg" alt="" width="232" height="368">
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php if ($wishlistCount): ?>
        <!-- banner slider EOF -->
        <div class="block block-wishlist">
            <div class="block-title">
                <strong><span>My Wishlist <small>(<?php echo count($wishlistItems); ?>)</small></span></strong>
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