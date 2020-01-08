<h3 class="">Shipping Multipliers</h3>
<hr />

<div class="form-group">
    <div class="row">
        <label class="control-label col-md-3">Base Shipping Multiplier</label>
    
        <div class="col-md-7">
                <input type="text" name="shipping_multiplier[base]" class="form-control" value="<?php echo $flash->old('shipping_multiplier.base');?>" placeholder="10% increase is 1.1 and etc." />
            </label>
        </div>    
    </div>
</div>

<?php 
	$others = $flash->old('shipping_multiplier.others');
	if( !empty($others)){
?>
<hr />

<?php 
	foreach( $others as $id => $vals ) {
        if (!is_array($vals)) continue;
?>
<div class="form-group">
    <div class="row">
        <label class="control-label col-md-3"><?php echo $vals['title']?></label>
    
        <div class="col-md-7">
        	<input type="text" value="<?php echo $vals['multiplier']?>" name="shipping_multiplier[others][<?php echo $id; ?>][multiplier]" class="form-control" />
        	<input type="hidden" value="<?php echo $vals['title']?>" name="shipping_multiplier[others][<?php echo $id; ?>][title]" />
        </div>    
    </div>
</div>
<?php	} 
	}
?>
