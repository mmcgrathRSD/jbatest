<?php $priceTag = '{{default_price}}';
$currency = '$';

if(\Base::instance()->get('sales_channel') == 'northridge-canada') {
    $currency = 'CA$';
}
$moneyHtml = '<span class=notranslate><sup>'.$currency.'</sup>'.$priceTag.'</span>';
?>
<ul class="products-grid">
    {{#hits}}
    <li class="item  {{#new_item_flag}}new-product{{/new_item_flag}} quick-view-container" data-model="{{model_number}}">
        <a href="{{url}}" title="{{title}}" class="product-image">
            {{#image_2}}
                {{{image_2}}}
            {{/image_2}}
            {{#image}}
                {{{image}}}
            {{/image}}
            <!--bof free youtube icon-->
            {{#youtube_video_description}}
            <div class="youtube-video"><img src="/images/youtube-video.png" alt="Video description available"></div>
            {{/youtube_video_description}}
            <!--eof youtube icon -->
            {{#new_item_flag}}
            <div class="new-label label-top-right">NEW</div>
            {{/new_item_flag}}
            {{#flag.enabled}}
            <div class="sale-label label-bottom-left">{{{flag.text}}}</div>
            {{/flag.enabled}}
        </a>
        <div class="product-hover">
            <h2 class="product-name"><a href="{{url}}" title="{{title}}">{{title}}{{#title_suffix}} - {{title_suffix}}{{/title_suffix}}</a></h2>
            <br>
            <div class="price-box">
                {{#range_price}}
                <span class="regular-price">
                    <span class="price">{{{range_price}}}</span>
                </span>
                {{/range_price}}
                {{^range_price}}
                {{#flag.enabled}}<p class="special-price">
                <span class="price-label">{{/flag.enabled}}
                {{^flag.enabled}}
                <span class="regular-price">{{/flag.enabled}}
                    <span class="price">{{default_price}}</span>
                </span>
                {{#flag.enabled}}</p>{{/flag.enabled}}
                {{#previous_default_price}}
                <p class="old-price">
                    <span class="price-label"></span>
                    <span class="price">{{previous_default_price}}</span>
                </p>
                {{/previous_default_price}}
                {{/range_price}}
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
                    </dd>
                </dl>
            </div>
            {{/swatches}}
            {{#options_available}}
                <div><label class="options_available required">* Options Available</label></div>
            {{/options_available}}
        </div>
        <div class="name">
        </div>
        </li>
    {{/hits}}
</ul>