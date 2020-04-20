<?php $priceTag = '{{default_price}}';
$currency = '$';

if(\Base::instance()->get('sales_channel') == 'northridge-canada') {
    $currency = 'CA$';
}
$moneyHtml = '<span class=notranslate><sup>'.$currency.'</sup>'.$priceTag.'</span>';
?>
<ul class="products-grid">
    {{#hits}}
    <li class="item  new-product quick-view-container">
        <a href="{{url}}" title="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" class="product-image">
            <img class="additional_img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQl6HtoHN1cCg6Fm2Jve-5zV08j1xGkxCQeariDtmpav8NoPBhE" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/small_image/434x434/85e4522595efc69f496374d01ef2bf13/1/2/124006_-_grimmspeed_fa_head_gasket_set_0.78mm-2.jpg" width="217" height="217" alt="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" style="opacity: 0; display: block;">                <img class="regular_img" src="https://encrypted-tbn0.gstatic.com/images?q=tbn%3AANd9GcQl6HtoHN1cCg6Fm2Jve-5zV08j1xGkxCQeariDtmpav8NoPBhE" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/small_image/434x434/85e4522595efc69f496374d01ef2bf13/1/2/124006_-_grimmspeed_fa_head_gasket_set_0.78mm-1.jpg" width="217" height="217" alt="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" style="opacity: 1;">				<!--bof free youtube icon-->
            <!--eof youtube icon -->
            <div class="new-label label-top-right">NEW</div>
        </a>
        <div class="product-hover">
            <h2 class="product-name"><a href="#" title="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ">{{title}}{{#title_suffix}} - {{title_suffix}}{{/title_suffix}}</a></h2>
            <br>
            <div class="price-box">
                <span class="regular-price" id="product-price-23498">
                <span class="price">${{default_price}}</span>                                    </span>
            </div>
            <div id="insert" style="display:none;"></div>
        </div>
        <div class="name">
        </div>
        </li>
    {{/hits}}
</ul>