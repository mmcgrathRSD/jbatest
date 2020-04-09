a<?php if (!empty($this->app->get('algolia.read_api_key')) && !empty($this->app->get('algolia.app_id')) && $checkoutmode == 0) : ?>
<div id="search_loader" class="search_loader"><i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw margin-bottom"></i></div>
<?php
$this->app->set('master_search', true);
echo $this->renderLayout('Search/Site/Views::search/functions/widgets.php');
$this->app->set('master_search', false);
?>
<script>
    var filtersParent = $('.filters_parent<?php echo $instance_id; ?>');
	var row = $('.search_row<?php echo $instance_id; ?>');

    var search = algoliaProductInstance(
        [
            'universal_item',
            'ymm_hashs',
            'collections.<?php echo \Base::instance()->get('sales_channel'); ?>.slug',
            'product_type'
        ], //set facets (array)
        null, //facet refinements (array -> object)
		null, //hierarchical facet refinements (string full path)
        21, //number of hits (int)
        '#hits-container', //hits container (string)
        '<?php echo $this->renderLayout('Search/Site/Views::search/algolia_empty_template.php');?>', //empty template (string)
        true, //url sync (boolean)
		null, //instance id (string)
		true //use widgets (other than hits)
    );

	search.on('render', function() {
	    <?php if(\Base::instance()->get('SITE_TYPE') != 'wholesale') : ?>
          try {
              affirm.ui.refresh();
          } catch(err) {
                <?php if($DEBUG) : ?>
                    console.error(err);
                <?php endif; ?>
            }
        <?php endif; ?>
	    try {
          $('.sticky_parent').hcSticky('reinit');
        } catch(err) {
          <?php if($DEBUG) : ?>
              console.error(err);
          <?php endif; ?>
        }
        stickyEval(filtersParent, row, 0);
		$('#search_loader').hide();






	});
</script>
<?php endif; ?>
