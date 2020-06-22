<?php $wishlist = $item; ?>
<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="dashboard">
                <div class="page-title">
                    <h1><?php echo !empty($user->get('profile.screen_name')) ? $user->get('profile.screen_name') . "'s" : 'Shared' ?> Wishlist</h1>
                </div>
                <?php if (empty($wishlist->items)) :  ?>
                <h2>
                    Wishlist is empty!</a>
                </h2>
                <?php else : ?>
                
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
                                <div class="inner"><?php echo \Dsc\ArrayHelper::get($item, 'product.description'); ?></div>
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

                                <form action="./shop/cart/add" method="post" class="addToCartForm">
                                    <input type="hidden" name="model_number" value="<?php echo $item['model_number']; ?>" class="variant_id" />
                                    <input type="hidden" class="form-control" value="1" placeholder="Quantity" name="quantity" id="quantity" />
                                    <button type="submit" title="Add to Cart" class="button btn-cart" product_name="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>"><span><span>Add to Cart</span></span></button>
                                </form>
                            </div>
                        </td>
                    <div class="clearfix">
                    
                    </div>
                <?php } ?>
                </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
