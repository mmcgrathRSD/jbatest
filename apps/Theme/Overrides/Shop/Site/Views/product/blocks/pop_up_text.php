<div id="map-popup" class="map-popup" style="display:none;">
    <a href="#" class="map-popup-close" id="map-popup-close">x</a>
    <div class="map-popup-arrow"></div>
    <div class="map-popup-heading">
    <h2 id="map-popup-heading"></h2>
    </div>
    <div class="map-popup-content" id="map-popup-content">
    <div class="map-popup-checkout">
        <form action="" method="POST" id="product_addtocart_form_from_popup">
            <input type="hidden" name="product" class="product_id" value="" id="map-popup-product-id">
            <div class="additional-addtocart-box">
            </div>
            <button type="button" title="Add to Cart" class="button btn-cart" id="map-popup-button" onclick=""><span><span>Add to Cart</span></span></button>
        </form>
    </div>
    <div class="map-popup-msrp" id="map-popup-msrp-box"><strong>Price:</strong> <span style="text-decoration:line-through;" id="map-popup-msrp"></span></div>
    <div class="map-popup-price" id="map-popup-price-box"><strong>Actual Price:</strong> <span id="map-popup-price"></span></div>
    <script type="text/javascript">
        //<![CDATA[
            document.observe("dom:loaded", Catalog.Map.bindProductForm);
        //]]>
    </script>
    </div>
    <div class="map-popup-text" id="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
    <div class="map-popup-text" id="map-popup-text-what-this">Our price is lower than the manufacturer's "minimum advertised price."  As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
</div>