<?php $aside = false;
// // TODO: is a module published in the pages-category-aside position? 
if (!empty($module_content)) {
	$aside = true;
}
?>



<div id="pages-category" class="pages-pages">
    <div class="container">
    
        <?php if ($category->{'featured_image.slug'}) { ?>
        
		        <div class="category-header-image">
		            <img class="img-responsive" src="./asset/<?php echo $category->{'featured_image.slug'}; ?>" title="<?php echo $category->{'title'}; ?>" alt="<?php echo $category->{'title'}; ?>">
		        </div>
        <?php } ?>
            
	        <div class="row">
		            <div class="col-sm-<?php echo !empty($aside) ? '9' : '12'; ?>">    
		            
		            <?php $this->paginated = $paginated; echo $this->renderView('Pages/Site/Views::pages/index.php'); ?>
		            
		            </div>
	            
		            <?php if (!empty($aside)) { ?>
		            <aside class="col-sm-3">
		            	<?php 
		                    echo $module_content;
		            	?>
		            </aside>
		            <?php } ?>
	        </div>   
        
         
    </div>
</div>