<?php
$clear_all_exclusions = '';
$hierarchical_refinement = end($item->getHierarchy());
if($type = $item->type()) {
    if ($type == 'shop.manufacturers') {
        $facet = 'Brand';
        $refinement = $item->title;
        $clear_all_exclusions = 'Brand';
    } elseif ($type == 'shop.collections') {
        $facet = 'collections.' . \Base::instance()->get('sales_channel') . '.slug';
        $refinement = $item->slug;
        $clear_all_exclusions = 'collections.slug';
    } elseif ($type == 'shop.yearmakemodels') {
        $facet = 'universal_item';
        $refinement = 'false';
        $clear_all_exclusions = 'Brand';
    } elseif ($type == 'shop.categories') {
    	$clear_all_exclusions = 'Category';
    }
}
?>
<script>
	var firstRender = true;
	var filtersParent<?php echo $item->id; ?> = $('.filters_parent_<?php echo $item->id; ?>');
	var row<?php echo $item->id; ?> = $('.search_row_<?php echo $item->id; ?>');

	var search_<?php echo $item->id; ?> = algoliaProductInstance(
        [
            '<?php echo $facet; ?>',
        ], //set facets (array)
        <?php if(!empty($facet)) : ?>
		{
			'<?php echo $facet; ?>': [
				'<?php echo $refinement; ?>'
			]
		}, //facet refinements (array -> object)
        <?php else : ?>
        null,
        <?php endif; ?>
        <?php if(!empty($hierarchical_refinement)) : ?>
		{
			'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') : ''; ?>.lvl0': [
				'<?php echo addslashes($hierarchical_refinement); ?>'
			]
		}, //hierarchical facet refinements (string full path)
        <?php else : ?>
        null,
        <?php endif; ?>
        21, //number of hits (int)
        '#hits-container_<?php echo $item->id; ?>', //hits container (string)
        '<?php echo $this->renderLayout('Search/Site/Views::search/algolia_empty_template.php'); ?>', //empty template (string)
        true, //url sync (boolean)
        '_<?php echo $item->id; ?>', //instance id (string)
		true //use widgets (other than hits widget)
    );

    search_<?php echo $item->id; ?>.start();
	instances.push('search_<?php echo $item->id; ?>');

	search_<?php echo $item->id; ?>.on('render', function() {
       
      if('universal_item' in this.helper.state.facetsRefinements && $('#search_universal_<?php echo $item->id; ?>.active .ais-toggle--checkbox').prop('checked') == false) {
        search_<?php echo $item->id; ?>.helper.removeFacetRefinement('universal_item').search();
	  }
	  
	});
	
	
    
</script>
