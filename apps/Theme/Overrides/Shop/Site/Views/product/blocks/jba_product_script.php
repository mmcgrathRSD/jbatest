<script type="text/javascript">
    //<![CDATA[
    var productAddToCartForm = new VarienForm('product_addtocart_form');
    productAddToCartForm.submit = function(button, url) {
    if (this.validator.validate()) {
        var form = this.form;
        var oldUrl = form.action;
        if (url) {
            form.action = url;
        }
        var e = null;
        if (!url) {
            url = jQuery('#product_addtocart_form').attr('action');
        }
        url = url.replace("checkout/cart","oxajax/cart");
        url = url.replace("wishlist/index/cart", "oxajax/cart/add");
        var data = jQuery('#product_addtocart_form').serialize();
        data += '&isAjax=1';
        if ('https:' == document.location.protocol) {
            url = url.replace('http:', 'https:');
        }
        jQuery.fancybox.showActivity();
        jQuery.ajax({
            url:url,
            dataType:'jsonp',
            type:'post',
            data:data,
            success:function (data) {
                Olegnax.Ajaxcart.helpers.showMessage(data.message);
                Olegnax.Ajaxcart.helpers.cartSuccessFunc(data);
            }
        });
        this.form.action = oldUrl;
        if (e) {
            throw e;
        }
    }
    }.bind(productAddToCartForm);
    
    productAddToCartForm.submitLight = function(button, url){
    if(this.validator) {
        var nv = Validation.methods;
        delete Validation.methods['required-entry'];
        delete Validation.methods['validate-one-required'];
        delete Validation.methods['validate-one-required-by-name'];
        if (this.validator.validate()) {
            if (url) {
                this.form.action = url;
            }
            this.form.submit();
        }
        Object.extend(Validation.methods, nv);
    }
    }.bind(productAddToCartForm);
    
    productAddToCartForm.submitWishlist = function (button, url) {
    if(this.validator) {
        var nv = Validation.methods;
        delete Validation.methods['required-entry'];
        delete Validation.methods['validate-one-required'];
        delete Validation.methods['validate-one-required-by-name'];
        if (this.validator.validate()) {
            var form = this.form;
            var oldUrl = form.action;
            if (url) {
                form.action = url;
            }
            var e = null;
            if (!url) {
                url = jQuery('#product_addtocart_form').attr('action');
            }
            url = url.replace("wishlist/index", "oxajax/wishlist");
            if ('https:' == document.location.protocol) {
                url = url.replace('http:', 'https:');
            }
            var data = jQuery('#product_addtocart_form').serialize();
            data += '&isAjax=1';
            if ('https:' == document.location.protocol) {
                url = url.replace('http:', 'https:');
            }
            jQuery.fancybox.showActivity();
            jQuery.ajax({
                url:url,
                dataType:'jsonp',
                type:'post',
                data:data,
                success:function (data) {
                Olegnax.Ajaxcart.helpers.showMessage(data.message);
                Olegnax.Ajaxcart.helpers.wishlistSuccessFunc(data);
                }
            });
            this.form.action = oldUrl;
    
            if (e) {
                throw e;
            }
        }
        Object.extend(Validation.methods, nv);
    }
    }.bind(productAddToCartForm);
    
    //]]>
</script>
<script type="text/javascript">
//<![CDATA[
Varien.Tabs = Class.create();
Varien.Tabs.prototype = {
    initialize: function(selector) {
    var self=this;
    $$(selector+' a').each(this.initTab.bind(this));
this.showContent($$(selector+' a')[0]);
    },

    initTab: function(el) {
        el.href = 'javascript:void(0)';
        el.observe('click', this.showContent.bind(this, el));
    },

    showContent: function(a) {
    var li = $(a.parentNode), ul = $(li.parentNode);
    ul.select('li', 'ol').each(function(el){
        var contents = $(el.id+'_contents');
        if (el==li) {
        el.addClassName('active');
        contents.show();
        } else {
        el.removeClassName('active');
        contents.hide();
        }
    });
    }
}
new Varien.Tabs('.product-tabs');
//]]>
</script>
<script type="text/javascript">
    var lifetime = 3600;
    var expireAt = Mage.Cookies.expires;
    if (lifetime > 0) {
        expireAt = new Date();
        expireAt.setTime(expireAt.getTime() + lifetime * 1000);
    }
    Mage.Cookies.set('external_no_cache', 1, expireAt);
</script>
<script type="text/javascript">
    if ($('box-amcustomerimg-slider'))
    {
    $('box-amcustomerimg-slider').style.display = 'none';
    document.observe("dom:loaded", function() {
        amcustomerimg_position('box-amcustomerimg-slider', '.grid_18');
    });
    }
</script>
<script type="text/javascript">
    var h = new Hash();
    h.set('value',1);  
    function timer_form(){
        h.set('value', h.get('value')+1);
    }
    function amcustomerimg_showUpload()
    {
        $('box-amcustomerimg-form-openlink').style.display = 'none';
        $('box-amcustomerimg-form-form-container').style.display = '';
        
        var timerMulti = window.setInterval("timer_form();", 1000);
    }
</script>
<script type="text/javascript">
    if ($('box-amcustomerimg-form'))
    {
        $('box-amcustomerimg-form').style.display = 'none';
        document.observe("dom:loaded", function() {
            amcustomerimg_position('box-amcustomerimg-form', '.grid_18');
        });
    }
</script>