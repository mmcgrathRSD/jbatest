<?php 
$previous_price = $item->getPreviousPrice();
$previous_price_formatted = \Shop\Models\Currency::format($item->getPreviousPrice());
$price = $item->price();
?>
<div class="price-box-wrap">
    <div class="f-left">
        <div class="price-box">
            <span class="regular-price">
            <span class="price"><?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup/price.php')?></span>                                    </span>
            <?php if(!empty($previous_price) && $previous_price < $price && filter_var($item->get('flag.enabled'), FILTER_VALIDATE_BOOLEAN)) : ?>
                <p class="old-price">
                    <span class="price-label"></span>
                    <span class="price" id="old-price-23848"><?php echo $previous_price_formatted; ?></span>
                </p>
            <?php endif; ?>
        </div>
        
        <div class="clear"></div>
        <?php if (!empty($shop['freeshipping']) && $item->price() > $shop['freeshipping']) : ?>
            <h5 style="color:#5bb900;">Ships for free in the 48 states</h5>
        <?php endif; ?>
    </div>
    <div class="f-left">
        <p class="sku">SKU: <span><?php echo $item->tracking['oem_model_number']; ?></span></p>
        <p class="availability ">
            <span>
                <span class="tt">
                
                <span class="top"></span>
                <span class="bottom"></span>
                </span></span> <span class="amstockstatus amsts_501">
                <?php
                if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) {
                    echo '&nbsp;';
                } else {
                    echo $this->renderView('Shop/Site/Views::helpers/stock.php', [
                        'hive' => [
                            'stockProduct' => $item
                        ]
                    ]);
                }
                ?>
                </span>
            </span>
        </p>
    </div>
</div>