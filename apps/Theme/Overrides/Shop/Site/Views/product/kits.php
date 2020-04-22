<div id="productsHolder" class="productInfo sticky_parent product-view product-img-box">
		<div class="pdpCenterMobile">
			<div id="dynamicMainGroup" class="kitMaster">
				<?php if(\Audit::instance()->isMobile()) :  ?>
					<?php echo $this->renderLayout ( 'Shop/Site/Views::product/blocks/mobile/dynamic_group_main.php' ); ?>
				<?php else : ?>
					<?php echo $this->renderLayout ( 'Shop/Site/Views::product/blocks/desktop/dynamic_group_main.php' ); ?>
				<?php endif; ?>
			</div>
	</div>
</div>