                <div class="row">
    <div class="col-md-2">
        
        <h3>Brand Logo</h3>
        <p class="help-block">This is the Brands Marketing logo</p>
                
    </div>
    <!-- /.col-md-2 -->
                
    <div class="col-md-10">
    
        <div class="form-group">
            <label>Primary Image</label>
            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('brand_logo', $flash->old('brand_logo.slug'), array('field'=>'brand_logo[slug]') ); ?>
        </div>
        <!-- /.form-group -->
        
    </div>
    <!-- /.col-md-10 -->
    
</div>
<!-- /.row -->

	<hr/>
		<div class="row">
	    <div class="col-md-2">
	        
	        <h3>Featured Image</h3>
	        <p class="help-block">This is the big header image that spans across the Brands page</p>
	                
	    </div>
	    <!-- /.col-md-2 -->
	                
	    <div class="col-md-10">
	    
	        <div class="form-group">
	            <label>Primary Image</label>
	            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('featured_image', $flash->old('featured_image.slug'), array('field'=>'featured_image[slug]') ); ?>
	        </div>
	        <!-- /.form-group -->
	        
	    </div>
	    <!-- /.col-md-10 -->
	    
	</div>
	<!-- /.row -->
	<hr />
   	<?php echo $this->renderLayout("Shop/Admin/Views::manufacturers/fields_publication.php"); ?>
	<hr />
	<div class="row">
	    <div class="col-md-2">
	        
	        <h3>Description</h3>
	        <p class="help-block">Text block, that goes below the image tells about the brand. </p>
	                
	    </div>
	    <!-- /.col-md-2 -->
	                
	    <div class="col-md-10">
	    
	        <div class="form-group">
	            <label>Description</label>
	            <textarea name="description" class="form-control wysiwyg"><?php echo $flash->old('description'); ?></textarea>
	        </div>
	        <!-- /.form-group -->
	        
	    </div>
	    <!-- /.col-md-10 -->
	    
	</div>
	<!-- /.row -->
<hr />

	<div class="row">
	    <div class="col-md-2">
	        
	        <h3>Settings</h3>
	        <p class="help-block">Options </p>
	                
	    </div>
	    <!-- /.col-md-2 -->
	                
	    <div class="col-md-10">
	    
	        <div class="form-group">
	            <label>Video ID</label>
	            <input class="form-control" name="videoid" type="text" value="<?php echo $flash->old('video'); ?>">
	        </div>
	        <!-- /.form-group -->
	        <div class="form-group">
	            <label>Show Text Overlay</label>
	            <select name="showtext" class="form-control">
	            <option value='1' <?php if($flash->old('showtext') == 1) { echo 'selected="selected"'; } ?>>Yes</option>
	            <option value='0' <?php if($flash->old('showtext') == 0) { echo 'selected="selected"'; } ?>>Hide Text</option></select>

	        </div>
	    </div>
	    <!-- /.col-md-10 -->
	    
	</div>
	<!-- /.row -->
<hr/>
