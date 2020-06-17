<?php
$sales_channel = \Base::instance()->get('sales_channel');
?>
<div class="header-container full-header">
   <div class="header header-2 site-width">
      <div class="table-container">
         <div class="table-cell v-align-cell logo-container">
            <a href="/" title="SubiSpeed - 2015 WRX / STI Parts and Accessories" class="logo">
            <strong><?php echo \Base::instance()->get('meta.title'); ?></strong>
            <img class="retina logo-image" width="240" height="42" src="https://www.subispeed.com/media/olegnax/athlete/subispeed_logo.png" alt="SubiSpeed - Your source for Subaru WRX / STI parts!">
            <div class="logo-image-div"></div>
            </a>
         </div>
         <?php if($checkoutmode == 0) : ?>
            <div class="table-cell header-info-container">
               <div class="relative">
                  <div class="top-links-container ">
                     <div class="header-switch header-cart" id="my-cart">
                        <?php echo  $this->renderLayout('Shop/Site/Views::cart/myCart.php'); ?>
                     </div>
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
                     <form class="searchautocomplete UI-SEARCHAUTOCOMPLETE" action="/search" method="get" data-tip="" data-minchars="6" data-delay="600">
                        <label for="search">Search</label>
                        <div class="nav">
                           <div class="nav-search-in" id="header_ymm_search_select_wrapper">
                              <span class="category-fake UI-CATEGORY-TEXT" id="header_ymm_search_span">All</span>
                              <span class="nav-down-arrow"></span>
                              <select name="cat" class="category UI-CATEGORY" style="width: 47.2344px;" id="header_ymm_search_select">
                              <?php 
                                 $searchDropdown = \Dsc\System::instance()->get('session')->get('search_dropdown');
                              ?>
                                 <option data-hierarchy="" value="0" <?php echo empty($searchDropdown) ? 'selected="selected"' : ''; ?> >All</option>
                                 <?php 
                                 
                                 foreach((new \Shop\Models\Categories)->collection()->find([
                                    'search_dropdown' => true,
                                    '$or' => [
                                          ['sales_channels.0' => ['$exists' => false]],
                                          ['sales_channels.0' => ['$exists' => true], 'sales_channels.slug' => $sales_channel]
                                       ]
                                    ]) as $doc){ ?>
                                    <option data-hierarchy="<?php echo $doc['hierarchical_categories']; ?>" value="<?php echo $doc['path']; ?>" <?php echo !empty($searchDropdown) && $searchDropdown['path'] === $doc['path'] ? 'selected="selected"' : ''; ?>><?php echo $doc['title']; ?></option>
                                 <?php 
                                    }
                                 ?>
                              </select>
                           </div>
                           <div class="nav-input UI-NAV-INPUT" style="padding-left: 47.2344px;" id="header_ymm_search_input_wrapper">
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
         <?php endif; ?>
      </div>
      <?php if($checkoutmode == 0) : ?>
         <div class="header-nav-wide">
            <div class="nav-container olegnaxmegamenu icons-black">
               <div class="nav-top-title">
                  <div class="icon"><span></span><span></span><span></span></div>
                  <a href="#">Navigation</a>
               </div>
               <ul id="nav">
               <?php
               $topNav = (new \Admin\Models\Navigation)->setCondition('title', $sales_channel)->getItem();
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
      <?php endif; ?>
   </div>
</div>