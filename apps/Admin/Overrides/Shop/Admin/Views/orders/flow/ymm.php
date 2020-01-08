<div role="tabpanel" class="tab-pane customerStep" id="<?php echo $tabid; ?>">
	    <div>
	   		<div class="form-group">
	        <label>Please Select Vehicle</label> <br>
	        <?php if(!empty($customer->{'garage'}) ):?>
	        	<?php foreach($customer->{'garage'} as $car) : ?>
	        	
	        	<?php endforeach; ?>
	        <?php else : ?>
	        No Vehicles in Garage
	        <?php endif;?>
	        </div>
			
			<?php ?>
        
        	<h3>Select New Car</h3>
        	<select class="form-control">
        	<?php echo \Dsc\Html\Select::options(range(date("Y")+1, 1950), ''); ?>
        	</select>
        
        	<a class="NEXT" data-url="./admin/shop/order/flow/getCustomerForOrder">NEXT</a>
        
   		</div>
    </div>