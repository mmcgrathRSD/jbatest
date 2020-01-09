<?php $url = $_SERVER['REQUEST_URI'];  ?>

<ul>
<?php $ids = array("engine" => '54eb506481498140488b4567', "suspension" => '54ee5ab2814981db408b4568', 'drivetrain' => '54907721a0313cbf7e8b457e'  ); ?>
<?php foreach($ids as $id): ?>
<?php 
$top = (new \Shop\Models\Categories)->setState('filter.id', $id)->getItem();
$secondary = $top->getChildCategories();
?>


<li >
<a class="dropdown-toggle disabled <?php echo (strpos($url,'shop/category/engine') ? 'active' : ''); ?>" data-toggle="dropdown" href="/shop/category<?php echo $top->path; ?>"><?php echo $top->title; ?></a>
	<ul>
		<?php foreach($secondary as $subcat) : ?>
		<?php $third = $subcat->getChildCategories(); ?>
		<li class="visible-xs"><a href="#" class="goBack active secondHead" data-toggle="dropdown"><i class="glyphicon glyphicon-chevron-left"></i>Back</a></li>
		<li><a href="/shop/category<?php echo $subcat->path; ?>"><?php echo $subcat->title?><i class="visible-xs pull-right glyphicon glyphicon-chevron-right"></i></a>
			<?php if(!empty($third)) : ?>
			<ul class="list-unstyled linkList">
				<?php foreach($third as $thirdcat) : ?>
				<li><a href="/shop/category<?php echo $thirdcat->path; ?>"><?php echo $thirdcat->title; ?></a></li>
				<?php endforeach; ?>
			</ul>
			<?php endif; ?>
		</li>
		<?php endforeach; ?>
	</ul>
</li>
<?php endforeach; ?>
</ul>
