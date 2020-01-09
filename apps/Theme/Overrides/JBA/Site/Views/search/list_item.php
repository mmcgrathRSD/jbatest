<?php $item = $this->item; ?>
<?php $item->url = './shop/product/' . $item->{'slug'}; ?>

<article id="product-<?php echo $item->id; ?>" class="list-item product-<?php echo $item->id; ?>">
    <?php if ($item->{'featured_image.slug'}) { ?>
    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 vcenter">
        <div class="product_listimage">
            <a href="<?php echo $item->url; ?>">
            <?php   if (((int) $item->get('flag.enabled') > 0) && strlen($item->get('flag.text')) ) { ?>
           		 <div class="flag orange"><span><?php echo $item->get('flag.text'); ?></span></div>
           	<?php } ?>
                <img class="img-responsive" src="<?php echo $item->featuredImageThumb();; ?>" title="<?php echo $item->{'metadata.title'}; ?>" alt="<?php echo $item->{'metadata.title'}; ?>">
            </a>
            
            
        </div>
    </div>
    <?php } else { ?>
    
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 vcenter">
        <div class="product_listimage">
            <a href="<?php echo $item->url; ?>">
                <img class="img-responsive" src="/theme/img/no-photo.png">
            </a>
                
        </div>
    </div>
    <?php } ?>
  
        
        
        	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 vcenter "><a href="<?php echo $item->url; ?>"><h4 class="marginTopNone"><strong><?php echo $item->title; ?></strong></h4></a>
        	
        	
        	<?php if(!empty($item->{'ymmtext.single'})) : ?>
			 <small class="ymmlist"><?php echo str_replace(',','<br>',trim($item->{'ymmtext.single'})); ?></small></br>
			 <?php else:?>
			  <small class="ymmlist"><small>Universal Product</small></small></br>
			<?php endif; ?>
      
        <h5 class="price">
            <?php if ($item->{'policies.hide_price'}) { ?>
                <p>Call for price.</p>
            <?php } else { ?>           
              
                <a href="<?php echo $item->url; ?>">
                    <strong class="newPrice"><?php echo \Shop\Models\Currency::format( $item->price() ); ?></strong>
                </a>
                <?php if (((int) $item->get('prices.list') > 0) && (float) $item->get('prices.list') != (float) $item->price() ) { ?>
              	  
                    <span class="strikePrice"><?php echo \Shop\Models\Currency::format( $item->{'prices.list'} ); ?></span>

                <?php } ?>
                <?php if(!empty($item->get('review_rating_counts.overall'))) : ?>
                <?php 
                	$stars= $item->get('review_rating_counts.overall');
                	$empty = (5 - $stars );
                	$count= $item->get('review_rating_counts.total');
                ?>
                <div class="paddingTopMd product-rating">
                <?php for ($i = 1; $i <= $stars; $i++) {
                	echo ' <i class="fa fa-star gold"></i>';
                };

                ?>
                <?php for ($i = 0; $i < $empty; $i++) {
                	echo ' <i class="fa fa-star-o gold"></i>';
                };

                ?>
               <small>(<?php echo $count; ?>)</small> </div>
                <?php endif; ?>
            <?php } ?>        
        </h5>
        

        	
        	</div>
        
       		 <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 vcenter paddingTopSm"> 
				<?php $variants = $item->variantsAvailable();  
				if(count($variants) == 0) { $variant_id = null; }
				if(count($variants) == 1) { $variant_id = $variants[0]['id']; }
				if(count($variants) >  1) { 
					$variant_id = null; $link = true; }
				//else
				
				
				?>	
				<?php  if (isset($link) ) { ?>
				<a href="/shop/product/<?php echo $item->slug; ?>" class="btn btn-block btn-warning">See Product Page</a>
				
				<?php  } elseif ( $item->confirmFitment () != "noymm" ) { ?>
					<?php if($variant_id) : ?>
		  			<form action="./shop/cart/add" method="post" class="addToCartForm">
		                    <input type="hidden" name="variant_id" value="<?php echo $variant_id; ?>" class="variant_id" />
							<input type="hidden" class="form-control" value="1" placeholder="Quantity" name="quantity" id="quantity" />
							<button class="btn btn-warning btn-block addToCartButton">
								<i class="glyphicon glyphicon-shopping-cart"></i> ADD TO CART
							</button>
		 			</form>
		 			<?php endif; ?>
				<?php } else { ?>
					<a href="#ymm" class="btn btn-block btn-warning" data-toggle="modal" data-target="#ymmModal">SELECT YOUR CAR  <i class="fa fa-chevron-right"></i></a>
				
				<?php  } ?>
				
				
	        	<div class="paddingTopMd" ><span class="detail-line">Part #: <?php echo $item->{'tracking.model_number'}; ?></div>
		        	
	        	<?php if(isset($comparable)) : ?>
	        	<div class="compareBox paddingTop">
	        		<div class="compareCheckWrapper"><input type="checkbox" name="compare[]" class="compareCheck" value="<?php echo $item->id;?>" data-route="/shop/compare<?php echo $comparecategory->path ?>"> Add to Compare</div>
	        		
	        		<button class="btn btn-default btn-block btn-xs compareButton paddingTopSm">
								COMPARE
					</button>
	        	</div>
	        	<?php endif;?>
	        	<div class></div>
	        	

        	</div> 
        

</article>

