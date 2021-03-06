<?php 
$use_previous_price = filter_var($item->get('flag.percent'), FILTER_VALIDATE_BOOLEAN);
$previous_price = $use_previous_price ? $item->getPreviousPrice() : $item->getPriceByChannel('list');
$previous_price_formatted = $use_previous_price ? \Shop\Models\Currency::format($item->getPreviousPrice()) : \Shop\Models\Currency::format($item->getPriceByChannel('list'));
$price = $item->price();
?>
<div class="price-box-wrap">
    <div class="f-left">
        <div class="price-box">
            <span class="regular-price">
                <span class="price <?php if($item->{'product_type'} == 'dynamic_group') : ?>dyn_kit_price<?php endif; ?>"><?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup/price.php')?></span>                                    </span>
            <?php if(!empty($previous_price) && $previous_price > $price && filter_var($item->get('flag.enabled'), FILTER_VALIDATE_BOOLEAN)) : ?>
                <p class="old-price">
                    <span class="price-label"></span>
                    <span class="price" id="old-price-23848"><?php echo $previous_price_formatted; ?></span>
                </p>
            <?php endif; ?>
        </div>
        
        <div class="clear"></div>
        <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup/affirm.php')?>
        <?php if (!empty($shop['freeshipping']) && $item->price() > $shop['freeshipping'] && !\Dsc\ArrayHelper::get($item->policies, 'ships_email')) : ?>
            <h5 style="color:#5bb900;">Ships for free in the 48 states</h5>
        <?php endif; ?>
    </div>
    <?php if(!\Dsc\ArrayHelper::get($item->policies, 'ships_email') & $item->{'product_type'} != 'dynamic_group') : ?>
        <div class="f-left">
            <p class="sku">SKU: <span><?php echo $item->tracking['oem_model_number']; ?></span></p>
            <p class="availability ">
                <span>
                    <span class="amstockstatus amsts_501">
                        <?php 
                        if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1 || $item->{'product_type'} == 'matrix') {
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
    <?php else : ?>
        <div class="stockStatusContainer"></div>
    <?php endif; ?>
</div>