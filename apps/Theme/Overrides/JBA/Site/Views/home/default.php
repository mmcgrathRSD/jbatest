	</div> <!-- Closing page body for home page content to go outside container -->
</div> <!-- Closing container -->
<?php $modules = \Modules\Factory::load('homepage-slider', '/');  ?>
	<?php foreach($modules as $key=> $module) :?>
			<?php echo $module->render(); ?>
	<?php endforeach; ?>
<div>

<tmpl type="modules" name="homepage-mid" />

<div class="mobile-banners visible-xs homeXsBrands"><a href="/shop-by-category"><img src="/theme/img/shop-by-cat.jpg" class="category-banner"></a></div><br class="visible-xs" />

<tmpl type="modules" name="topbrands" />

<div class="container"> <!-- Starting page body/container for footer -->
	<div class="pageBody clearfix container-fluid">

		<div class="paddingBottom hidden-xs">
			<tmpl type="modules" name="homemarketingimages" />
    	</div>
		<tmpl type="modules" name="homepage-end" />
		</div>