<div class="main row clearfix">
   <div class="col-main grid_18">
   <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/breadcrumbs.php')?>
      <div itemscope="" itemtype="http://schema.org/Product">
         <script type="text/javascript">
            var optionsPrice = new Product.OptionsPrice([]);
         </script>
         <div id="messages_product_view"></div>
         <div class="product-view">
            <div class="product-essential">
                  <div class="product-category-title">
                     <h3><?php echo $item->manufacturer['title']; ?></h3>
                  </div>
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/image_modal.php')?>
                  <div class="product-shop ">
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/meta_links.php')?>
                  <span itemprop="offers" itemscope="" itemtype="http://schema.org/offers">
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/price_box.php')?>
                  </span>
                     <div class="clear"></div>
                     <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/std.php')?>
                     <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/add_to_links.php')?>
                     <?php if(!in_array($item->{'product_type'},  ['matrix','dynamic_group']) && !\Dsc\ArrayHelper::get($item->policies, 'ships_email')) : ?>
                        <div class="wishListButton text-center add_to_wishlist" data-variant="<?php echo $item->get('tracking.model_number'); ?>">
                           <i class="glyphicon glyphicon-refresh spinning"></i>
                        </div>
                     <?php endif; ?>
                     <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/social_links.php')?>
                  </div>
                  <div class="clearer"></div>
                  <!-- </form> -->
                  
            </div>
            <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/description.php')?>
            
            			
            <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/lockup_reviews.php')?>
            
         </div>
      </div>
      <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/pop_up_text.php')?>
      <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/jba_product_script.php')?>
      <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/user_content.php')?>
   </div>
</div>