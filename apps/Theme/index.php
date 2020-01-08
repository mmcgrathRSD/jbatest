<!DOCTYPE html>

<html lang="en" class="default <?php echo \Base::instance()->get('sales_channel'); echo @$html_class; if($checkoutmode != 0) { echo ' page_checkout'; } ?> <?php if($this->session->get('activeVehicle')) { echo 'ymm_set'; } else { echo 'ymm_not_set'; } ?>" >
	<?php echo $this->renderView('Theme/Views::common/head.php'); ?>

	<body role="document">
		<!-- Google Tag Manager (noscript) -->
		<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PFV89B"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<!-- End Google Tag Manager (noscript) -->
		<div role="main">
			<?php echo $this->renderLayout('Shop/Site/Views::common/nav.php')?>

			<div id="algolia_master" class="algoliaMaster container" <?php if(empty($_GET['q'])) : ?>style="display: none"<?php endif; ?>>
				<?php echo $this->renderView('Search/Site/Views::search/list.php'); ?>
			</div>
			<div id="mainBody" class="bodyMaster" <?php if(!empty($_GET['q'])) : ?>style="display: none"<?php endif; ?>>
				<div class="container bodyMaster">
					<div class="pageBody clearfix container-fluid ">
						<?php echo $this->renderView('Theme/Views::system-messages.php'); ?>
						<tmpl type="view" />
						</div>
				</div>
			</div>

			<div class="container">
				<?php echo $this->renderView('Theme/Views::common/footer.php'); ?>
				<?php echo $this->renderView('Search/Site/Views::search/algolia_js.php'); ?>
			</div>
		</div>
		<?php echo $this->renderLayout('Assets/Site/Views::common/footer_tags.php')?>
	</body>
</html>
