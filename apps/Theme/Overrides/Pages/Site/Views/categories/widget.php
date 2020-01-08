<?php if ($cats = \Blog\Models\Categories::find()) { ?>
<div class="widget widget-categories">
	<h4 class="widget-title">Categories</h4>
	<div class="widget-content">
	
    <?php foreach ($cats as $cat) {
        // TODO Set the classes based on anything in the $cat object and also on the active URL and also on $this->selected 
        $classes = null; 
        ?>
		<div>
    		<a href="./blog/category<?php echo $cat->{"path"}; ?>" class="<?php echo $classes; ?>"><?php echo $cat->{'title'}; ?></a>
		</div>
    <?php } ?>	
	
	</div>
</div>
<?php } ?>