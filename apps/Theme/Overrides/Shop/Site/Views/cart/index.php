<?php
    echo $this->renderView('Shop/Site/Views::cart/cart_tracking.php');

    $result =  $cart->displayCartItems();
    $active = $this->session->get('activeVehicle'); 
?>
<div class="main row">
    <div class="cart">
    <div class="page-title title-buttons">
        <h1>Shopping Cart</h1>
        <ul class="checkout-types">
            <li>   <button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout icon-white btn-checkout" onclick="window.location='/shop/checkout';"><span><span>Proceed to Checkout</span></span></button></li>
        </ul>
    </div>
    <fieldset>
        <table id="shopping-cart-table" class="data-table cart-table">
            <colgroup>
            <col width="1">
            <col>
            <col width="1">
            <col width="1">
            <col width="1">
            <col width="1">
            </colgroup>
            <thead>
            <tr class="first last">
                <th rowspan="1">&nbsp;</th>
                <th rowspan="1"><span class="nobr">Product Name</span></th>
                <th class="a-center" colspan="1"><span class="nobr">Unit Price</span></th>
                <th rowspan="1" class="a-center">Qty</th>
                <th class="a-center" colspan="1">Subtotal</th>
                <th rowspan="1" class="a-center">&nbsp;</th>
            </tr>
            </thead>
            <tfoot>
            <tr class="first last">
                <td colspan="50" class="a-right last">
                    <button type="button" title="Continue Shopping" class="button inverted btn-continue " onclick="setLocation('/')"><span><span>Continue Shopping</span></span></button>
                    <button type="button" onclick="setLocation('/shop/cart/clear')" title="Clear Shopping Cart" class="button inverted btn-empty" id="empty_cart_button"><span><span>Clear Shopping Cart</span></span></button>
                    <!--[if lt IE 8]>
                    <input type="hidden" id="update_cart_action_container" />
                    <script type="text/javascript">
                        //<![CDATA[
                            Event.observe(window, 'load', function()
                            {
                                // Internet Explorer (lt 8) does not support value attribute in button elements
                                $emptyCartButton = $('empty_cart_button');
                                $cartActionContainer = $('update_cart_action_container');
                                if ($emptyCartButton && $cartActionContainer) {
                                    Event.observe($emptyCartButton, 'click', function()
                                    {
                                        $emptyCartButton.setAttribute('name', 'update_cart_action_temp');
                                        $cartActionContainer.setAttribute('name', 'update_cart_action');
                                        $cartActionContainer.setValue('empty_cart');
                                    });
                                }
                        
                            });
                        //]]>
                    </script>
                    <![endif]-->
                </td>
            </tr>
            </tfoot>
            <tbody>
            <?php if(!empty($result['groups'])) : ?>
                <?php foreach ($result['groups'] as $key => $item) : ?>
                    <tr class="first last odd">
                        <td>
                            <a href="/part/<?php echo $item->slug; ?>" title="<?php echo $item->title(); ?>" class="product-image">
                                <img src="<?php echo $item->featuredImageThumb();?>" width="300" height="300" alt="<?php echo $item->title(); ?>">
                            </a>    
                        </td>
                        <td>
                            <h2 class="product-name">
                                <a href="/part/<?php echo $item->slug; ?>"><?php echo $item->title(); ?></a>
                            </h2>
                            <?php 
                            if (!$assembled) {
                                foreach ($item->getKitOptions() as $kitOption) {
                                    $product = $kitOption->values()[0]->product();
                                    echo ($kitOption->quantity > 1 ? $kitOption->quantity . 'x ' : '1x ') . '<strong>' . $product->modelNumber() . '</strong><br />';
                                }
                            } 
                            ?>
                        </td>
                        <td class="a-center td-price">
                            <span class="td-title">Unit Price</span>
                            <span class="cart-price">
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->kit_price_with_discount ); ?></span>
                            <?php if (!empty($cart->coupons)) : ?>
                                <?php if ( \Dsc\ArrayHelper::get($item, 'discount', 0.00) > 0.00 ) { ?>
                                    <div>
                                        <span class="discount"><small><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'discount') ); ?> discount from coupon applied</small></span>
                                    </div>
                                <?php  } else { ?>
                                    <div>
                                        <span class="discount"><small class="coupon">Item is not eligible for discount</small></span>
                                    </div>
                                <?php } ?>
                            <?php endif; ?>
                            </span>
                        </td>
                        <td class="a-center td-qty">
                            <div class="qty-container">
                                1
                                <label for="qty" class="td-title">Quantity</label>
                            </div>
                        </td>
                        <td class="a-center td-price">
                            <span class="td-title">Subtotal</span>
                            <span class="cart-price">
                            <span class="price"><?php echo \Shop\Models\Currency::format( $item->kit_price_with_discount ); ?></span>                            
                            </span>
                        </td>
                        <td class="a-center last">
                            <span class="td-title v-top">Actions</span>
                            <span class="nobr">
                                <a 
                                    href="./shop/cart/remove-group/<?php echo (string) \Dsc\ArrayHelper::get($item, '__group_id'); ?>" 
                                    data-analytics='<?php echo \Dsc\ArrayHelper::get($item, ' model_number '); ?>' 
                                    title="Remove item" 
                                    class="btn-remove btn-remove2 icon-white"
                                    >Remove item</a>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php foreach ($result['items'] as $key => $item) : $product = (new \Shop\Models\Products)->load(['_id' => $item['product_id']]); ?>
            <tr class="first last odd">
                <td>
                    <a href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>" title="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>" class="product-image">		<img src="<?php echo \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get($item, 'image'));?>" width="300" height="300" alt="<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>">
                    </a>    
                </td>
                <td>
                    <h2 class="product-name">
                        <a href="/part/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>"><?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?></a>
                    </h2>
                    <?php if (\Dsc\ArrayHelper::get($item, 'attribute_title')) { ?>
                        <small><?php echo \Dsc\ArrayHelper::get($item, 'attribute_title'); ?></small>
                    <?php } ?>
                </td>
                <td class="a-center td-price">
                    <span class="td-title">Unit Price</span>
                    <span class="cart-price">
                    <span class="price"><?php echo \Shop\Models\Currency::format($product->price()); ?></span>                
                    </span>
                    <?php if (!empty($cart->coupons)) : ?>
                        <?php if ( \Dsc\ArrayHelper::get($item, 'discount', 0.00) > 0.00 ) { ?>
                            <div>
                                <span class="discount"><small><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'discount') ); ?> discount from coupon applied</small></span>
                            </div>
                        <?php  } else { ?>
                            <div>
                                <span class="discount"><small class="coupon">Item is not eligible for discount</small></span>
                            </div>
                        <?php } ?>
                    <?php endif; ?>
                </td>
                <td class="a-center td-qty">
                    <div class="qty-container">
                    <?php if(empty(\Dsc\ArrayHelper::get($item, 'promotional_item'))) : ?>
                        <?php if(!\Dsc\ArrayHelper::get($product->policies, 'ships_email')) : ?>
                            <?php if(\Dsc\ArrayHelper::get($item, 'quantity') <= 9) : ?>
                                <select  data-hash="<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>"  class="fgBlack bgWhite cartUpdateQuantities cartUpdateOnChange" id="quantities-<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" name="quantities[<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>]" <?php echo \Dsc\ArrayHelper::get($item, 'model_number') == 'RSD 50101' ? 'disabled' : '' ?>>
                                    <?php for ($i = 1; $i < 11; $i++) { ?>
                                        <option class="fgBlack bgWhite" value="<?php echo $i; ?>" <?php if ($i == \Dsc\ArrayHelper::get($item, 'quantity')) { echo "selected=selected"; } ?>><?php echo $i == 10 ? $i. "+" : $i; ?></option>
                                    <?php } ?>
                                </select>
                            <?php else : ?>
                            <form>
                                <div class="updateCartQtyNumber">
                                <input style="height: 29px;" type="number" data-hash="<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>"  class="cartUpdateQuantities" id="quantities-<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" value="<?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?>" />
                                <button class="button inverted updateCartQtyButton"><span><span>Update</span></span></button>
                                </div>
                            </form>
                            <?php endif; ?>
                            <br class="clearfix" />
                        <?php endif; ?>
                    <?php else : ?>
                        <?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?>
                    <?php endif ; ?>
                    </div>
                </td>
                <td class="a-center td-price">
                    <span class="td-title">Subtotal</span>
                    <span class="cart-price">
                    <span class="price"><?php echo \Shop\Models\Currency::format( $cart->calcItemSubtotal( $item )); ?></span>                            
                    </span>
                </td>
                <td class="a-center last">
                    <span class="td-title v-top">Actions</span>
                    <span class="nobr">
                    <a href="./shop/cart/remove/<?php echo \Dsc\ArrayHelper::get($item, 'hash'); ?>" title="Remove item" class="btn-remove btn-remove2 icon-white">Remove item</a>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <script type="text/javascript">decorateTable('shopping-cart-table')</script>
    </fieldset>
    <div class="row cart-collaterals">
        <div class="grid_18 top-line"></div>
            <?php echo $this->renderView('Shop/Site/Views::cart/blocks/estimate_shipping.php')?>
        <div class="grid_6">
            <?php if (empty($cart->userCoupons())) : \Dsc\System::instance()->get( 'session' )->set( 'site.addcoupon.redirect', '/shop/cart' ); ?>
                <div class="block block_coupon">
                    <form role="form" action="./shop/cart/addCoupon" method="post">
                        <div class="discount">
                            <h2>Discount Codes</h2>
                            <div class="discount-form">
                                <label for="coupon_code">Enter your coupon code if you have one.</label>
                                <input type="hidden" name="remove" id="remove-coupone" value="0">
                                <div class="input-box">
                                    <input class="input-text" id="inputCouponCode" name="coupon_code" value="">
                                </div>
                                <div class="buttons-set">
                                    <button type="submit" title="Apply Coupon" class="button couponSubmit" value="Apply Coupon"><span><span>Apply Coupon</span></span></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php endif; //enter coupon ?>
        </div>
        <div class="grid_6">
            <div class="block block_totals clearfix">
                <div class="block_totals_indent">
                <table id="shopping-cart-totals-table">
                    <colgroup>
                        <col>
                        <col width="1">
                    </colgroup>
                    <tfoot>
                        <tr>
                            <td style="" class="a-right" colspan="1">
                            <strong>Subtotal</strong>
                            </td>
                            <td style="" class="a-right">
                            <strong><span class="price"><?php echo \Shop\Models\Currency::format($cart->subtotal(true)); ?></span></strong>
                            </td>
                        </tr>
                    </tfoot>
                    <!-- <tbody>
                        <tr>
                            <td style="" class="a-right" colspan="1">
                            Subtotal    
                            </td>
                            <td style="" class="a-right">
                            <span class="price">$21.85</span>    
                            </td>
                        </tr>
                    </tbody> -->
                </table>
                </div>
                <ul class="checkout-types">
                    <li>
                        <button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout icon-white btn-checkout" onclick="window.location='/shop/checkout';"><span><span>Proceed to Checkout</span></span></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="cart-collaterals">
    </div>
    </div>
</div>