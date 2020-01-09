
<?php 
$collection = (new \Shop\Models\Collections)->bind($module->model->cast());
$collection->products = array_filter(explode(',', $collection->products));
$conditions = \Shop\Models\Collections::getProductQueryConditions($collection);


$products = (new \JBAShop\Models\Products)->setParam('conditions', $conditions)->setParam('limit', 4)->getItems();

if(!empty($products)) : 
?>

	<div class="row ">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<h3><?php echo $module->model->title; ?></h3>
		</div>
	</div>
	
	<div class="row  paddingBottom">

	
	<?php 
		
	 switch (count($products)) {
		case 1:
		$class = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
			break;
		case 2:
		$class = 'col-lg-6 col-md-6 col-sm-6 col-xs-12';
	
			break;
		case 3:
		$class = 'col-lg-4 col-md-4 col-sm-12 col-xs-12 ';

			break;
		case 4:
		$class = 'col-lg-3 col-md-3 col-xs-12 col-sm-6';

			break;
		
		default:
		$notSane = 1;
		break;
	}    ?>
	
	
	
			<?php foreach($products as $product) : ?>
						<div class="<?php echo $class; ?>">
							<a href="/shop/product/<?php echo $product->slug; ?>">
							
							<?php /*<div class="flag blue"><span>NEW</span></div> */ ?>
							<img src="<?php echo $product->featuredImageThumb(); ?>" class="img-responsive">
							</a>
							
							<a href="/shop/product/<?php echo $product->slug; ?>">
								<h4><?php echo $product->title; ?></h4>
							</a>
								<h4><strong>$<?php echo $product->price(); ?></strong></h4>
						</div>
				<?php endforeach; ?>	
				
	</div>
<?php endif; ?>
