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
               <!-- <form action="https://www.subispeed.com/checkout/cart/add/uenc/aHR0cHM6Ly93d3cuc3ViaXNwZWVkLmNvbS9zdWJhcnUtbWF0dGUtYmxhY2stYWMta25vYi1mdWxsLXJlcGxhY2VtZW50LTIwMTUtd3J4LXN0aS0yMDE0LWZvcmVzdGVyLTIwMTMtY3Jvc3N0cmVr/product/13193/form_key/PTT3lpSgzitk4OCG/" method="post" id="product_addtocart_form"> -->
                  <div class="product-category-title">
                     <h3><?php echo $item->manufacturer['title']; ?></h3>
                  </div>
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/image_modal.php')?>
                  <div class="product-shop ">
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/meta_links.php')?>
                  <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/price_box.php')?>
                     <div class="clear"></div>
                     <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/std.php')?>
                     <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/add_to_links.php')?>
                     <?php if($item->{'product_type'} != 'dynamic_group' && !\Dsc\ArrayHelper::get($item->policies, 'ships_email')) : ?>
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