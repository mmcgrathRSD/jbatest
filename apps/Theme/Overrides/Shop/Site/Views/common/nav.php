<?php 
   $cart = \Shop\Models\Carts::fetch();
   // var_dump($cart);die('cartz');
?>

<div class="header-container full-header">
   <div class="header header-2 site-width">
      <div class="table-container">
         <div class="table-cell v-align-cell logo-container">
            <a href="/" title="SubiSpeed - 2015 WRX / STI Parts and Accessories" class="logo">
            <strong>SubiSpeed - 2015 WRX / STI Parts and Accessories</strong>
            <img class="retina" width="240" height="42" src="https://www.subispeed.com/media/olegnax/athlete/subispeed_logo.png" alt="SubiSpeed - Your source for Subaru WRX / STI parts!">
            </a>
         </div>
         <div class="table-cell header-info-container">
            <div class="relative">
               <div class="top-links-container ">
                  <!-- cart BOF -->
                  <div class="header-switch header-cart">
                     <a class="header-switch-trigger summary icon-white" href="/shop/cart/">
                     <span>My Cart</span><span class="qty"><?php echo count($cart->get('items')); ?></span>	</a>
                     <div class="header-dropdown" style="display: none; opacity: 0;">
                        <div class="cart-promotion"><strong>Shopping Cart</strong></div>
                        <p class="block-subtitle text-recently">Recently added item(s)</p>
                        <ol id="cart-sidebar" class="mini-products-list">
                        <?php
                         $lastThreeItems = array_reverse(array_map(function($item){
                           return [
                               'slug' => $item['product']['slug'],//or this?
                               'hash' => $item['hash'],//or this?
                               'price' => $item['price'],
                               'quantity' => $item['quantity'],
                               'model_number' => $item['model_number'],
                               'title' => $item['product']['title'],
                               'title_suffix' => $item['product']['title_suffix'],//umm or this as well?
                               'image' => \Shop\Models\Products::product_thumb($item['image']),
                           ];
                       }, array_splice($cart->get('items'), -3)));
                        foreach($lastThreeItems as $item){ ?>
                        <li class="item clearfix">
                       
                              <a href="/part/<?php echo $item['slug']; ?>" title="<?php echo "{$item['title']} - {$item['title_suffix']}"; ?>" class="product-image">
                              <img src="<?php echo \Shop\Models\Products::product_thumb($item['image']); ?>" alt="<?php echo "{$item['title']} - {$item['title_suffix']}"; ?>"></a>
                              <div class="product-details">
                                 <a href="#" title="Remove This Item" onclick="return confirm('Are you sure you would like to remove this item from the shopping cart?');" class="btn-remove icon-white">Remove This Item</a>
                                 <a href="#" title="Edit item" class="btn-edit icon-white">Edit item</a>
                                 <p class="product-name"><a href="#"><?php echo "{$item['title']} - {$item['title_suffix']}"; ?></a></p>
                                 <strong><?php echo $item['quantity']; ?></strong> x <span class="price">$<?php echo number_format($item['price'], 2); ?></span>											
                              </div>
                        </li>
                        <?php } ?>
                        </ol>
                        <div class="subtotal">
                           <span class="label">Total:</span> <span class="price">$<?php echo number_format(array_sum(array_map(function($item){ return $item['price'] * $item['quantity'];}, $cart['items'])), 2); ?></span>												
                        </div>
                        <div class="buttons clearfix">
                           <button type="button" title="View Cart" class="button inverted btn-continue" onclick="setLocation('https://www.subispeed.com/checkout/cart/')"><span><span>View Cart</span></span></button>
                           <button type="button" title="Checkout" class="button btn-checkout" onclick="setLocation('https://www.subispeed.com/checkout/onepage/')"><span><span>Checkout</span></span></button>
                        </div>
                     </div>
                  </div>
                  <!-- cart EOF -->						
                  <div class="top-links">
                     <ul class="links">
                        <li class="first"><a href="/shop/account" title="My Account">My Account</a></li>
                        <li><a href="/shop/wishlist" title="My Wishlist">My Wishlist</a></li>
                        <li><a href="/shop/checkout" title="Checkout" class="top-link-checkout">Checkout</a></li>
                        <?php if($this->auth->getIdentity()->id) : ?>
                        <li class=" last"><a href="/logout" title="Log Out">Log Out</a></li>
                        <?php else : ?>
                        <li class=" last"><a href="/sign-in" title="Log In">Log In</a></li>
                        <?php endif; ?>
                        
                     </ul>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="nav-search-container search-visible">
                  <form class="searchautocomplete UI-SEARCHAUTOCOMPLETE" action="https://www.subispeed.com/catalogsearch/result/" method="get" data-tip="" data-url="//www.subispeed.com/searchautocomplete/ajax/get/" data-minchars="6" data-delay="600">
                     <label for="search">Search</label>
                     <div class="nav">
                        <div class="nav-search-in">
                           <span class="category-fake UI-CATEGORY-TEXT">All</span>
                           <span class="nav-down-arrow"></span>
                           <select name="cat" class="category UI-CATEGORY" style="width: 47.2344px;">
                           <?php 
                              $searchDropdown = \Dsc\System::instance()->get('session')->get('search_dropdown');
                           ?>
                              <option value="0" <?php echo empty($searchDropdown) ? 'selected="selected"' : ''; ?> >All</option>
                              <?php 
                              
                              foreach((new \Shop\Models\Categories)->collection()->find([
                                 'search_dropdown' => true,
                                 '$or' => [
                                       ['sales_channels.0' => ['$exists' => false]],
                                       ['sales_channels.0' => ['$exists' => true], 'sales_channels.slug' => \Base::instance()->get('sales_channel')]
                                    ]
                                 ]) as $doc){ ?>
                                 <option value="<?php echo $doc['path']; ?>" <?php echo !empty($searchDropdown) && $searchDropdown['path'] === $doc['path'] ? 'selected="selected"' : ''; ?>><?php echo $doc['title']; ?></option>
                              <?php 
                                 }
                              ?>
                           </select>
                        </div>
                        <div class="nav-input UI-NAV-INPUT" style="padding-left: 47.2344px;">
                           <input class="input-text UI-SEARCH" type="text" autocomplete="off" name="q" value="" maxlength="50" id="search-box">
                           <input class="input-text UI-SEARCH" style="display: none;" type="text" autocomplete="off" name="q" value="" maxlength="50" id="search-box-mobile">
                        </div>
                        <div class="searchautocomplete-loader UI-LOADER" style="display:none;"></div>
                     </div>
                     <div class="nav-submit-button">
                        <button type="submit" title="Go" class="button">Go</button>
                     </div>
                     <div style="display:none" class="searchautocomplete-placeholder UI-PLACEHOLDER"></div>
                  </form>
                  <div class="nav-container header-nav-txt std">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div class="header-nav-wide">
         <div class="nav-container olegnaxmegamenu icons-black">
            <div class="nav-top-title">
               <div class="icon"><span></span><span></span><span></span></div>
               <a href="#">Navigation</a>
            </div>
            <ul id="nav">
            <?php
            $topNav = (new \Admin\Models\Navigation)->setCondition('title', 'Top Nav')->getItem();
            $menuId = (string) $topNav->id;
            $tops = (new \Admin\Models\Navigation)->setState('filter.parent', $menuId)->setState('order_clause', array( 'tree'=> 1, 'lft' => 1 ))->getList();

            foreach($tops as $key => $top):
                echo '<li class="level0 nav-' . ($key + 1) . ' first  level-top ';
                
                if(!empty($top->display_type) && $top->display_type == 'title') {
                    echo 'default';
                } else {
                     echo 'wide';
                } 
                 
                echo ' parent parent-fake parent"><a href="/scp/' . urlencode($top->slug) . '" class=""><span>' . $top->title . '</span></a>';

               if(!empty($top->details['content'])) {
                  echo '<div class="megamenu-dropdown">' . $top->details['content'] . '</div>';
               }
                  
                  echo '</li>';
            endforeach;
            ?>
            </ul>
         </div>
      </div>
   </div>
</div>