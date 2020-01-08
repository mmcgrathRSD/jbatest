<?php
$img = array();
$img[] = $module->model->{'img_1'};
$img[] = $module->model->{'img_2'};
$img[] = $module->model->{'img_3'};
$img[] = $module->model->{'img_4'};
$img[] = $module->model->{'img_5'};
$img[] = $module->model->{'img_6'};

$imgLink = array();
$imgLink[] = $module->model->{'imgLink_1'};
$imgLink[] = $module->model->{'imgLink_2'};
$imgLink[] = $module->model->{'imgLink_3'};
$imgLink[] = $module->model->{'imgLink_4'};
$imgLink[] = $module->model->{'imgLink_5'};
$imgLink[] = $module->model->{'imgLink_6'};

$module1 = $module->model->{'img_bg'};
$module2 = $module->model->{'title'};
$module3 = $module->model->{'desc'};
$module4 = $module->model->{'img_bg_mobile'};


?>

<div class="homeBrands hidden-xs" style="background-image: url(<?php echo $module1; ?>);">
	<div class="container">
		<div class="row">
			<div class="col-md-5 col-sm-12">
				<h4>
					<?php echo $module2; ?>
				</h4>
				<?php echo $module3; ?><br />
				<a href="/brands" class="brandsButton"><button class="btn btn-primary footerSubmit" style="margin-top: 9px; width: 50% !important; font-size: 1em;">See All Brands</button></a>
			</div>
			<div class="col-md-7 col-sm-12 text-center">
				<div>
					<a href="<?php echo $imgLink[0]; ?>"><?php echo cl_image_tag($img[0], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190, "class" => "pull-left")); ?></a>
					<a href="<?php echo $imgLink[1]; ?>"><?php echo cl_image_tag($img[1], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190)); ?></a>
					<a href="<?php echo $imgLink[2]; ?>"><?php echo cl_image_tag($img[2], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190, "class" => "pull-right")); ?></a>
				</div>
				<div>
					<a href="<?php echo $imgLink[3]; ?>"><?php echo cl_image_tag($img[3], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190, "class" => "pull-left")); ?></a>
					<a href="<?php echo $imgLink[4]; ?>"><?php echo cl_image_tag($img[4], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190)); ?></a>
					<a href="<?php echo $imgLink[5]; ?>"><?php echo cl_image_tag($img[5], array("crop" => "limit", "effect" => "mask", "height" => 60, "opacity" => 100, "width" => 190, "class" => "pull-right")); ?></a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="mobile-banners visible-xs homeXsBrands"><a href="/brands"><img src="<?php echo $module4; ?>" class="brand-banner"></a></div>
