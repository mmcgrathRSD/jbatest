<?php $base = \Dsc\Url::full(false);
	            	 $activeVechile =  \Dsc\System::instance()->get('session')->get('activeVehicle'); 

	            	?>	

	<div class="col-md-10 col-xs-12 ymm-sort" style="margin-left: 0 !important;">
	   <div class="ymmSortHeightOdd text-left">
		   <span class="verCenter "><h3 class="marginTop">Showing Results: “<?php echo @$terms; ?>”</h3></span>
	   </div>
		
	  </div>
		<div class="col-lg-2 col-md-2 hidden-xs">
			<h3 class="cat-title paddingTop cat-title-section marginTopNone text-center">
				<span class="cc-font-r"><small><a href="" id="newSearch"><i class="fa fa-search">&nbsp;&nbsp;</i>NEW SEARCH</a></small></span>
			</h3>
		</div>
	<div class="col-xs-12 search404 paddingTop">
	   
	   <div class="paddingBottom">
	      
		  
		    	<?php echo $this->renderView('NorthRidge/Site/Views::search/grid.php'); ?>
		  
	  </div> 
	</div>
