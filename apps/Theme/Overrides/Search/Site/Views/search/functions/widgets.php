<?php
if (!$master_search) {
   $instance_id = '_' . $item->id;

   $crumbs = $item->getHierarchyTitlePathArray();

   $last_crumb = array_key_last($crumbs);
   array_pop($crumbs);
}

if (!empty($item)) {
   $type = $item->type();
}
?>
<div class="main row search_container" data-instance-id="search<?php echo $instance_id; ?>">
   <div id="empty-container<?php echo $instance_id; ?>" style="display: none;"><?php echo $this->renderLayout('Search/Site/Views::search/algolia_custom_empty_template.php'); ?></div>
   <div class="col-main grid_13 custom_left">
      <div class="category_dynamic_head">
         <div class="breadcrumbs">
            <ul>
               <?php if (!empty($crumbs)) : ?>
                  <li style="display: inline-block;" typeof="v:Breadcrumb">
                     <a href="/" title="Home" rel="v:url" property="v:title">Home</a>
                  </li>
                  <?php foreach ($crumbs as $key => $crumb) : ?>
                     <li style="display: inline-block;" typeof="v:Breadcrumb">
                        <span>/</span>
                        <a href="/scp<?php echo $crumb; ?>" title="<?php echo $key; ?>" rel="v:url" property="v:title"><?php echo $key; ?></a>
                     </li>
                  <?php endforeach; ?>
                  <li style="display: inline-block;">
                     <span>/</span>
                     <strong><?php echo $last_crumb; ?></strong>
                  </li>
               <?php else : //TODO: Put 'Search / [q]' here 
               ?>

               <?php endif; ?>
            </ul>
         </div>
         <div class="page-title category-title">
            <?php if (!$this->app->get('master_search')) : ?>
               <h1><?php echo !empty($item->h1) ? $item->h1 : $item->title . ' Parts'; ?></h1>
		         <?php echo !empty($item->h2) ? '<h2 class="manual_h2"><small>' . $item->h2 . '</small></h2>' : ''; ?>
            <?php else : ?>
               <span class="search_title">Search</span>
            <?php endif; ?>
         </div>
         <?php if (!$this->app->get('master_search')) : ?>
            <div class="category-description std">
               <?php echo $item->description; ?>
            </div>
            <!-- no text just picture
            <h2 class="subcategory">Sub Categories</h2><br />  -->
            <ul class="subcategories3 category-children">
               <?php foreach ((array) $children as $child) : ?>
                  <li>
                     <a href="<?php echo $child['link']; ?>">
                        <img src="<?php echo $child['image']; ?>" alt="<?php echo $child['title']; ?>" height="60" width="175" data-algolia-hierarchy="<?php echo $child['hierarchical_category']; ?>">
                     </a>
                  </li>
               <?php endforeach; ?>
            </ul>
         <?php endif; ?>
      </div>
      <div id="clear">
      </div>
      <div id="search_selected<?php echo $instance_id; ?>" class="refined_results_jba"></div>
      <p></p>
      <a name="new"></a>
      <div class="category-products product-columns-3">
         <div class="toolbar">
            <div class="sorter">
               <div class="sort-by">
                  <div class="sort-by-wrap toolbar-switch icon-white">
                     <div class="toolbar-title">
                        <label>Sort By</label>
                        <span class="current" data-opposite="products-date-newest-asc" data-current="products">Newest Products</span>
                        <div id="search_filter_sort<?php echo $instance_id; ?>"></div>
                     </div>
                     <div class="toolbar-dropdown">
                        <ul>
                           <li class="selected" data="products"><a href="#" class="sort-option" data-opposite="products-date-newest-asc" data-current="products">Newest Products</a></li>
                           <li data="products-date-newest-asc"><a href="#" class="sort-option" data-opposite="products">Oldest Products</a></li>
                           <li data="products-date-newest-desc"><a href="#" class="sort-option" data-opposite="products-date-newest-desc">Most Popular</a></li>
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
                              <li class="selected" data="products"><a href="#" class="sort-option" data-opposite="products-date-newest-asc" data-current="products">Newest Products</a></li>
                              <li data="products-date-newest-asc"><a href="#" class="sort-option" data-opposite="products">Oldest Products</a></li>
                              <li data="products-date-newest-desc"><a href="#" class="sort-option" data-opposite="products-date-newest-desc"">Most Popular</a></li>
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
         </div>
         <div class="map-popup-text" id="map-popup-text">Our price is lower than the manufacturer's "minimum advertised price." As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
         <div class="map-popup-text" id="map-popup-text-what-this">Our price is lower than the manufacturer's "minimum advertised price." As a result, we cannot show you the price in catalog or the product page. <br><br> You have no obligation to purchase the product once you know the price. You can simply remove the item from your cart.</div>
      </div>
      <!-- <div class="block block-list" itemscope="" itemtype="http://schema.org/Product">
      </div> -->
      <!-- start of category rating -->
      <div class="block block-list" itemscope="" id="category-rating" itemtype="http://schema.org/Product">
         <div class="block-title" id="category-rating-title">
            <strong>
               <span itemprop="name"><?php echo $item->title; ?></span>
            </strong>
         </div>
         <div class="block-content">
            <div id="m-snippets" class="category-sniippets">
               <span class="ratings rating-label" style="float:left;"></span>
               <div class="ratings" itemscope="" itemtype="http://schema.org/AggregateRating" itemprop="aggregateRating">
                  <div id="category-rating-stars" class="rating-box" style="float: left; margin-right: 5px;">
                     <div class="rating" style="width: <?php echo (floatval($item->rating['average']) / 5) * 100; ?>%"></div>
                  </div>
                  <div class="r-lnk" id="category-rating-values">
                     <span itemprop="ratingValue"><?php echo floatval($item->rating['average']); ?></span>
                     <span> / </span>
                     <span itemprop="reviewCount"><?php echo $item->rating['total']; ?></span>
                     Review(s)
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- end of category rating -->
   </div>
   <div class="col-left sidebar grid_5 custom_left">
      <?php if ($master_search) : ?>
         <div id="search_clear_all"></div>
      <?php else : ?>
         <div class="collection_search_input" id="search-box<?php echo $instance_id; ?>"></div>
      <?php endif; ?>
      <div class="block block-layered-nav block-layered-color">
         <div class="block-title">
            <strong><span>
                  Categories </span></strong>
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
                  Brand </span></strong>
         </div>
         <div class="block-content">
            <ol>
               <div id="search_filter_brand<?php echo $instance_id; ?>"></div>
            </ol>
         </div>
      </div>
      <div class="block block-layered-nav block-layered-color">
         <div class="block-title">
            <strong><span>
                  Rating </span></strong>
         </div>
         <div class="block-content">
            <ol>
               <div id="search_filter_rating<?php echo $instance_id; ?>"></div>
            </ol>
         </div>
      </div>
      <?php if (!empty($this->app->get('product_specs'))) : ?>
         <div class="side_box mobile_side_box" data-id="search_filter_specs<?php echo $instance_id; ?>">
            <div class="block block-layered-nav block-layered-color">
               <?php foreach ($this->app->get('product_specs') as $key => $spec) : ?>
                  <div id="search_filter_<?php echo str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $key));
                                          echo $instance_id; ?>"></div>
               <?php endforeach; ?>
            </div>
         </div>
      <?php endif;
      ?>
      <?php if (!$master_search) : ?>
      <tmpl type="modules" name="account-page-sidebar" />
      <?php endif; ?>
   </div>
   <?php
   $headers = \Base::instance()->get('HEADERS');
   if (\Audit::instance()->isBot() && preg_match('/(roger|dot)bot/i', $headers['User-Agent']) && !empty($item)) {
      $aggregate = [
         [
            '$match' => [
               'sales_channels.slug' => \Base::instance()->get('sales_channel'),
               "_id" => new \MongoDB\BSON\ObjectID((string) $item->id),
            ]
         ],
         [
            '$project' => [
               '_id' => 1,
               'title' => 1,
               'parent' => 1,
               'sales_channels' => 1,
               'ancestor_lookup._id' => 1
            ]
         ],
         [
            '$graphLookup' => [
               'from' => 'common.categories',
               'startWith' => '$parent',
               'connectFromField' => '_id',
               'connectToField' => 'parent',
               'as' => 'ancestor_lookup',
               'maxDepth' => 10,
               'depthField' => 'depth',
               'restrictSearchWithMatch' => [
                  'sales_channels.slug' => \Base::instance()->get('sales_channel')
               ]
            ]
         ],
         [
            '$unwind' => [
               'path' => '$ancestor_lookup',
               'includeArrayIndex' => 'indexName',
               'preserveNullAndEmptyArrays' => false
            ]
         ],
         [
            '$unwind' => [
               'path' => '$ancestor_lookup._id',
               'includeArrayIndex' => 'index2Name',
               'preserveNullAndEmptyArrays' => false
            ]
         ],
         ['$group' => ['_id' => '$ancestor_lookup._id']]
      ];
      $categoryAndChildren = (new \Shop\Models\Categories)->collection()->aggregate($aggregate);
      $categoryIds = [];
      foreach ($categoryAndChildren as $doc) {
         $categoryIds[] = $doc['_id'];
      }

      $productCursor = (new \Shop\Models\Products)->collection()->aggregate([
         [
            '$match' => [
               'categories.id' => ['$in' => $categoryIds],
               'publication.sales_channels.slug' => \Base::instance()->get('sales_channel'),
               'publication.status' => 'published',
               'product_type' => ['$nin' => ['service', 'matrix_subitem']],
               'policies.group_only' => ['$ne' => 1]
            ]
         ],
         [
            '$project' => [
               'canonical' => [
                  '$concat' => ['/part/', '$slug']
               ],
               'title' => 1
            ]
         ]
      ]);
      foreach ($productCursor as $doc) {
         echo <<<HTML
               <a class="mozbot-hack-a-majig"href="{$doc['canonical']}">{$doc['title']}</a>
            HTML;
      }
   }

   ?>
</div>
</div>