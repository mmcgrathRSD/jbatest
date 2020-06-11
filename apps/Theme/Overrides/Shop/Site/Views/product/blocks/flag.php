<?php

    $addClass = '';
    $originalPrice = '';
    $savings = false;
    $zeroDollars = '';
    $price = $item->price();
    $richPrice = $item->price();
    $msrp = \Shop\Models\Currency::unformat($item->getPriceByChannel('list'));
    $previous = null;

    //strike price if flag hide_price
    if ($item->{'policies.hide_price'}) {
        $price = $msrp;
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
    } elseif ($item->get('flag.enabled') && strtolower($item->get('flag.text')) != 'new') { //product on sale but don't flag new item as onsale
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

    $saleOffFlag = $item->saleFlagText();

    if(!empty($saleOffFlag) && !filter_var($item->get('flag.toggle'), FILTER_VALIDATE_BOOLEAN) && empty($item->get('flag.text'))) {
        $youSave = 'You save: ';

        if(filter_var($item->get('flag.percent'), FILTER_VALIDATE_BOOLEAN)) {
            $saleOffFlag = $item->getPercentOff() . '%';
            $dollorOffAmount = $item->getDollarOff();
            $previous = 'Was: <strike>' . \Shop\Models\Currency::format( $item->getPreviousPrice() ) . '</strike>';
        } else {
            $saleOffFlag = $item->getPercentOff(null, false) . '%';
            $dollorOffAmount = $item->getDollarOff(null, false);
        }

        if($item->get('flag.display_as') == 'dollar') {
            $saleOffFlag = '$' . $dollorOffAmount;
            $dollorOffAmount = null;
        } else if($item->get('flag.display_as') == 'percent' || $dollorOffAmount <= 0) {
            $dollorOffAmount = null;
        }

        if($dollorOffAmount) {
            $dollorOffAmount = ' (' . \Shop\Models\Currency::format($dollorOffAmount) . ')';
        }
    }
    if(!empty($saleOffFlag)) : 
?>
<div class="sale-label label-top-left" <?php if($flagColor = $item->get('flag.color')) { echo 'style="background-color: ' . $flagColor . '"'; } ?>><?php echo $saleOffFlag; ?></div>
<?php endif; ?>