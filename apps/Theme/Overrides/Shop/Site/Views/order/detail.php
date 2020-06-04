<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="page-title title-buttons">
                <h1>Order #<?php echo \Dsc\ArrayHelper::get($order, 'objectID'); ?> - <?php echo \Dsc\ArrayHelper::get($order, 'status'); ?></h1>
                <?php if(!empty(\Dsc\ArrayHelper::get($order, 'tranId'))) : ?>
                    <div class="print-wrap">
                        <strong>SO#: <?php echo \Dsc\ArrayHelper::get($order, 'tranId'); ?></strong>
                        <span class="separator">|</span>
                        <a href="<?php echo \Dsc\ArrayHelper::get($order, 'pdf_link'); ?>" class="link-print" onclick="this.target='_blank';">Print Order</a>
                    </div>
                <?php endif; ?>
                <?php foreach((array) \Dsc\ArrayHelper::get($order, 'invoices') as $invoice) : ?>
                    <div class="print-wrap">
                        <strong>Invoice#: <?php echo $invoice['tranId']; ?></strong>
                        <span class="separator">|</span>
                        <a href="<?php echo $invoice['pdf_link']; ?>" class="link-print" onclick="this.target='_blank';">Print Invoice</a>
                    </div>
                <?php endforeach; ?>
            </div>
            <dl class="order-info">
                <dt>About This Order:</dt>
            </dl>
            <?php $date = \Dsc\ArrayHelper::get($order, 'created_utc'); ?>
            <p class="order-date">Order Date: <?php echo (new \DateTime("@$date"))->format('F j, Y'); ?></p>
            <div class="col2-set order-info-box">
                <div class="col-1">
                    <div class="box">
                        <div class="box-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="box-content">
                            <address><?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.name'); ?><br>
                            <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.line_1'); ?><br>
                            <?php if(\Dsc\ArrayHelper::get($order, 'shipping_address.line_2')) { echo \Dsc\ArrayHelper::get($order, 'shipping_address.line_2') . '<br />'; } ?>
                            <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.city'); ?>,  <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.region'); ?>
                            <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.postal_code'); ?>, <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.country'); ?><br>
                                T: <?php echo \Dsc\ArrayHelper::get($order, 'shipping_address.phone_number'); ?>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="box">
                        <div class="box-title">
                            <h2>Tracking Information</h2>
                        </div>
                        <div class="box-content">
                        <?php
                        foreach((array) \Dsc\ArrayHelper::get($order, 'tracking_numbers') as $trackingNumber) {
                            switch($trackingNumber['provider']) {
                                case 'ups':
                                    echo '<a href="http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=' . $trackingNumber['number'] . '" target="_blank">' . $trackingNumber['number'] . "</a><br>";
                                    break;
                                case 'fedex':
                                    echo '<a href="http://www.fedex.com/Tracking?action=track&tracknumbers=' . $trackingNumber['number'] . '" target="_blank">' . $trackingNumber['number'] . "</a><br>";
                                    break;
                                case 'usps':
                                    echo '<a href="http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' . $trackingNumber['number'] . '" target="_blank">' . $trackingNumber['number'] . "</a><br>";
                                    break;
                            }
                        }  
                         ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col2-set order-info-box">
                <div class="col-1">
                    <div class="box">
                        <div class="box-title">
                            <h2>Billing Address</h2>
                        </div>
                        <div class="box-content">
                        <address><?php echo \Dsc\ArrayHelper::get($order, 'billing_address.name'); ?><br>
                            <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.line_1'); ?><br>
                            <?php if(\Dsc\ArrayHelper::get($order, 'billing_address.line_2')) { echo \Dsc\ArrayHelper::get($order, 'billing_address.line_2') . '<br />'; } ?>
                            <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.city'); ?>,  <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.region'); ?>
                            <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.postal_code'); ?>, <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.country'); ?><br>
                                T: <?php echo \Dsc\ArrayHelper::get($order, 'billing_address.phone_number'); ?>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <div class="box box-payment">
                        <div class="box-title">
                            <h2>Payment Method</h2>
                        </div>
                        <div class="box-content">
                            <p><strong><?php echo \Dsc\ArrayHelper::get($order, 'terms'); ?></strong></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-items order-details">
                <h2 class="table-caption">Items Ordered</h2>
                <table class="data-table" id="my-orders-table" summary="Items Ordered">
                    <colgroup>
                        <col>
                        <col width="1">
                        <col width="1">
                        <col width="1">
                        <col width="1">
                    </colgroup>
                    <thead>
                        <tr class="first last">
                            <th>Product Name</th>
                            <th>SKU</th>
                            <th class="a-right">Price</th>
                            <th class="a-center">Qty</th>
                            <th class="a-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="subtotal first">
                            <td colspan="4" class="a-right">
                                Subtotal                    
                            </td>
                            <td class="last a-right">
                                <span class="price">$<?php echo \Dsc\ArrayHelper::get($order, 'sub_total'); ?></span>                    
                            </td>
                        </tr>
                        <tr class="shipping">
                            <td colspan="4" class="a-right">
                                Shipping &amp; Handling                    
                            </td>
                            <td class="last a-right">
                                <span class="price">$<?php echo \Dsc\ArrayHelper::get($order, 'shipping_total'); ?></span>                    
                            </td>
                        </tr>
                        <tr class="grand_total">
                            <td colspan="4" class="a-right">
                                <strong>Grand Total (Excl.Tax)</strong>
                            </td>
                            <td class="last a-right">
                                <strong><span class="price">$<?php echo (\Dsc\ArrayHelper::get($order, 'total') - \Dsc\ArrayHelper::get($order, 'tax_total')); ?></span></strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="a-right">
                                Tax            
                            </td>
                            <td class="last a-right"><span class="price">$<?php echo \Dsc\ArrayHelper::get($order, 'tax_total'); ?></span></td>
                        </tr>
                        <tr class="grand_total_incl last">
                            <td colspan="4" class="a-right">
                                <strong>Grand Total (Incl.Tax)</strong>
                            </td>
                            <td class="last a-right">
                                <strong><span class="price">$<?php echo \Dsc\ArrayHelper::get($order, 'total'); ?></span></strong>
                            </td>
                        </tr>
                    </tfoot>
                    <?php foreach((array) \Dsc\ArrayHelper::get($order, 'invoices') as $invoice) : ?>
                        <?php foreach((array) $invoice['items'] as $item) : ?>
                            <tbody class="odd">
                                <tr class="border first" id="order-item-row-1315200">
                                    <td>
                                        <h3 class="product-name"><?php echo $item['title']; ?></h3>
                                    </td>
                                    <td><?php echo $item['model_number']; ?></td>
                                    <td class="a-right">
                                        <span class="price-excl-tax">
                                        <span class="cart-price">
                                        <span class="price">$<?php echo $item['price']; ?></span>                    
                                        </span>
                                        </span>
                                        <br>
                                    </td>
                                    <td class="a-right">
                                        <span class="nobr">
                                        <strong><?php echo $item['quantity']; ?></strong><br>
                                        </span>
                                    </td>
                                    <td class="a-right last">
                                        <span class="price-excl-tax">
                                        <span class="cart-price">
                                        <span class="price">$<?php echo $item['extended_price']; ?></span>                    
                                        </span>
                                        </span>
                                        <br>
                                    </td>
                                    <!--
                                        <th class="a-right"><span class="price">$840.75</span></th>
                                            -->
                                </tr>
                            </tbody>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    <?php foreach((array) \Dsc\ArrayHelper::get($order, 'unfulfilled_items') as $item) : ?>
                        <tbody class="odd">
                            <tr class="border first" id="order-item-row-1315200">
                                <td>
                                    <h3 class="product-name"><?php echo $item['title']; ?></h3>
                                </td>
                                <td><?php echo $item['model_number']; ?></td>
                                <td class="a-right">
                                    <span class="price-excl-tax">
                                    <span class="cart-price">
                                    <span class="price">$<?php echo $item['price']; ?></span>                    
                                    </span>
                                    </span>
                                    <br>
                                </td>
                                <td class="a-right">
                                    <span class="nobr">
                                    <strong><?php echo $item['quantity']; ?></strong><br>
                                    </span>
                                </td>
                                <td class="a-right last">
                                    <span class="price-excl-tax">
                                    <span class="cart-price">
                                    <span class="price">$<?php echo $item['extended_price']; ?></span>                    
                                    </span>
                                    </span>
                                    <br>
                                </td>
                                <!--
                                    <th class="a-right"><span class="price">$840.75</span></th>
                                        -->
                            </tr>
                        </tbody>
                    <?php endforeach; ?>
                </table>
                <div class="buttons-set">
                    <p class="back-link"><a href="/shop/orders"><small>« </small>Back to My Orders</a></p>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>