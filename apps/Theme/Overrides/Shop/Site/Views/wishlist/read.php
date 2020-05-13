<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="dashboard">
                <div class="page-title">
                    <h1>My Wishlist</h1>
                </div>
                <?php if (empty($wishlist->items)) :  ?>
                <h2>
                    Your wishlist is empty!</a>
                </h2>
                <?php else : ?>
                <div class="" style="margin-bottom: 20px;">
                    <div class="" id="wishListEmailBox">
                        <div id="SharableBox" style="float: right">
                            <div class="fb-share-button" data-href="<?php echo $wishlist->getShareUrl(); ?>" data-layout="button" data-size="large" data-mobile-iframe="true">
                                <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($wishlist->getShareUrl()); ?>&amp;src=sdkpreparse" class="fb-xfbml-parse-ignore">Share</a>
                            </div>

                        </div>
                    </div>
                    <form class="form-inline" action="/shop/wishlist/send" method="POST" id="wishListEmail">
                        <input type='hidden' id='captchatoken' name='captchatoken'/>
                        <div class="form-group">
                            <label for="exampleInputName2">Email Wishlist:&nbsp;&nbsp;</label>
                            <input type="email" name="email" required class="form-control input-text" style="margin-right: 5px;" id="email" no-validation="true" placeholder="Email"><button type="submit" class="button btn-submit"><span><span>Send Wishlist</span></span></button>
                        </div>
                        
                    </form>
                    </div>
                    <div class="" id="wishListEmailBox">
                        <div class="form-inline">
                        <div class="form-group">
                            <label for="exampleInputName2">Share Wishlist:&nbsp;&nbsp;</label>
                            <input type="text" name="" class="form-control clipboard_link_wishlist" value="<?php echo urldecode($wishlist->getShareUrl()); ?>">
                        </div>
                        <button class="button btn-submit btn_clipboard" data-clipboard-text="<?php echo urldecode($wishlist->getShareUrl()); ?>">
                            <span><span>Copy link to clipboard</span></span>
                        </button>
                    </div>
                </div>
                <hr>
                <table class="data-table" id="wishlist-table">
                <thead>
                        <tr class="first last">
                                            <th></th>
                                            <th>Product Details</th>
                                            <th>Add to Cart</th>
                                            <th></th>
                                    </tr>
                    </thead>
                    <tbody>
                <?php foreach($wishlist->items as $item) { ?>
                    <?php $product = $wishlist->product( $item ); ?>
                    <tr class="first last odd">
                        <td><a class="product-image" href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>" title="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>">
                            
                            <?php if (\Dsc\ArrayHelper::get($item, 'image')) { ?>
                                        <img  class="img-responsive" src="<?php echo  \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get($item, 'image'));?>" alt="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" width="113" height="113"/>
                            <?php } else { ?>
                                <img  class="img-responsive" src="<?php echo  \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get(null, 'image'));?>" alt="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" width="113" height="113"/>
                            <?php } ?>
                            </a>
                        </td>
                        <td>
                            <h3 class="product-name"><a href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>" title="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?></a></h3>
                            <div class="description std">
                                <div class="inner"><?php echo \Dsc\ArrayHelper::get($item, 'product.short_description'); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="cart-cell">
                                <div class="price-box">
                                    <p class="special-price">
                                    <span class="price-label"></span>
                                    <span class="price" id="product-price-23525">
                                    <?php echo \Shop\Models\Currency::format($product->price()); ?></span>
                                    </p>
                                </div>
                                <div class="add-to-cart-alt td-qty">
                                    <a href="./shop/wishlist/<?php echo $wishlist->id; ?>/cart/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" title="Add to Cart" class="jba-button-style"><button type="button" title="Add to Cart" class="button btn-cart"><span><span>Add to Cart</span></span></button></a>
                                </div>
                            </div>
                        </td>
                        <td class="last"><span class="nobr">
                            <a href="./shop/wishlist/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" class="btn-remove btn-remove2 icon-white" title="Remove Item">
                            Remove item</a>
                            <span>
                            </span></span>
                        </td>
                        </tr>
                    <div class="clearfix">
                    
                    </div>
                <?php } ?>
                </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>

<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.1&appId=151320871669752&autoLogAppEvents=1';
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.5.12/clipboard.min.js"></script>
<script>
var clip = new Clipboard('.btn_clipboard');

clip.on("success", function() {
    if($('.clipboard_success').length == 0) {
        $('.btn_clipboard').after('<div class="clipboard_success" style="color: green;">Wishlist share link copied to clipboard!</div>');
    }
});
clip.on("error", function() {
    if($('.clipboard_error').length == 0) {
        $('.btn_clipboard').after('<div class="clipboard_error" style="color: red;">There was a problem copying link to clipboard.</div>');
    }
});
</script>
<script src="https://www.google.com/recaptcha/api.js?render=<?=$captcha_site_key?>"></script>
<script>
grecaptcha.ready(function() {
  grecaptcha.execute('<?=$captcha_site_key?>', {action: 'wishlist'}).then(function(token) {
    $("#captchatoken").val(token);
  });
});
</script>
