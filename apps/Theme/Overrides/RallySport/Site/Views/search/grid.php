


<?php $activeVechile =  \Dsc\System::instance()->get('session')->get('activeVehicle'); ?>

<div id="productsHolder">

   <div class="row ymm-sort">
	   <div class="col-xs-12 col-sm-2 col-md-2 ymmSortHeightOdd">
		   <span class="verCenter ">Showing <span id="stats"></span><?php if(!empty($activeVechile)) : ?> for your<?php endif; ?>: </span>
	   </div>
	  
	   <div class="col-xs-12 col-sm-5 col-md-7 paddingLNone ymmSortHeightOdd">
		   <h1 class="verCenter marginTopNone marginBottomNone ymmTitleSort">
			   <?php if(!empty($activeVechile)) : ?>
			   <?php echo strtoupper($activeVechile['vehicle_year'].' '.$activeVechile['vehicle_make'].' '.$activeVechile['vehicle_model'].' '.$activeVechile['vehicle_sub_model']); ?>
			   <?php endif; ?>
		   </h1>
	   </div>
	  </div>
	  
	  <div id="hits-container"></div>
    <div id="pagination-container"></div>
</div>	          





