<?php if (empty($order->id)) { ?>
    <h1>Order not found. <a href="./shop"><small>Go Shopping</small></a></h1>
<?php } else { ?>
    <div class="main row">
        <div class="col-main grid_13 custom_right">
            <div class="page-title">
                <h1>Your order has been received.</h1>
            </div>
            <h2 class="sub-title">Thank you for your purchase!</h2>
            <p>Your order # is: <?php echo $order->number; ?>.</p>
            <p>You will receive an order confirmation email with details of your order and a link to track its progress.</p>
            <div class="buttons-set">
                <button type="button" class="button" title="Continue Shopping" onclick="window.location='/'"><span><span>Continue Shopping</span></span></button>
            </div>
        </div>
        <div class="col-right sidebar grid_5 custom_right"></div>
    </div>

    <?php
        $this->order = $order;
        $settings = \Shop\Models\Settings::fetch();
    
        /* ?> [tracking pixels] */
        echo $this->renderView('Shop/Site/Views::confirmation/tracking_custom.php');

        if ($settings->{'order_confirmation.gtm.enabled'} == 1 && empty(\Base::instance()->get('disable_order_tracking', false))) {
            echo $this->renderView('Shop/Site/Views::confirmation/tracking_gtm.php');
        }
    ?>

    <tmpl type="modules" name="confirmation-end" />
<?php } ?>
