<?php
$addClass = '';
$originalPrice = '';
$savings = false;
$zeroDollars = '';
$price = $item->price();
$richPrice = $item->price();

//strike price if flag hide_price
if ($item->{'policies.hide_price'}) {
    $price = \Shop\Models\Currency::unformat( $item->getPriceByChannel('list'));
    $addClass .= ' priceStrike';
}

//get kit original and most appropriate price
if ($item->{'product_type'} == 'dynamic_group') {
    $originalPrice = $item->price();
    if(!$item->hasIncludedItems()) {
        $addClass .= ' priceAsLowAs';
        $zeroDollars = '<br /><div class="zero_dollars">$0.00 </div>';
    } else {
        if ($item->isConfigurable()) {
            $addClass = $addClass.' priceConfigurable';
        }
        $price = $originalPrice;
    }
} elseif ($item->get('flag.enabled') &&  strtolower($item->get('flag.text')) != 'new') { //product on sale but don't flag new item as onsale
    $addClass .= ' priceSale';
}
//var_dump($item->get('flag.enabled')); die;
$richPrice = $price;
$priceInCents = $price*100;
if(!empty($zeroDollars)) {
    $priceInCents = 0;
}

$price = \Shop\Models\Currency::format($price);

if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) {
    $zeroDollars = '';
    $price = $item->getVariantPriceRange();

}

//strike price if flag hide_price
if ($item->{'policies.hide_price'}) {
    $richPrice = 0.00;
    echo $this->renderView ( 'Shop/Site/Views::product/blocks/testing/price.php' );
    }

//savings section
if ((float)$originalPrice - (float) $richPrice >= 0.01) {
    $savings = \Shop\Models\Currency::format($originalPrice - $richPrice);
}

$list = $item->getPriceByChannel('list');
$map = $item->getPriceByChannel('map');
$jobber = $item->getPriceByChannel('jobber');

?>
<div class="affirm-parent" >
	<span itemprop="url" content="<?php echo $item->generateStandardURL(true)?>" class="hidden"></span>
    <span itemprop="priceCurrency" content="USD" class="hidden">$</span>
    <span itemprop="price" content="<?php echo $richPrice; ?>" class="hidden"><?php echo $richPrice; ?></span>
        <div class="product-price">
            <?php if(!$grouponly) : ?>
                <div class="price_container">
                    <div class="price_command"><div class="price_actual <?php echo $addClass; ?>" data-id="cost"><?php echo $price; ?></div></div><?php echo $zeroDollars; ?>
                    <div class="price_extras">
                        <?php if(!empty($item->saleFlagText())) : ?><div class="price_discount"><?php echo $item->saleFlagText(); ?></div><?php endif; ?><?php if($item->get('product_type') == 'dynamic_group') : ?><div class="price_suplimental">(Price as configured)</div><?php endif; ?>
                    </div>

                    <?php if (!$item->{'policies.is_giftcard'} && $item->price() < $item->get('prices.list') && $item->get('product_type') != 'dynamic_group'): ?>
                        <?php if (((int) $item->get('prices.list') > 0) && (float) $item->get('prices.list') != (float) $item->price() ) { ?>
                            <div class="price_msrp">msrp: <?php echo \Shop\Models\Currency::format( $item->getPriceByChannel('list') ); ?></div>
                        <?php } ?>
                    <?php endif; ?>
                </div>
                <div id="groupSavings"<?php if(empty($savings)) : ?>style="display: none;"<?php endif; ?>>
                    <span>Bundle Savings: <?php echo $savings; ?></span>
                </div>
            <?php endif;?>
        </div>
</div>
