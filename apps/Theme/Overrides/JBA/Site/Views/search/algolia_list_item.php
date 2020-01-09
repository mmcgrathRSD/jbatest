<?php $priceTag = '{{default_price}}';
$currency = '$';
if(\Base::instance()->get('sales_channel') == 'northridge-canada') {
    $priceTag = '{{canada_price}}';
    $currency = 'CA$';
}
$moneyHtml = '<span class=notranslate><sup>'.$currency.'</sup>'.$priceTag.'</span>';
?><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 noCompare paddingBottom paddingLNone paddingRNone position-0 productItem"><article class=product-{{ObjectId}} id=product-{{ObjectId}} itemscope itemtype=http://schema.org/Product><a href={{url}} id=category-list-item-oem-number_{{model_number}}><div class="paddingLeft paddingRight productItemInner"><br><div class="productImg text-center"><img alt="{{title}}" class=img-responsive src={{image}} title="{{title}}"></div><br><div class="productName vcenter"><h4 class="gridTitle marginBottomNone marginTopNone"><span itemprop=name>{{title}}</span></h4><div><span class=detail-line>MODEL #<span itemprop=sku>{{model_number}}</span></span></div><div itemprop=offers itemscope itemtype=http://schema.org/Offer><div itemprop=aggregateRating itemscope itemtype=http://schema.org/AggregateRating></div><strong class=newPrice content="<?php echo $priceTag; ?>"itemprop=price><?php echo $moneyHtml; ?></strong><meta content=USD itemprop=priceCurrency></div></div><button class="btn btn-primary footerSubmit newProdsButton">View Product</button></div></a></article></div>