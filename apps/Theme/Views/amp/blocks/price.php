<?php
$addClass = '';
$originalPrice = 0.00;
$savings = false;
$zeroDollars = '';
$price = $item->price($item->get('variants.0.id'));
$richPrice = $item->price($item->get('variants.0.id'));

//strike price if flag hide_price
if ($item->{'policies.hide_price'}) {
    $price = \Shop\Models\Currency::unformat( $item->{'prices.list'});
    $addClass = $addClass.' priceStrike';
}

//get kit original and most appropriate price
if ($item->{'product_type'} == 'dynamic_group') {
    $originalPrice = $item->originalKitPrice();
    if(!$item->hasIncludedItems()) {
        $addClass = $addClass.' priceAsLowAs';
        $zeroDollars = '<div><sup>$</sup>0.<sup>00</sup> </div>';
    } else {
        if ($item->isConfigurable()) {
            $addClass = $addClass.' priceConfigurable';
        }
        $price = $item->selectedPrice();
    }
} elseif ($item->get('flag.enabled')) { //product on sale
    $addClass = 'priceSale';
}

$richPrice = $price;

if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) {
    $price = $item->getVariantPriceRange();
    $zeroDollars = '';
} else {
    $price = \Shop\Models\Currency::format($price);
}

//strike price if flag hide_price
if ($item->{'policies.hide_price'}) {
    $richPrice = '';
    echo $this->renderView ( 'Shop/Site/Views::product/blocks/testing/price.php' );
    }

//savings section
if ($originalPrice - $richPrice >= 0.01) {
    $savings = \Shop\Models\Currency::format($originalPrice - $richPrice);
}
?>

<span itemprop="priceCurrency" content="USD" class="hidden">$</span>
<span itemprop="price" content="<?php echo $richPrice; ?>" class="hidden"><?php echo $richPrice; ?></span>
<h4 class="paddingTopSm">
    <div class="product-price">
        <?php if(!$grouponly) : ?>
            <h2 class="price <?php echo $addClass; ?>">
                <span><?php echo $price; ?></span>
                <?php echo $zeroDollars; ?>
            </h2>
        <?php endif;?>

        <small>
            <?php if (!$item->{'policies.is_giftcard'} && $item->price() < $item->get('prices.list')): ?>
                <?php if (((int) $item->get('prices.list') > 0) && (float) $item->get('prices.list') != (float) $item->price() ) { ?>
                    <?php if($item->get('flag.enabled')) : ?>
                        <strike>MSRP: <?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?></strike>
                    <?php else : ?>
                        MSRP: <?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?>
                    <?php endif; ?>
                <?php } ?>
            <?php endif; ?>
        </small>
    </div>
</h4>
