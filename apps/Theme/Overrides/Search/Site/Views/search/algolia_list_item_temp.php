<?php $priceTag = '{{default_price}}';
$currency = '$';

if(\Base::instance()->get('sales_channel') == 'northridge-canada') {
    $currency = 'CA$';
}
$moneyHtml = '<span class=notranslate><sup>'.$currency.'</sup>'.$priceTag.'</span>';
?>
<ul class="products-grid">
    {{#hits}}
    <li class="item  new-product quick-view-container" data-model="{{model_number}}">
        <a href="{{url}}" title="{{title}}" class="product-image">
            {{#image}}
            <img class="regular_img" src="{{image}}" width="217" height="217" alt="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" style="opacity: 1; display: block;">
            {{/image}}
            {{^image}}
            <img class="regular_img" src="{{image}}" width="217" height="217" alt="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" style="opacity: 1; display: block;">
            {{/image}}
            {{#image_2}}
            <img class="additional_img" src="{{image_2}}" width="217" height="217" alt="GrimmSpeed Head Gasket Set (FA20) - 2015+ WRX / 2013+ BRZ" style="opacity: 0;">
            {{/image_2}}
            <!--bof free youtube icon-->
            <!--eof youtube icon -->
            <div class="new-label label-top-right">NEW</div>
        </a>
        <div class="product-hover">
            <h2 class="product-name"><a href="{{url}}" title="{{title}}">{{title}}{{#title_suffix}} - {{title_suffix}}{{/title_suffix}}</a></h2>
            <br>
            <div class="price-box">
                <span class="regular-price" id="product-price-23498">
                <span class="price">${{default_price}}</span>                                    </span>
            </div>
            <div id="insert" style="display:none;"></div>
            {{#swatches}}
            <div id="amconf-block">
                <dl>
                    <dt id="label-92-12313" style=""><label class="required"><em>*</em> {{key}}</label></dt>
                    <dd class="last">
                        <div class="input-box" style="margin: 2px;">
                            <div class="amconf-images-container">
                                {{#value}}
                                <div class="amconf-image-container">
                                    {{{value}}}
                                </div>
                                {{/value}}
                                <div style="clear: both;"></div>
                            </div>  
                        </div>
                        <div id="requared-attribute92-12313" style="color:red"></div>
                    </dd>
                </dl>
            </div>
            {{/swatches}}
        </div>
        <div class="name">
        </div>
        </li>
    {{/hits}}
</ul>