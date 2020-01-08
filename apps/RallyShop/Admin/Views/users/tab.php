<div class="form-group">
	<label>Price Level</label>
	<select name="shop[pricelevel]" class="form-control">
	<?php $array = array(); 
			$array[] = array('text' => 'RETAIL', 'value' => 'retail' );
			$array[] = array('text' => 'WHOLESALE D1', 'value' => 'wholesale_d1' );
			$array[] = array('text' => 'WHOLESALE D2', 'value' => 'wholesale_d2' );
			$array[] = array('text' => 'WHOLESALE D3', 'value' => 'wholesale_d3' );
			
	
	echo \Dsc\Html\Select::options($array ,  $item->{'shop.pricelevel'});
	
	
	?>
	
	</select>
	
</div>