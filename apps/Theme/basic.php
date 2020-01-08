<!DOCTYPE html>

<html lang="en" class="default <?php echo @$html_class; ?>" >
	<?php echo $this->renderView('Theme/Views::common/head.php'); ?>

	<body role="document">
		<div role="main">
			<div class="container">
				<div class="pageBody clearfix container-fluid ">
					<?php echo $this->renderView('Theme/Views::system-messages.php'); ?>
					<tmpl type="view" />
     			</div>
			</div>
		</div>
	</body>
</html>
