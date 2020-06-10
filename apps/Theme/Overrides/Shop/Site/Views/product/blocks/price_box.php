<div class="price-box-wrap">
    <div class="f-left">
        <div class="price-box">
            <span class="regular-price" id="product-price-13193">
            <span class="price"><?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup/price.php')?></span>                                    </span>
        </div>
    </div>
    <div class="f-left">
        <p class="sku">SKU: <span><?php echo $item->tracking['model_number']; ?></span></p>
        <p class="availability in-stock">
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