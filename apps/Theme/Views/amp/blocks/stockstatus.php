<?php
/** @var \Shop\Models\Products $parent */
/** @var \Shop\Models\Products $product */
if (empty($stockProduct)) {
    throw new \Exception('Product must be set.');
}

if(empty($item)) {
	$item = $stockProduct;
}

if(!empty($cart_stock)) {
    $shipping_callout = '';
} else {
    if (!$shop['free_ltl'] && $item->shipsLtl()) {
        $shipping_callout = "Excluded from free shipping.";
    } else if ($item->price() > $shop['freeshipping']) {
        $shipping_callout = "Qualifies for free shipping!";
    } else {
        $shipping_callout = "Free shipping on all orders over ".\Shop\Models\Currency::format($shop['freeshipping'])."!";
    }
}

/** @var \Shop\Models\ProductStock $stock */
$stock = $stockProduct->stock((int) empty($qty) ? 1 : $qty);
$truck_text = '24<br /><span>hours</span>';
$schema_org = 'InStock'; //Really could find a better way to do this but at this time I don't want to change the GA labels to match Schema.org as we already have existing data on the GA labels, I don't want to change them.
$top_text = '';
$extra_class = '';
$cart_unit = 'days';
$ga_label = 'Not Set';
switch ($stock->getStatus()) {
    case \Shop\Models\ProductStock::IN_STOCK_MULTIWAREHOUSE:
    case \Shop\Models\ProductStock::IN_STOCK:
        $ga_label = 'InStock';
        $top_text = '<span class="aamp_green">In stock!</span> Ships same day<br />';
		$cart_unit = 'hours';
        break;
    case \Shop\Models\ProductStock::LIMITED_STOCK:
        $ga_label = 'LimitedStock';
        $top_text = '<link itemprop="availability" href="http://schema.org/LimitedAvailability"/><i class="fa fa-clock-o" class="aamp_red" aria-hidden="true"></i> Only ' . $stock->getInventory() . ' left!<br />';
		$cart_unit = 'hours';
       break;
    case \Shop\Models\ProductStock::PARTIAL_STOCK:
    case \Shop\Models\ProductStock::OUT_OF_STOCK:
		//Get estimated ship date using days or weeks or week range if there is a remainder
		$actual = round((strtotime($stockProduct->expectedInStockDate()->format('m/d/Y')) - strtotime(date('m/d/Y'))) / 86400 );
		$expected = $actual;
		if($actual > 7) {
			$cart_unit = 'weeks';
			if($actual > 60) {
				$expected = ceil($actual / 7);
			} else {
				$expected = ceil($actual / 7);
				if(($actual % 7) != 0) {
					$expected = ($expected - 1) . '-' . $expected;
				}
				if($expected == 1 && ($actual % 7) == 0) {
					$cart_unit = 'week';
				}
			}
		}
		
        $ga_label = 'OutOfStock';
		$top_text = 'Ships within '.$expected.' '.$cart_unit.'<br />';
		$truck_text = $expected.'<br /><span>'.$cart_unit.'</span>';
        $schema_org = 'OutOfStock';
        if ($stock->getQuantityOnOrder() > 0) {
            $schema_org = 'OnOrder';
        }
        break;
    case \Shop\Models\ProductStock::DROPSHIP_NOTES:
        $top_text = $stock->getDropShipNotes();
    case \Shop\Models\ProductStock::DROPSHIP:
		$extra_class = 'ss_truck-text-small';
		$top_text = 'Ships from manufacturer<br />';
        $ga_label = 'DropShip';
		$truck_text = 'SHIPS<br /><span>direct</span>';
        break;
}
?>
<?php if(empty($cart_stock)) : ?>
<button on="tap:stock-lightbox"
      role="button"
      tabindex="0" class="aamp_modal_button" id="aamp_stock_modal_button"></button>
<label for="aamp_stock_modal_button">
    <?php echo '<link itemprop="availability" href="http://schema.org/'.$schema_org.'"/><div class="ss_icon"><amp-img src="/shop-assets/images/emtpy_stock_icon.jpg" width="70" height="45.5"></amp-img><div class="'.$extra_class.'">'.$truck_text.'</div><div class="ss_icon-text">'.$top_text.$shipping_callout.'</div></div>'; ?>
</label>
<?php else : ?>
<a href="#" data-toggle="modal" data-target="#ssModal" class="ssModal">
    <div class="ss_cart"><i class="fa fa-truck" aria-hidden="true"></i> <?php echo $top_text; ?></div>
</a>
<?php endif; ?>
<amp-lightbox id="stock-lightbox"
	class="aamp_modal_container"
	layout="nodisplay">
	<div class="aamp_modal_body">
		<div class="aamp_modal_close"
			on="tap:stock-lightbox.close"
			role="button"
			tabindex="0">
			<i class="fa fa-times-circle-o" aria-hidden="true"></i>
		</div>
		<div class="aamp_center">
			<strong>
				Shipping Policy
			</strong>
		</div>
		<div>
			<h4>
				TURNAROUND TIME
			</h4>

Items listed as "24 hours" or "Ships same day" will ship the same day if placed on a weekday before 2PM MST / 4PM EST when shipping via UPS. 
Items listed differently typically ship by the estimated ship date listed on the product page. These estimated dates are not guaranteed and are subject to change periodically. Our customer service representatives monitor backorders on a daily basis to ensure your order ships as soon as possible.  Orders requiring additional verification (security concerns, incorrect information, etc.) may need additional processing time.

<h4>
FREE SHIPPING
			</h4>

Free shipping is available within the lower 48 states for orders totaling $<?php echo $shop['freeshipping']; ?> or more. Simply select the free shipping option during checkout. Even though expedited shipping is not free we will still discount the free shipping option from the expedited shipping cost for you. This offer excludes shipments going to Alaska, Hawaii, Military Boxes, and items too large to ship via UPS or USPS. These items are usually large freight or bulky items such as engine blocks, etc.<br />&nbsp;<br />
			<div class="aamp_center">
				<a href="/pages/shipping" target="_blank">View Shipping Policy</a>
			</div>
		</div>
	</div>
	
</amp-lightbox>
