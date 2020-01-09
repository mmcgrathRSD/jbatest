<?php $item = $this->item; ?>
<?php $item->url = './shop/product/' . $item->{'slug'}; ?>

<article id="product-<?php echo $item->id; ?>" class=" product-<?php echo $item->id; ?>" itemscope itemtype="http://schema.org/Product">
    <?php if ($item->{'featured_image.slug'}) { ?>
    <br/>
    <div class=" productImg">
            <a href="<?php echo $item->url; ?>">
            <?php   if (((int) $item->get('flag.enabled') > 0) && strlen($item->get('flag.text')) ) { ?>
           		 <div class="flag orange"><span><?php echo $item->get('flag.text'); ?></span></div>
           	<?php } ?>
                <img class="img-responsive" src="<?php echo $item->featuredImageThumb(); ?>" title="<?php echo $item->{'metadata.title'}; ?>" alt="<?php echo $item->{'metadata.title'}; ?>">
            </a>
    </div>
    <br/>
    <?php } else { ?>
    <br/>
        <div class=" productImg">
            <a href="<?php echo $item->url; ?>">
                <img class="img-responsive" src="/theme/img/no-photo.png">
            </a>
        </div>
 			<br/>
    <?php } ?>
  
        
        	<div class=" vcenter productName"><a href="<?php echo $item->url; ?>"><h4 class="marginTopNone gridTitle"><strong itemprop="name"><?php echo $item->title; ?></strong></h4></a>
        		<div class="" ><span class="detail-line fgGrey">#<span itemprop="sku"><?php echo $item->{'tracking.model_number'}; ?></span></div>
        	  <h5 class="price">
                   <div itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
                <?php if ($item->{'policies.hide_price'}) : ?>
		                 <a href="<?php echo $item->url; ?>">
		                    <strong class="newPrice"><strike><?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?></strike></strong>
		                </a>
		               
            <?php  else : ?>  
		              <a href="<?php echo $item->url; ?>">
		                    <strong class="newPrice" itemprop="price"><?php echo \Shop\Models\Currency::format( $item->price() ); ?></strong>
		                </a>
		                <?php if (((int) $item->get('prices.list') > 0) && (float) $item->get('prices.list') != (float) $item->price() ) { ?>
		              	  
		                   <!--  <span class="strikePrice"><?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?></span> -->
		
		                <?php } ?>
               <?php endif;?> 
              </div>
                <?php if(!empty($item->get('review_rating_counts.overall'))) : ?>
                <?php
                	$stars = $item->get('review_rating_counts.overall');
                	$count= $item->get('review_rating_counts.total');
                ?>
                <div  itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
					<meta itemprop="ratingValue" content="<?php echo $stars; ?>">
					<meta itemprop="reviewCount" content="<?php echo $count; ?>">
					<div class="paddingTopMd product-rating">
						<?php echo \JBAShop\Models\UserContent::outputStars($stars); ?>
						<small>(<?php echo $count; ?>)</small>
					</div>
				</div>
				<?php else:?>
				<br/>
                <?php endif; ?>
              
        </h5><br/>
        	<?php if(empty($item->{'universalpart'})) : ?>
	        	<?php if(!empty($item->{'ymmtext.single'})) : ?>
				        <?php if(empty($activeVehicle)) : ?>
							<small><small class="ymmlist fgGrey"><?php echo str_replace(',','',trim($item->{'ymmtext.single'})); ?></small></small>
						<?php endif;?>
				<?php endif;?>
			<?php else:?>
				 <small><small class="ymmlist">Universal Product</small></small>
			<?php endif; ?>
        	</div>
        
       		 <div class="vcenter paddingTopSm paddingLNone paddingRNone productPrice"> 
       		 	<div class="visible-xs"><br/></div>
				<?php
				$link = false;
				$variants = $item->variantsAvailable();  
				if(count($variants) == 0) { $variant_id = null; }
				if(count($variants) == 1) { $variant_id = $variants[0]['id']; }
				if(count($variants) >  1) { 
					$variant_id = null; $link = true; }
				//else
					
					
				if(empty($variant_id)) {
					$link = true;
				}
				
				if($item->product_type == 'group' || !empty($item->group_items)) {
					
					$link = false;
					//hack to make groups always have stuff
					$variant_id = $item->{'variants.0.id'};
				}
				
				
				?>




				<?php if($item->get('inventory_count') < 4 && $item->get('inventory_count') != 0) :?>
													<span class="lowStockQty">Only <?php echo $item->get('inventory_count'); ?> left in stock.</span>
													<?php endif; ?>
		
		        	
	        	<?php if($comparable) : ?>
	        	<div class="compareBox paddingTop hidden-xs hidden-sm">
	        		<div class="compareCheckWrapper"><input type="checkbox" name="compare[]" class="compareCheck" value="<?php echo $item->id;?>" data-route="/shop/compare<?php echo $comparecategory->path ?>"> Add to Compare</div>
	        		
	        		<button class="btn btn-default btn-block btn-xs compareButton paddingTopSm">
								Compare
					</button>
	        	</div>
	        	<?php endif;?>
	        	<div class></div>
	        	

        	</div> 
        

</article>

