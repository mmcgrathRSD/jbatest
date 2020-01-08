<?php $mobile = \Audit::instance()->isMobile(); ?>
<div class="row">

<?php if(!$mobile) : ?>
<div class="col-md-8 col-sm-12 col-xs-12">
    <?php echo $this->renderView ( 'Shop/Site/Views::product/blocks/description.php' ); ?>
</div>
<?php endif; ?>
<div class="col-md-4 col-sm-12 col-xs-12">												
    <?php
        $app = \Dsc\System::instance()->get('app');
        $featured_img = $app->get('SCHEME').'://'.$app->get('HOST').'/asset/'.$item->{'featured_image.slug'};
    ?>
    <div id="productSocial">
      <h5>SHARE THIS </h5>
      <span>|</span>
      <a class="social-link" href="https://www.facebook.com/Rallysportdirect"  data-type="facebook" data-title="<?php echo $item->title; ?>" data-url="<?php echo $app->get('REALM');?>" data-img="<?php echo $featured_img; ?>"><i class="fa fa-facebook " ></i></a>
      <span>|</span>
      <a class="social-link" href="https://twitter.com/RallySportTweet" data-type="twitter" data-url="<?php echo $app->get('REALM'); ?>" data-title="<?php echo $item->title; ?>"><i class="fa fa-twitter "></i></a>
      <span>|</span>
		<a class="social-link" href="https://pinterest.com"  data-type="pinterest" data-title="<?php echo $item->title; ?>" data-url="<?php echo $app->get('REALM');?>" data-img="<?php echo $featured_img; ?>"><i class="fa fa-pinterest" aria-hidden="true"></i></a>
      <span>|</span>
		<a class="social-link" href="https://plus.google.com" data-type="gplus" data-title="<?php echo $item->title; ?>"  data-url="<?php echo $app->get('REALM'); ?>" ><i class="fa fa-google-plus" aria-hidden="true"></i></a>
    	
		<span>|</span>
        </div>
    <div class="clearfix"></div>
    <?php switch ($item->{'policies.returns'}) : ?>
<?php case 'standard':?>

<?php break;?>
<?php case 'rsd':?>
<div class="textModalBody" id="guaranteeModal">
	<h3>RallySport Guarantee - 30 Days</h3><br>
	Items that are covered under the RallySport Guarantee offer a 30 day no questions asked return policy. <br> <br>
	RallySport Guarantee items may be returned within 30 days of the original invoice date and must include all the original parts and contents for a refund. <br> <br>
	The RallySport Guarantee does not apply to products that have been modified, damaged, misused or abused. All returns require an RMA (Return Merchandise Authorization) Number, and freight charges are strictly non-refundable. For more information regarding our freight refund policy, please refer to our return policy page found <a href="pages/returns">HERE</a>.
</div>
<hr>
<a href="#" data-toggle="modal" class="textModal guaranteeModal" >
	<div class="paddingBottom  text-center">
		<i href="#" class="sprite sprite-guarantee-light text-center sprite-inline"></i>
	</div>
</a>


<?php break;?>
<?php endswitch; ?>
</div>
</div>
<?php echo $this->renderView ( 'Shop/Site/Views::product/blocks/specs.php' ); ?>
<?php if($mobile) : ?>
<div class="row">
	<div class="col-md-8 col-sm-12 col-xs-12">
		<?php echo $this->renderView ( 'Shop/Site/Views::product/blocks/description.php' ); ?>
	</div>
	<?php endif; ?>
</div>