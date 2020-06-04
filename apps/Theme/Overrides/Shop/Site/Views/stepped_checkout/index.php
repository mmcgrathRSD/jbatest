<?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/bt_scripts.htm'); ?>
<div class="checkoutPage">
    <div class="container error_outer_container">
        <div class="error_msg"><strong class="title"><i class="fa fa-exclamation-circle" aria-hidden="true"></i>ERROR</strong>
            <div class="error_body"></div>
            <div class="error_close">Dismiss</div>
        </div>
    </div>
    <div class="loader">
        <div class="circle-loader">
            <div class="checkmark draw"></div>
        </div>
    </div>
    <div class="content-container checkout_body">
        <div class="main row">

            <div class="col-xs-12 checkout_title">
                <h1>Checkout <a href="/shop/cart" class="pull-right edit_cart"><i class="glyphicon glyphicon-shopping-cart"></i> Edit Cart</a></h1>
            </div>
            <div class="col-md-9 col-sm-12 col-xs-12">
                <div class="row steps">
                    
                    
                    
                </div>
                <div class="row steps_index">
                    <div class="col-xs-12">
                        <div class="steps_body">
                            <div class="col-xs-12 shipping jba_steps">
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/shipping.htm'); ?>
                            </div>
                            <div class="shipping_index step_index">
                                <?php $this->app->set('regions', $shipping_regions); ?>
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/shipping_index.htm'); ?>
                                <div>
                                    <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/footer.htm'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 billing jba_steps">
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/billing.htm'); ?>
                            </div>
                            <div class="billing_index step_index">
                                <?php $this->app->set('regions', $billing_regions); ?>
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/billing_index.htm'); ?>
                                <div>
                                    <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/footer.htm'); ?>
                                </div>
                            </div>
                            <div class="col-xs-12 review jba_steps">
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/review.htm'); ?>
                            </div>
                            <div class="review_index step_index">
                                <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/review_index.htm'); ?>
                                <div>
                                    <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/footer.htm'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-12 col-xs-12 ">
                <div class="side_bar_steps">
                    <div class="block-title">
                        <strong><span>Your Checkout Progress</span></strong>
                    </div>
                    <div class="shipping">
                        <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/shipping.htm'); ?>
                    </div>
                    <div class="shipping">
                        <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/billing.htm'); ?>
                    </div>
                    <div class="shipping">
                        <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/steps/review.htm'); ?>
                    </div>
                </div>
                <div class="summary">
                    <?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/summary.htm'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="site-modal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content container">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default paste_address_submit" data-dismiss="modal">Use This Address</button>
                </div>
            </div>


    </div>
</div>
<div class="modal fade" id="site-modal-suggest" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content container">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Address Validation</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default suggest_address_submit" data-dismiss="modal">Dismiss</button>
            </div>
        </div>

    </div>
</div>

<?php echo $this->renderLayout('Shop/Site/Views::stepped_checkout/scripts.htm'); ?>

<script type="text/javascript">
	<?php foreach ($cart->items as $item) { ?>

		ga('ec:addProduct', {            // Provide product details in an impressionFieldObject.
			'id': '<?php echo \Dsc\ArrayHelper::get($item, 'model_number'); ?>',                   // Product ID (string).
			'name': '<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>', // Product name (string).
			'price': '<?php echo $cart->calcItemSubtotal( $item ); ?>',
			'quantity': '<?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?>',
			'category': '<?php echo \Dsc\ArrayHelper::get($item, 'product.categories.0.title'); ?>',   // Product category (string).
			'brand': '<?php echo \Dsc\ArrayHelper::get($item, 'product.manufacturer.title'); ?>',                // Product brand (string).
		});
	<?php } ?>

    ga('ec:setAction','checkout', {'step': 2});
    ga('send', 'event', 'Shopping', 'click', 'checkout page');     // Send data using an event.
</script>

