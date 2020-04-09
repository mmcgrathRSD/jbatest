<?php
   if(!$master_search) {
       $instance_id = '_'.$item->id;
   }
   
   if(!empty($item)) {
       $type = $item->type();
   }
   ?>
<div class="main row search_container" data-instance-id="search<?php echo $instance_id; ?>">
<div id="search_selected<?php echo $instance_id; ?>"></div>
<div id="empty-container<?php echo $instance_id; ?>" style="display: none;"><?php echo $this->renderLayout('Search/Site/Views::search/algolia_custom_empty_template.php');?></div>
   <div class="col-main grid_13 custom_left">
      <div class="category_dynamic_head">
         <div class="breadcrumbs">
            <ul>
               <li style="display: inline-block;" typeof="v:Breadcrumb">
                  <a href="#" title="Home" rel="v:url" property="v:title">
                  Home                </a>
                  <span>/</span>
               </li>
               <li style="display: inline-block;">
                  <strong>15+ WRX </strong>
               </li>
            </ul>
         </div>
         <div class="page-title category-title">
            <h1>2015+ Subaru WRX parts</h1>
         </div>
         <div class="category-description std">
            <div class="container">
               <div class="leftColumn">
                  <h4 align="center"><a href="#">Subispeed 2015 WRX Showcase</a></h4>
                  <div class="video-container">
                     <iframe src="https://www.youtube.com/embed/ZL1q3jLQWNY" frameborder="0" width="560" height="315"></iframe>
                  </div>
               </div>
               <div class="rightColumn">
                  <h4 align="center"><a href="#">OLM SPEC CR SEQUENTIAL TAIL LIGHTS</a></h4>
                  <div class="video-container">
                     <iframe width="560" height="315" src="https://www.youtube.com/embed/yVaD-gqg5_s" frameborder="0" allowfullscreen=""></iframe>
                  </div>
               </div>
            </div>
            <div id="clear"></div>
            <strong></strong>
            <p align="center"><strong>We are your source for Subaru WRX aftermarket and OEM parts. Nobody knows the FA20DIT or the WRX better than we do! Find everything you need from brakes, cooling, wheels, lug nuts, engine mods, exhausts and more here! Take a look below!</strong></p>
            <hr>
         </div>
         <!-- no text just picture
            <h2 class="subcategory">Sub Categories</h2><br />  -->
         <ul class="subcategories3 category-children"></ul>
      </div>
      <div id="clear">
      </div>
      <p></p>
      <a name="new"></a>    
      <div class="category-products product-columns-3">
         <div class="toolbar">
            <div class="sorter">
               <div class="sort-by">
                  <div class="sort-by-wrap toolbar-switch icon-white">
                     <div class="toolbar-title">
                        <label>Sort By</label>
                        <span class="current" data-opposite="products-date-newest-asc" data-current="products-date-newest-desc">Newest Products</span>
                        <div id="search_filter_sort<?php echo $instance_id; ?>"></div>
                     </div>
                     <div class="toolbar-dropdown">
                        <ul>
                           <li class="selected" data="products-date-newest-desc"><a href="#" class="sort-option" data-opposite="products-date-newest-asc" data-current="products-date-newest-desc">Newest Products</a></li>
                           <li data="products-date-newest-asc"><a href="#" class="sort-option" data-opposite="products-date-newest-desc">Oldest Products</a></li>
                           <li data="products"><a href="#" class="sort-option" data-opposite="products">Most Popular</a></li>
                           <li data="products-price-asc"><a href="#" class="sort-option" data-opposite="products-price-desc">Price Lowest</a></li>
                           <li data="products-price-desc"><a href="#" class="sort-option" data-opposite="products-price-asc">Price Highest</a></li>
                           <li data="products-reviews"><a href="#" class="sort-option" data-opposite="products">Reviews</a></li>
                        </ul>
                     </div>
                  </div>
                  <a class="sort-by-arrow icon-white" href="#" title="Set Ascending Direction"><img class="i_desc_arrow" width="29" height="58" src="/theme/img/jba/i_desc_arrow.png" alt="Set Ascending Direction"></a>
               </div>
               <div class="limiter toolbar-switch icon-white">
                  <div class="toolbar-title">
                     <label>Show</label>
                     <span class="current">12</span>
                     per page					
                  </div>
                  <div class="toolbar-dropdown">
                     <ul>
                        <li data="6"><a href="#">6</a></li>
                        <li class="selected" data="12"><a href="#">12</a></li>
                        <li data="24"><a href="#">24</a></li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="pager ">
            <span class="inline_me">
               <p class="amount search_stats_<?php echo $item->id; ?>" id="search_stats<?php echo $instance_id; ?>"></p>
               <div class="pages">
                  <div id="pagination-container<?php echo $instance_id; ?>"></div>
               </div>
            </div>
         </div>
         <div id="hits-container<?php echo $instance_id; ?>"></div>
         
         <div class="toolbar-bottom">
            <div class="toolbar">
               <div class="sorter">
               <div class="sort-by">
                  <div class="sort-by-wrap toolbar-switch icon-white search_filter_sort<?php echo $instance_id; ?>">
                     <div class="toolbar-title">
                        <label>Sort By</label>
                        <span class="current" data-opposite="products-date-newest-asc" data-current="products-date-newest-desc">Newest Products</span>
                        <div id="search_filter_sort<?php echo $instance_id; ?>"></div>
                     </div>
                     <div class="toolbar-dropdown">
                        <ul>
                           <li class="selected" data="products-date-newest-desc"><a href="#" class="sort-option" data-opposite="products-date-newest-asc" data-current="products-date-newest-desc">Newest Products</a></li>
                           <li data="products-date-newest-asc"><a href="#" class="sort-option" data-opposite="products-date-newest-desc">Oldest Products</a></li>
                           <li data="products"><a href="#" class="sort-option" data-opposite="products">Most Popular</a></li>
                           <li data="products-price-asc"><a href="#" class="sort-option" data-opposite="products-price-desc">Price Lowest</a></li>
                           <li data="products-price-desc"><a href="#" class="sort-option" data-opposite="products-price-asc">Price Highest</a></li>
                           <li data="products-reviews"><a href="#" class="sort-option" data-opposite="products">Reviews</a></li>
                        </ul>
                     </div>
                  </div>
                  <a class="sort-by-arrow icon-white" href="#" title="Set Ascending Direction"><img class="i_desc_arrow" width="29" height="58" src="/theme/img/jba/i_desc_arrow.png" alt="Set Ascending Direction"></a>
               </div>
                  <div class="limiter toolbar-switch icon-white">
                     <div class="toolbar-title">
                        <label>Show</label>
                        <span class="current">12</span>
                        per page					
                     </div>
                     <div class="toolbar-dropdown">
                        <ul>
                           <li data="6"><a href="#">6</a></li>
                           <li class="selected" data="12"><a href="#">12</a></li>
                           <li data="24"><a href="#">24</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
               <div class="pager">
                  <p class="amount search2_stats<?php echo $item->id; ?>" id="search2_stats<?php echo $instance_id; ?>"></p>
                  
                  <div class="pages">
                     <div id="pagination2-container<?php echo $instance_id; ?>"></div>
                  </div>
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
      <div class="block block-list" itemscope="" itemtype="http://schema.org/Product">
      </div>
   </div>
   <div class="col-left sidebar grid_5 custom_left">
         <?php if($master_search) : ?>
         <div id="search_clear_all"></div>
         <?php else : ?>
         <div class="collection_search_input" id="search-box<?php echo $instance_id; ?>"></div>
         <?php endif; ?>
        <div class="block block-layered-nav block-layered-color">
         <div class="block-title">
            <strong><span>
            Categories                      </span></strong>
         </div>
         <div class="block-content">
            <ol>
            <div class="olegnaxmegamenu-sidebar" id="search_filter_categories<?php echo $instance_id; ?>"></div>
            </ol>
         </div>
      </div>
        <div class="block block-layered-nav block-layered-color">
         <div class="block-title">
            <strong><span>
            Brand                        </span></strong>
         </div>
         <div class="block-content">
            <ol>
                <div id="search_filter_brand<?php echo $instance_id; ?>"></div>
            </ol>
         </div>
      </div>
        <?php if(\Base::instance()->get('SITE_TYPE') != 'wholesale') : ?>
         <div class="block block-layered-nav block-layered-color">
            <div class="block-title">
               <strong><span>
               Rating                        </span></strong>
            </div>
            <div class="block-content">
               <ol>
                  <div id="search_filter_rating<?php echo $instance_id; ?>"></div>
               </ol>
            </div>
         </div>
        <?php elseif(\Base::instance()->get('SITE_TYPE') == 'wholesale') : ?>
        <div class="side_box mobile_side_box" data-id="search_filter_status<?php echo $instance_id; ?>">
            <h4>Status</h4>
            <div id="search_filter_status<?php echo $instance_id; ?>"></div>
        </div>
        <div class="side_box mobile_side_box" data-id="search_filter_promotions<?php echo $instance_id; ?>">
            <h4>Promotion</h4>
            <div id="search_filter_promotions<?php echo $instance_id; ?>"></div>
        </div>
        <?php endif; ?>
        <?php if($type == 'shop.categories' && !$master_search && !empty($item->product_specs)) : ?>
        <div class="side_box mobile_side_box"  data-id="search_filter_specs<?php echo $instance_id; ?>">
        <?php foreach($item->product_specs as $key => $spec) : ?>
        <?php if(empty($spec['custom_atf'])) : ?>
            <div id="search_filter_<?php echo str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $key)); echo $instance_id; ?>"></div>
        <?php endif; ?>
        <?php endforeach; ?>
        </div>
        <?php endif; ?>
      <!-- banner slider BOF -->
      <div id="banners_slider_sidebar_list_nav" class="nav">
         <a class="prev" href="#">&nbsp;</a>
         <a class="next" href="#">&nbsp;</a>
      </div>
      <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
         <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
            <ul id="banners_slider_sidebar_list" class="banners jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: -232px; width: 1492px;">
               <li class="slide37 jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-left" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="123" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">The Gift
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="162" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">That Keeps
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="108" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Giving</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="86" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Check it out</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/Athlete-Banners-232x368-Subi-GC.jpg" alt="" width="232" height="368">
                  </a>
               </li>
               <li class="slide27 jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" jcarouselindex="2" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-right" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="136" data-height="38" style="width: 136px; height: 38px;"><span class="text" style="">New RGB
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="128" data-height="38" style="width: 128px; height: 38px;"><span class="text" style="">C-Lights</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="76" data-height="22" style="width: 76px; height: 22px;"><em class="link" style="">Click here</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/rgbclight.jpg" alt="" width="232" height="368">
                  </a>
               </li>
               <li class="slide28 jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" jcarouselindex="3" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-left" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="130" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Scosche
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="131" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Mounts</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="76" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Click here</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/scosche_mounts.jpg" alt="" width="232" height="368">
                  </a>
               </li>
               <li class="slide32 jcarousel-item jcarousel-item-horizontal jcarousel-item-4 jcarousel-item-4-horizontal" jcarouselindex="4" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-left" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">SubiSpeed
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="218" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Wheel Spacers</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="86" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Click here...</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/wheel_spacers.jpg" alt="" width="232" height="368">
                  </a>
               </li>
               <li class="slide33 jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" jcarouselindex="5" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-left" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">SubiSpeed 
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="160" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Headlight</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="79" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Order now</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/subispeedheadlight.jpg" alt="" width="232" height="368">
                  </a>
               </li>
               <li class="slide30 jcarousel-item jcarousel-item-horizontal jcarousel-item-6 jcarousel-item-6-horizontal" jcarouselindex="6" style="float: left; list-style: none;">
                  <a href="#">
                     <span class="text-container top-left" style="display: inline;">
                        <div class="animation-wrapper animation-text" data-width="78" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">WRX 
                           </span>
                        </div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-text" data-width="150" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Top Mods</span></div>
                        <br style="display: none;">
                        <div class="animation-wrapper animation-link" data-width="76" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Click here</em></div>
                     </span>
                     <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/youtubechannel.jpg" alt="" width="232" height="368">
                  </a>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <!-- banner slider EOF -->
</div>
</div>