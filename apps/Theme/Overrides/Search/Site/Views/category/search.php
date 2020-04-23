<?php
$clear_all_exclusions = '';
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

if(!empty(\Dsc\ArrayHelper::get($item, 'hierarchical_categories'))) {
	$hierarchical_refinement = \Dsc\ArrayHelper::get($item, 'hierarchical_categories');
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
			'hierarchicalCategories.lvl0': [
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
			<?php if(\Base::instance()->get('SITE_TYPE') != 'wholesale') : ?>
  				try {
      				affirm.ui.refresh();
  				} catch(err) {
        			<?php if($DEBUG) : ?>
        					console.log(err);
      					<?php endif; ?>
    			}
    	<?php endif; ?>
		if($('.sticky_parent').length) {
			try {
  				$('.sticky_parent').hcSticky('reinit');
 			} catch(err) {
      		<?php if($DEBUG) : ?>
      			console.error(err);
      		<?php endif; ?>
  		}
		}
        stickyEval(filtersParent<?php echo $item->id; ?>, row<?php echo $item->id; ?>, 0);

		<?php if($type == 'shop.yearmakemodels') : ?>
		if(firstRender) {
			$('#search_universal_<?php echo $item->id; ?> .ais-toggle--checkbox').prop('checked', true);
			$('#search_universal_<?php echo $item->id; ?>').addClass('inactive');
			$('#search_universal_<?php echo $item->id; ?> .ais-toggle--item').addClass('ais-toggle--item__active');
			firstRender = false;
		} else {
      if('universal_item' in this.helper.state.facetsRefinements && $('#search_universal_<?php echo $item->id; ?>.active .ais-toggle--checkbox').prop('checked') == false) {
        search_<?php echo $item->id; ?>.helper.removeFacetRefinement('universal_item').search();
      }
    }
		<?php endif; ?>
	});


    search_<?php echo $item->id; ?>.helper.on('result', function(results, state) {

    });

	<?php if($type == 'shop.yearmakemodels') : ?>
	$(document).on('click', '#search_universal_<?php echo $item->id; ?>.inactive', function(e) {
		$(this).removeClass('inactive').addClass('active');
		e.stopImmediatePropagation();
		//search_<?php echo $item->id; ?>.helper.setQueryParameter('filters', 'ymm_hashs: <?php echo $activeVehicle['hash']; ?> OR universal_item: true').search();
    search_<?php echo $item->id; ?>.helper.removeFacetRefinement('universal_item', 'false').search();
		$('#search_universal_<?php echo $item->id; ?> .ais-toggle--item').removeClass('ais-toggle--item__active');
		return false;
	});
	<?php endif; ?>
	
	
	
    
</script>
