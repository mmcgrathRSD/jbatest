<pre><?php
//Adding item if this view was called from the reviews page, not the product page.
if(empty($item)) {
	$item = $product;
}
if(!empty($reviews)) { //This is also to use this view in the reviews page, not product page. If reviews already exists from the controller, use that rather than making a new query. (Austin's notes)
	$save = [];
	foreach($reviews->items as $review) {
		$save[] = $review;
	}
	$reviews = $save;
	if(empty($questions)) {
		$save = 1;
	}
}
else {
	$reviews = (new \Shop\Models\UserContent())->setCondition('product_id', $item->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->setState('list.limit', 5)->getList();
}

//This is a catch if the queue task is not run to update the product about review counts
if(!empty($reviews) && empty($item->{'review_rating_counts.total'})) {
    $item->set('review_rating_counts.total',count($reviews));
}

?><div class="main row clearfix">
   <div class="col-main grid_18">
      <div class="breadcrumbs">
         <ul>
            <li style="display: inline-block;" typeof="v:Breadcrumb">
               <a href="/" title="Home" rel="v:url" property="v:title">
               Home                </a>
               <span>/</span>
            </li>
            <li style="display: inline-block;">
               <strong><?php echo $item->title; ?></strong>
            </li>
         </ul>
      </div>
      <div itemscope="" itemtype="http://schema.org/Product">
         <script type="text/javascript">
            var optionsPrice = new Product.OptionsPrice([]);
         </script>
         <div id="messages_product_view"></div>
         <div class="product-view">
            <div class="product-essential">
               <form action="https://www.subispeed.com/checkout/cart/add/uenc/aHR0cHM6Ly93d3cuc3ViaXNwZWVkLmNvbS9zdWJhcnUtbWF0dGUtYmxhY2stYWMta25vYi1mdWxsLXJlcGxhY2VtZW50LTIwMTUtd3J4LXN0aS0yMDE0LWZvcmVzdGVyLTIwMTMtY3Jvc3N0cmVr/product/13193/form_key/PTT3lpSgzitk4OCG/" method="post" id="product_addtocart_form">
                  <div class="product-category-title">
                     <h3><?php echo $item->manufacturer['title']; ?></h3>
                  </div>
                  <div class="product-img-box">
                     <div class="product-image">
                        <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-3.jpg" rel="lighbox-zoom-gallery">
                        <img src="https://www.subispeed.com/media/catalog/product/cache/1/image/473x473/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-3.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/image/946x946/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-3.jpg" alt="Subaru Matte Black AC Knob Full Replacement - 2015+ WRX / STI / 2014+ Forester / 2013+ Crosstrek" title="Subaru Matte Black AC Knob Full Replacement - 2015+ WRX / STI / 2014+ Forester / 2013+ Crosstrek" width="473" height="473">
                        </a>
                     </div>
                     <div class="more-views">
                        <div class="more-views-nav" id="thumbs_slider_nav">
                           <ul>
                              <li><a class="prev disabled" href="#"></a></li>
                              <li><a class="next" href="#"></a></li>
                           </ul>
                        </div>
                        <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
                           <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
                              <ul class="jcarousel-slider slides jcarousel-list jcarousel-list-horizontal" id="thumbs_slider" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 705px;">
                                 <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                                    <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-5.jpg" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                                    <span style="width: 92px; height: 92px;"></span>
                                    <img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/110x110/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-5.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/220x220/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-5.jpg" width="110" height="110" alt="">
                                    </a>
                                 </li>
                                 <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" jcarouselindex="2" style="float: left; list-style: none;">
                                    <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-6.jpg" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                                    <span style="width: 92px; height: 92px;"></span>
                                    <img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/110x110/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-6.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/220x220/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-6.jpg" width="110" height="110" alt="">
                                    </a>
                                 </li>
                                 <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" jcarouselindex="3" style="float: left; list-style: none;">
                                    <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-4.jpg" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                                    <span style="width: 92px; height: 92px;"></span>
                                    <img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/110x110/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-4.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/220x220/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-4.jpg" width="110" height="110" alt="">
                                    </a>
                                 </li>
                                 <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-4 jcarousel-item-4-horizontal" jcarouselindex="4" style="float: left; list-style: none;">
                                    <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-1.jpg" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                                    <span style="width: 92px; height: 92px;"></span>
                                    <img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/110x110/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-1.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/220x220/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-1.jpg" width="110" height="110" alt="">
                                    </a>
                                 </li>
                                 <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" jcarouselindex="5" style="float: left; list-style: none;">
                                    <a href="https://www.subispeed.com/media/catalog/product/cache/1/image/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-2.jpg" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                                    <span style="width: 92px; height: 92px;"></span>
                                    <img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/110x110/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-2.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/220x220/85e4522595efc69f496374d01ef2bf13/s/u/subaru_matte_ac_controls-2.jpg" width="110" height="110" alt="">
                                    </a>
                                 </li>
                              </ul>
                           </div>
                        </div>
                     </div>
                     <script type="text/javascript">
                        jQuery(function($) {
                        	$('a[rel="lighbox-zoom-gallery"]').fancybox({
                        		titleShow:false,
                        		hideOnContentClick:true
                        	});
                        });
                     </script>			
                  </div>
                  <div class="product-shop ">
                     <meta itemprop="brand" content="<?php echo $item->manufacturer['title']; ?>">
                     <meta itemprop="sku" content="<?php echo $item->tracking['model_number']; ?>">
                     <meta itemprop="category" content="NOTE">
                     <meta itemprop="image" content="NOTE">
                     <div class="product-name " itemprop="name">
                        <h1><?php echo $item->title; ?></h1>
                        <?php if(!empty($item->title_suffix)) : ?>
                        <h2 class="product_suffix block marginTopNone"> - <?php echo $item->title_suffix; ?></h2>
                        <?php endif; ?>
                        <?php if(!empty($item->h2)) : ?>
                        <h2 class="product_suffix block marginTopNone"> - <?php echo $item->h2; ?></h2>
                        <?php endif; ?>
                     </div>
                     <div class="ratings" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
                        <meta itemprop="ratingValue" content="NOTE">
                        <meta itemprop="reviewCount" content="NOTE">
                        <div class="rating-box">
                           <div class="rating" style="width:100%;" NOTE></div>
                        </div>
                        <p class="rating-links">
                           <a href="NOTE">X Review(s)</a>
                           <span class="separator">|</span>
                           <a href="NOTE">Add Your Review</a>
                        </p>
                     </div>
                     <script>
                     //NOTE
                        var magPrice=76.5;
                        var _affirm_config = {
                          public_api_key: affirm_api_key, /* Use the PUBLIC API KEY Affirm sent you. */
                          script: "https://cdn1.affirm.com/js/v2/affirm.js"
                        };
                        
                        
                        
                        
                        
                        
                        (function(l,g,m,e,a,f,b){var d,c=l[m]||{},h=document.createElement(f),n=document.getElementsByTagName(f)[0],k=function(a,b,c){return function(){a[b]._.push([c,arguments])}};c[e]=k(c,e,"set");d=c[e];c[a]={};c[a]._=[];d._=[];c[a][b]=k(c,a,b);a=0;for(b="set add save post open empty reset on off trigger ready setProduct".split(" ");a<b.length;a++)d[b[a]]=k(c,e,b[a]);a=0;for(b=["get","token","url","items"];a<b.length;a++)d[b[a]]=function(){};h.async=!0;h.src=g[f];n.parentNode.insertBefore(h,n);delete g[f];d(g);l[m]=c})(window,_affirm_config,"affirm","checkout","ui","script","ready");
                        affirm.ui.ready( function() { updateAffirmAsLowAs( magPrice*100 ) } ); // change to your template value for product or cart price
                        function updateAffirmAsLowAs( amount ){
                          if ( ( amount == null ) || ( amount < 10000 ) ) { return; } // Only display as low as for items over $10 CHANGE FOR A DIFFERENT LIMIT
                          // payment estimate options
                          var options = {
                            apr: "0.10", // percentage assumed APR for loan
                            months: 12, // can be 3, 6, or 12
                            amount: amount // USD cents
                          };
                          try {
                            typeof affirm.ui.payments.get_estimate; /* try and access the function */
                          }
                          catch (e) {
                            return; /* stops this function from going any further - affirm functions are not loaded and will throw an error */
                          }
                          // use the payment estimate response
                          function handleEstimateResponse (payment_estimate) {
                            // the payment comes back in USD cents
                            var dollars = ( ( payment_estimate.payment + 99 ) / 100 ) | 0; // get dollars, round up, and convert to int
                            // Set affirm payment text
                            var a = document.getElementById('learn-more');
                            var iText = ('innerText' in a)? 'innerText' : 'textContent';
                            a[iText] = "Starting at $" + dollars + " a month. Learn More";
                            // open the customized Affirm learn more modal
                            a.onclick = payment_estimate.open_modal;
                            a.style.visibility = "visible";
                          };
                          // request a payment estimate
                          affirm.ui.payments.get_estimate(options, handleEstimateResponse);
                        }
                     </script>
                     <div class="price-box-wrap">
                        <div class="f-left">
                           <div class="price-box">
                              <span class="regular-price" id="product-price-13193">
                              <span class="price"><?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup/price.php')?></span>                                    </span>
                           </div>
                        </div>
                        <div class="f-left">
                           <p class="sku">SKU: <span><?php echo $item->tracking['model_number']; ?></span></p>
                           <p class="availability in-stock">
                              <span>
                                 <span class="tt">
                                 <img src="https://www.subispeed.com/media//amstockstatus/icons/501.jpg" class="amstockstatus_icon" alt="" title=""><span class="amtooltip">
                                 <span class="top"></span>
                                 <span class="bottom"></span>
                                 </span></span> <span class="amstockstatus amsts_501"><span style="font-weight: bold; color:#afd500">In Stock</span></span>
                              </span>
                           </p>
                        </div>
                     </div>
                     <div class="clear"></div>
                     <div><a id="learn-more" style="visibility: none;position: relative" href="#"></a></div>
                     <div class="short-description" itemprop="description">
                        <div class="std">
                           <?php echo $item->short_description; ?>
                        </div>
                     </div>
                     <div class="add-to-box">
                        <div class="add-to-cart">
                           <div class="qty-container">
                              <div class="f-right">
                                 <a class="qty-math qty-inc icon-white" href="#"></a>
                                 <a class="qty-math qty-dec icon-white" href="#"></a>
                              </div>
                              <input type="text" name="qty" id="qty" maxlength="12" value="0" title="Qty" class="input-text qty">
                              <label for="qty">Quantity</label>
                           </div>
                           <button type="button" title="Add to Cart" class="button btn-cart icon-black" onclick="" data-click="productAddToCartForm.submit(this)"><span><span>Add to Cart</span></span></button>
                        </div>
                     </div>
                     <ul class="add-to-links">
                        <li><a class="link-wishlist  icon-black" href="https://www.subispeed.com/wishlist/index/add/product/13193/form_key/PTT3lpSgzitk4OCG/" onclick=""><span class="link_i"></span>Add to Wishlist</a></li>
                     </ul>
                     <div class="social">
                        <!-- AddThis Button BEGIN -->
                        <div class="addthis_toolbox addthis_default_style ">
                           <a class="addthis_button_facebook_like at300b" fb:like:layout="button_count">
                              <div class="fb-like fb_iframe_widget" data-layout="button_count" data-show_faces="false" data-share="false" data-action="like" data-width="90" data-height="25" data-font="arial" data-href="https://www.subispeed.com/subaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek" data-send="false" style="height: 25px;" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&amp;app_id=195555774072&amp;container_width=77&amp;font=arial&amp;height=25&amp;href=https%3A%2F%2Fwww.subispeed.com%2Fsubaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek&amp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;send=false&amp;share=false&amp;show_faces=false&amp;width=90"><span style="vertical-align: bottom; width: 69px; height: 20px;"><iframe name="f2c872b77b945c4" width="90px" height="25px" title="fb:like Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/plugins/like.php?action=like&amp;app_id=195555774072&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D46%23cb%3Df1a78600bb1903c%26domain%3Dwww.subispeed.com%26origin%3Dhttps%253A%252F%252Fwww.subispeed.com%252Ff3135e49829595c%26relation%3Dparent.parent&amp;container_width=77&amp;font=arial&amp;height=25&amp;href=https%3A%2F%2Fwww.subispeed.com%2Fsubaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek&amp;layout=button_count&amp;locale=en_US&amp;sdk=joey&amp;send=false&amp;share=false&amp;show_faces=false&amp;width=90" style="border: none; visibility: visible; width: 69px; height: 20px;" class=""></iframe></span></div>
                           </a>
                           <a class="addthis_button_tweet at300b">
                              <div class="tweet_iframe_widget" style="width: 62px; height: 25px;"><span><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" allowfullscreen="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" style="position: static; visibility: visible; width: 61px; height: 20px;" title="Twitter Tweet Button" src="https://platform.twitter.com/widgets/tweet_button.d0f13be8321eb432fba28cfc1c3351b1.en.html#dnt=false&amp;id=twitter-widget-0&amp;lang=en&amp;original_referer=https%3A%2F%2Fwww.subispeed.com%2Fsubaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek%23.Xnpu2dNKhTY&amp;size=m&amp;text=Subaru%20Matte%20Black%20AC%20Knob%20Full%20Replacement%20-%202015%2B%20WRX%20%2F%20STI%20%2F%202014%2B%20Forester%20%2F%202013%2B%20Crosstrek%3A&amp;time=1585082074417&amp;type=share&amp;url=https%3A%2F%2Fwww.subispeed.com%2Fsubaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek%23.Xnpu2a9qW8Y.twitter" data-url="https://www.subispeed.com/subaru-matte-black-ac-knob-full-replacement-2015-wrx-sti-2014-forester-2013-crosstrek#.Xnpu2a9qW8Y.twitter"></iframe></span></div>
                           </a>
                           <a class="addthis_button_pinterest_pinit at300b" pi:pinit:layout="horizontal"></a>
                           <a class="addthis_counter addthis_pill_style addthis_nonzero" href="#" style="display: inline-block;"><a class="atc_s addthis_button_compact">Share<span></span></a><a class="addthis_button_expanded" target="_blank" title="More" href="#" tabindex="1000">47</a></a>
                           <div class="atclear"></div>
                        </div>
                        <script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
                        <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4f574ddc5bcdccbc"></script>
                        <!-- AddThis Button END -->
                     </div>
                  </div>
                  <div class="clearer"></div>
               </form>
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
            </div>
            <div class="product-tabs-container clearfix">
               <ul class="product-tabs clearfix">
                  <li id="product_tabs_description_tabbed" class="first active"><a href="javascript:void(0)">Description</a></li>
                  <li id="product_tabs_additional_tabbed" class="last"><a href="javascript:void(0)">Additional</a></li>
               </ul>
               <h2 id="product_acc_description_tabbed" class="tab-heading active"><a href="#">Description</a></h2>
               <div class="product-tabs-content tabs-content std" id="product_tabs_description_tabbed_contents" style="">
                  <h2>Details</h2>
                  <div class="std">
                     <h4 align="center"><?php echo $item->title; ?></h4>
                     <div class="container">
                        <?php echo $item->copy; ?>
                     </div>
                     <div id="clear"></div>
                     <?php //West told me to remove the 'this part fits' section ?>
                  </div>
               </div>
               <h2 id="product_acc_additional_tabbed" class="tab-heading"><a href="#">Additional</a></h2>
               <div class="product-tabs-content tabs-content " id="product_tabs_additional_tabbed_contents" style="display: none;">
                  <h2>Additional Information</h2>
                  <table class="data-table" id="product-attribute-specs-table">
                     <colgroup>
                        <col width="25%">
                        <col>
                     </colgroup>
                     <tbody>
                        <tr class="first odd">
                           <th class="label">Installation Instructions</th>
                           <td class="data last">These knobs come with a red base which is to be removed prior to installation.  The inner trim ring can be swapped out due to the indicator pointer that come with these as well.</td>
                        </tr>
                        <tr class="last even">
                           <th class="label">Additional Warranty Information</th>
                           <td class="data last">None</td>
                        </tr>
                     </tbody>
                  </table>
                  <script type="text/javascript">decorateTable('product-attribute-specs-table')</script>
               </div>
            </div>
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
            <div class="collateral-box dedicated-review-box" id="product-customer-reviews">
               <div class="title-container clearfix">
                  <h3>3 customer reviews</h3>
                  <button type="button" title="Submit Review" class="button"><span><span>Submit Review</span></span></button>
               </div>
               <div class="average-rating">
                  <div class="f-left">
                     <strong>Average rating</strong>
                     <div class="rating-box rating-large">
                        <div class="rating" style="width:93%;"></div>
                     </div>
                     <span class="reviews-count">(based on 3 reviews)</span>
                  </div>
                  <div class="f-left">
                     <table class="ratings ratings-table">
                        <colgroup>
                           <col width="1">
                           <col>
                        </colgroup>
                        <tbody>
                           <tr>
                              <td>
                                 <div class="rating-box">
                                    <div class="rating" style="width:80%;"></div>
                                 </div>
                              </td>
                              <th><span>Ease of Installation</span></th>
                           </tr>
                           <tr>
                              <td>
                                 <div class="rating-box">
                                    <div class="rating" style="width:100%;"></div>
                                 </div>
                              </td>
                              <th><span>Fit / Quality</span></th>
                           </tr>
                           <tr>
                              <td>
                                 <div class="rating-box">
                                    <div class="rating" style="width:100%;"></div>
                                 </div>
                              </td>
                              <th><span>Overall Satisfaction</span></th>
                           </tr>
                        </tbody>
                     </table>
                  </div>
                  <div class="clear"></div>
               </div>
               <ol class="reviews-list">
                  <li>
                     <h3 class="review-title">Subaru OEM replacement part. Looks awesome</h3>
                     <div class="review-info">
                        <div class="rating-box">
                           <div class="rating" style="width:86.666666666667%;"></div>
                        </div>
                        By <b>Blue STI</b>	            <span class="separator"></span>
                        <b>November 16, 2018</b>
                     </div>
                     <p>Really makes the interior look better and a more black look. They're great just expensive </p>
                  </li>
                  <li>
                     <h3 class="review-title">Looks great</h3>
                     <div class="review-info">
                        <div class="rating-box">
                           <div class="rating" style="width:93.333333333333%;"></div>
                        </div>
                        By <b>Jordan</b>	            <span class="separator"></span>
                        <b>December 29, 2017</b>
                     </div>
                     <p>Pretty simple install, the red pieces are a little difficult to remove from the black rings but the edge of the rings on that side are not visible when installed. The red ring in the pictures shown are not part of this product. Those are a separate part. But overall is nice look over the chrome and they seem to be genuine subaru parts from the boxes they came in. </p>
                  </li>
                  <li>
                     <h3 class="review-title">Nice touch</h3>
                     <div class="review-info">
                        <div class="rating-box">
                           <div class="rating" style="width:100%;"></div>
                        </div>
                        By <b>David</b>	            <span class="separator"></span>
                        <b>December 28, 2017</b>
                     </div>
                     <p>I did not like the sliver accents in the interior, the flat black looks great. You will have to remove the red rings which was easy just need to pry the tab on the inside. </p>
                  </li>
               </ol>
            </div>
            <div class="add-review">
               <div class="form-add">
                  <h2>Write Your Own Review</h2>
                  <p class="review-nologged" id="review-form">
                     Only registered users can write reviews. Please, <a href="https://www.subispeed.com/customer/account/login/referer/aHR0cHM6Ly93d3cuc3ViaXNwZWVkLmNvbS9jYXRhbG9nL3Byb2R1Y3Qvdmlldy9pZC8xMzE5My8jcmV2aWV3LWZvcm0,/">log in</a> or <a href="https://www.subispeed.com/customer/account/create/">register</a>    
                  </p>
               </div>
            </div>
         </div>
      </div>
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
      <div class="box-collateral box-amcustomerimg-form" id="box-amcustomerimg-form" style="">
         <h2>Upload images for this product</h2>
         <iframe id="box-amcustomerimg-form-target" name="box-amcustomerimg-form-target" style="display: none; width: 600px; height: 600px;"></iframe>
         <div><br>Feel free to share pictures of your vehicle with this product!<br><br></div>
         <div id="box-amcustomerimg-form-error" style="display: none;"></div>
         <div id="box-amcustomerimg-form-success" style="display: none;"></div>
         <div id="box-amcustomerimg-form-openlink">
            <a href="#" onclick="javascript: amcustomerimg_showUpload(); return false;">+ Start Uploading My Own Images</a>
         </div>
         <div id="box-amcustomerimg-form-form-container" style="display: none;">
            <form method="post" action="https://www.subispeed.com/amcustomerimg/image/upload/" id="box-amcustomerimg-form-form" enctype="multipart/form-data">
               <input type="hidden" name="product_id" id="product_id" value="13193">
               <div class="form-add">
                  <label>Add Your Images:</label>
                  <div class="input-box">
                     <p><input type="file" name="image[0]" class="input-file" size="50"> &nbsp;&nbsp;
                        Title:  <input type="text" name="title[0]" class="input-text" value="" size="30">
                     </p>
                     <p><input type="file" name="image[1]" class="input-file" size="50"> &nbsp;&nbsp;
                        Title:  <input type="text" name="title[1]" class="input-text" value="" size="30">
                     </p>
                     <p><input type="file" name="image[2]" class="input-file" size="50"> &nbsp;&nbsp;
                        Title:  <input type="text" name="title[2]" class="input-text" value="" size="30">
                     </p>
                  </div>
                  Email address:                         <em style="color: red;">*</em>                        <br>
                  <input type="text" name="guest_email" id="guest_email" class="input-text validate-email required-entry" size="42">
                  <input type="hidden" name="check1" id="check1" value="¦¨¢">
                  <input type="hidden" name="check2" id="check2" value="">
                  <br>                
                  <p>
                     <button type="button" onclick="javascript: amcustomerimg_upload('box-amcustomerimg-form');" class="button upload-button" title="Upload Images">
                  <p></p>
                  <span>
                  <span>Upload Images</span>
                  </span>
                  </button>
                  </p>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>