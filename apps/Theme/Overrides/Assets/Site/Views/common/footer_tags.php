<?php if($listrak_id = $this->app->get('listrak.id')) : ?>
<?php if($page == 'confirmation') : ?>
<img src="https://fp.listrakbi.com/fp/<?php echo $listrak_id; ?>.jpg" height="1" width="1" />
<?php endif; ?>
<script type="text/javascript">

	/********** Listrak Master **********/
	(function(d) { if (document.addEventListener) document.addEventListener('ltkAsyncListener', d);
        else {e = document.documentElement; e.ltkAsyncProperty = 0; e.attachEvent('onpropertychange', function (e) {
        if (e.propertyName == 'ltkAsyncProperty'){d();}});}})(function() {

		/********** Begin Custom Code - Cart Collection **********/
		<?php if($page == 'cart') : ?>
		<?php if(empty($this->input->get('empty'))) : ?>
		<?php foreach ((array) $cart->items as $key => $item) : $product_list[] = \Dsc\ArrayHelper::get($item['product'], 'tracking.model_number_flat'); ?>
		_ltk.SCA.AddItemWithLinks('<?php echo \Dsc\ArrayHelper::get($item['product'], 'tracking.model_number_flat'); ?>', <?php echo \Dsc\ArrayHelper::get($item, 'quantity'); ?>, '<?php echo \Dsc\ArrayHelper::get($item, 'price'); ?>', '<?php echo \Dsc\ArrayHelper::get($item, 'product.title'); ?>', '<?php echo \Shop\Models\Products::product_thumb(\Dsc\ArrayHelper::get($item, 'image')); ?>', '/shop/product/<?php echo \Dsc\ArrayHelper::get($item, 'product.slug'); ?>');
		<?php endforeach; ?>
		<?php if(!empty($product_list)) : ?>
		//_ltk.Recommender.AddSku(<?php echo '"'.implode('","',$product_list).'"'; ?>);
		<?php endif; ?>
	  	_ltk.SCA.Submit();
		<?php else : ?>
		_ltk.SCA.ClearCart();
		<?php endif; ?>
		<?php endif; ?>

		/********** Begin Custom Code - Checkout Confirmation Collection **********/
		<?php if($page == 'confirmation') : ?>
		_ltk.Order.SetCustomer('<?php echo $this->order->customer['email']; ?>', '<?php echo $this->order->customer['first_name']; ?>', '<?php echo $this->order->customer['last_name']; ?>');
		_ltk.Order.OrderNumber = '<?php echo $this->order->number; ?>';
		_ltk.Order.ItemTotal = '<?php echo (float) $this->order->sub_total; ?>';
		<?php if ($this->order->shipping_total - $this->order->shipping_discount_total > 0) : ?>
		_ltk.Order.ShippingTotal = '<?php echo (float) $this->order->shipping_total - $this->order->shipping_discount_total; ?>';
		<?php endif; ?>
		<?php if (!empty($this->order->tax_total)) : ?>
		_ltk.Order.TaxTotal = '<?php echo (float) $this->order->tax_total; ?>';
		<?php endif; ?>
		_ltk.Order.OrderTotal = '<?php echo (float) $this->order->grand_total; ?>';

		<?php foreach ($this->order->items as $item) : $product_list[] = \Dsc\ArrayHelper::get($item['product'], 'tracking.model_number_flat'); ?>
		_ltk.Order.AddItem('<?php echo \Dsc\ArrayHelper::get($item['product'], 'tracking.model_number_flat'); ?>', <?php echo (int) \Dsc\ArrayHelper::get($item, 'quantity'); ?>, '<?php echo (float) \Dsc\ArrayHelper::get($item, 'price'); ?>');
		<?php endforeach; ?>

		_ltk.Order.Submit();
		<?php if(!empty($product_list)) : ?>
		//_ltk.Recommender.AddSku(<?php echo '"'.implode('","',$product_list).'"'; ?>);
		<?php endif; ?>

		_ltk.SCA.SetCustomer('<?php echo $this->order->customer['email']; ?>', '<?php echo $this->order->customer['first_name']; ?>', '<?php echo $this->order->customer['last_name']; ?>');
		_ltk.SCA.OrderNumber = '<?php echo $this->order->number; ?>';
		_ltk.SCA.Submit();
		<?php endif; ?>
	});
</script>
<!-- Listrak Analytics – Javascript Framework -->
<script type="text/javascript">
    <?php if($DEBUG) : //I'm adding this here because the script gets blocked in the VPN if not https ?>
    var biJsHost = "https://";
    <?php else : ?>
    var biJsHost = (("https:" == document.location.protocol) ? "https://" : "http://");
    <?php endif; ?>
    (function (d, s, id, tid, vid) {
      var js, ljs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return; js = d.createElement(s); js.id = id;
      js.src = biJsHost + "cdn.listrakbi.com/scripts/script.js?m=" + tid + "&v=" + vid;
      ljs.parentNode.insertBefore(js, ljs);
    })(document, 'script', 'ltkSDK', '<?php echo $listrak_id; ?>', '1');
</script>
<?php endif; ?>
<?php if(\Base::instance()->get('SITE_TYPE') != 'wholesale' && $this->app->get('velaro.id') && $this->app->get('velaro.group')) : ?>
<script type="text/javascript">
	(function() {
    var w = window;
    var d = document;
    if (w.Velaro) {
        return;
    }
    var v = function() {
        return v.c(arguments)
    };
    v.q = [];
    v.c = function(args) {
        v.q.push(args)
    };
    w.Velaro = v;
    v.endpoints = {
        mainApi: 'https://api-main-us-east.velaro.com/',
        cdn: 'https://eastprodcdn.azureedge.net/'
    };
    w.addEventListener('load', function() {
        var s = d.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = v.endpoints.cdn + 'widgets/shim';
        var x = d.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
    });

    Velaro('boot', {
        siteId: <?php echo $this->app->get('velaro.id'); ?>,
        groupId: <?php echo $this->app->get('velaro.group'); ?>
    });
    
    try {
    	document.getElementById('chat-button').addEventListener('click', function() {
	        if(Velaro('isExpanded')) {
	            Velaro('collapse')
	        } else {
	            Velaro('expand')
	        }
	    })
    } catch(error) {
    	
    }
    
}());
</script>
<?php endif; ?>
<script>

</script>
<noscript>
    <a href="https://www.velaro.com" title="Contact us" target="_blank">Questions?</a>
    powered by <a href="http://www.velaro.com" title="Velaro live chat">velaro live chat</a>
</noscript>
