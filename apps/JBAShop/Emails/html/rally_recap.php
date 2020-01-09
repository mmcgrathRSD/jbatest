{{@default_header}}

<?php if(!empty($settings)) : ?>
    <?php if(!empty($settings->get('recap.message'))) : ?>
    <br>
    <blockquote><?php echo $settings->get('recap.message');  ?>
    <?php if(!empty($settings->get('recap.credit'))) : ?>
    <br>
    <div style="text-align: right;">
    --<?php echo $settings->get('recap.credit'); ?>
    </div>
    <?php endif; ?>
    </blockquote>
    <?php endif; ?>
<?php endif; ?>


<?php if(!empty($products)) : ?>
<hr>
<h2>New and Important Products</h2>
<table style="width: 100%;">

  <?php foreach($products as $item) : ?>
    <tr>
        <td width="75px" >
            <?php if (\Dsc\ArrayHelper::get($item, 'featured_image.slug')) { ?>
            <img width="75px" style="width: 75px;" src="<?php echo  \JBAShop\Models\Products::product_thumb(\Dsc\ArrayHelper::get($item, 'featured_image.slug'));?>" alt="" />
            <?php } ?>
        </td>
        
        <td style="vertical-align: middle;color:#5e5e5e; font-family: 'Lato',sans-serif;font-size: 14px;">
            <h5>
               <a href="<?php echo $base_url; ?>/shop/product/<?php echo $item->slug; ?>"><?php echo \Dsc\ArrayHelper::get($item, 'title'); ?></a>
            </h5>
             <?php if( !empty($item->get('copy')) ): ?>
             
             <?php echo substr(strip_tags($item->get('copy')), 0, 400); ?>...<a href="<?php echo $base_url; ?>/shop/product/<?php echo $item->slug; ?>">more</a>
             <?php endif; ?>
        </td>
        
       
    </tr>   
    
    <tr>
    <td colspan="2">
    <?php if(!empty($item->get('manufacturer.title'))) : ?>
    <small>Brand:</small> <strong><?php echo $item->get('manufacturer.title'); ?></strong> 
    <?php endif; ?>	
    			Pricing : 
				<?php if(!empty($item->get('prices.list'))) : ?>
                <small>Retail:</small> <strong><?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'prices.list')  ); ?></strong> | 
           		<?php endif; ?>	
           		<?php if(!empty($item->get('prices.map'))) : ?>
                <small>MAP:</small> <?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'prices.map')  ); ?> | 
           		<?php endif; ?>	
           		<?php if(!empty($item->get('prices.msrp'))) : ?>
                <small>MSRP:</small> <?php echo \Shop\Models\Currency::format( \Dsc\ArrayHelper::get($item, 'prices.msrp')  ); ?>
           		<?php endif; ?>	
           
        </td>
    </tr>
    
      
    <?php if( !empty($item->get('policies.recap_message')) ): ?>
    <tr>
        <td colspan="2" style="background-color: #cccccc;">
             <label>RSD Message:</label>
             <strong>
             <?php echo $item->get('policies.recap_message'); ?>
             </strong>
        </td>
    </tr>
             <?php endif; ?>    
    
    
    <?php endforeach; ?>
</table>

<?php endif; ?>


<?php if(!empty($ymms)) : ?>
<hr>
<h3>New Vehicles have been added!</h3>
<ul>
<?php foreach ($ymms as $ymm) :?>
<li><a href="<?php echo $base_url; ?>/shop/vehicle/<?php echo $ymm['slug']; ?>"><?php echo $ymm['title'];?></a> | <small>(<a href="https://www.google.com/search?q=<?php echo urlencode($ymm['title']); ?>&source=lnms&tbm=isch&sa=X">Google Images Link</a>)</small></li>
<?php endforeach;?>
</ul>

<p> Take a minute to research the new vehicles!</p>

<?php endif; ?>


<?php if(!empty($brands)) : ?>
<hr>
<h3>New Brands have been added!</h3>
<ul>
<?php foreach ($brands as $brand) :?>
<li><a href="<?php echo $base_url; ?>/shop/brand/<?php echo $brand['slug']; ?>"><?php echo $brand['title'];?></a></li>
<?php endforeach;?>
</ul>
<p> Take a minute to research the brand and the products. Customers will likely have questions about these soon!</p>

<?php endif; ?>

<?php 

$sec = time() - (date('G') * 3600 + date('i') * 60);
?>
<hr>
<h3>Orders from Yesterday</h3>
<table width="610px">
<tr>
<td style="text-align: center;">Desktop  Orders<br><?php $desktop =  \JBAShop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true],'device_type' => 'desktop-tablet']); echo $desktop; ?></td>
<td style="text-align: center;">
Mobile  Orders <br><?php $mobile =  \JBAShop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true], 'device_type' => 'mobile']); echo $mobile; ?>
</td>
</tr>
</table>
<div> <strong><em> Mobile consisted of <?php echo round(($mobile/($desktop + $mobile))*100, 2)?>% of yesterdays sales. </em></strong></div>





<br><br><br><br><br>







{{@default_footer}}
