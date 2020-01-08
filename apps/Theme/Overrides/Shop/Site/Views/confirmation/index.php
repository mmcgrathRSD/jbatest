
    <?php if (empty($order->id)) { ?>
        <h1>Order not found. <a href="./shop"><small>Go Shopping</small></a></h1>
    <?php } else { ?>

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-9">
                <h1>
                    Thank you for your order!<br/>
                    <small>Your order number is <span id="HEYJASON"><?php echo $order->number; ?></span>.</small>
                </h1>
                <p>You will receive an email confirmation shortly at <b><?php echo $order->user_email; ?></b></p>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3">
            </div>
        </div>

        <?php
        $this->order = $order;
        $settings = \Shop\Models\Settings::fetch();
        ?>

        <?php /* ?> [tracking pixels] */ ?>
        <?php echo $this->renderView('Shop/Site/Views::confirmation/tracking_custom.php'); ?>



        <?php
        if ($settings->{'order_confirmation.gtm.enabled'} == 1 && empty(\Base::instance()->get('disable_order_tracking', false)))
        {
        	echo $this->renderView('Shop/Site/Views::confirmation/tracking_gtm.php');
        }
        echo $this->renderView('Shop/Site/Views::confirmation/facebook_pixel.php');
        ?>

		<tmpl type="modules" name="confirmation-end" />

        <?php /* ?>
        <p>
        [upsells of related products]
        </p>
        */ ?>

        <?php /* ?>
        <p>
        [upsells with "false urgency"]
        </p>
        */ ?>

        <?php /* ?>
        <p>
        [newsletter signup w/one-click]
        </p>
        */ ?>

    <?php } ?>
