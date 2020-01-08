<?php 
$app = \Dsc\System::instance()->get('app');
$assets = $app->get('nav_imgs');
if( $assets == null ){
	$model = \Marketing\Models\Settings::fetch();
	$assets = (array)$model->navigation_images;
	$app->set('nav_imgs', $assets);
}
?>

<?php if(!empty($assets[$nav_category]) && !empty($assets[$nav_category]['link'])) :
	$asset = $assets[$nav_category];
	?>
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	<a href="<?php echo $asset['link'] ; ?>">
		<img src="<?php echo \Shop\Models\Assets::render($asset['image']);?>" class="img-responsive center-block">
	</a>
</div>
<?php endif;?>