<?php if($this->getIdentity()->role == 'staff') {
	$this->app->reroute('/admin/toolbox');
	
}   ?>

<div class="container"><?php echo $this->renderView('Admin/Views::dashboard/checkout_stats.php'); ?>
</div>

