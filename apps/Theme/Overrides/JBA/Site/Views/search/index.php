<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.css">
<script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch.min.js"></script>

<script src="https://cdn.jsdelivr.net/instantsearch.js/1/instantsearch-preact.min.js"></script>
<style>
	#search-box {
		width: 100%;
		height: 40px;
		padding: 15px;
		font-size: 16px;
		border: 2px solid #ccc!important;
	}
	
	#hits-container {
		margin-top: 20px;
	}
	
	#categoryFilterList {
		padding-left: 0!important;
	}
	
	.sbx-sffv {
		margin-top: 15px;
	}
	
	.filters_outer_wrapper {
		padding: 15px;
		    border: solid 2px #000;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    border-radius: 2px;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
	}
	
	.ais-header {
		font-size: 16px;
		font-weight: bold; 
		padding-bottom: 5px;
    border-bottom: 1px solid #DDDDDD;
	}
	
	.ais-refinement-list--label, ais-hierarchical-menu--link {
		width: 100%;
		cursor: pointer;
	}
	
	label.ais-refinement-list--label:hover, ais-hierarchical-menu--link:hover {
			color: #009CFF;
	}

	.ais-refinement-list--label input {
		display: none;
	}
	
	.ais-refinement-list--label {
		height: 10px;
    display: block;
    padding-top: 15px;
    padding-bottom: 10px;
	}
	
	.ais-refinement-list--label:after {
		margin-top: -7px;
    font-size: 1.8rem;
    color: #afb8bd!important;
    font-family: 'Glyphicons Regular';
    content: "\e154"!important;
    float: left!important;
    padding-left: 0px!important;
    padding-right: 2px!important;
	}
	
	.ais-refinement-list--item__active .ais-refinement-list--label, .ais-hierarchical-menu--item__active .ais-hierarchical-menu--link {
		color: #0155ab;
		font-weight: bold;
	}
	
	.ais-refinement-list--item__active .ais-refinement-list--label:after {
		    content: "\e153"!important;
		color: #0155ab!important;
		font-weight: bold;
	}
	
	.ais-refinement-list--count, .ais-hierarchical-menu--count {
			float: right;
	}
	
	.ais-pagination--item {
		padding: 0;
	}
	
	.ais-pagination--link {
		 position: relative;
    float: left;
    padding: 6px 12px;
    line-height: 1.42857143;
    text-decoration: none;
    color: #0155ab;
    background-color: #ffffff;
    border: 1px solid #dddddd;
    margin-left: -1px;

	}
	
	.ais-pagination--item__disabled {
		display: none;
	}
	
	.ais-pagination {
		margin-top: 20px;
		padding-left: 0;
		margin-left: -15px;
	}
	
	.ais-pagination--item__active .ais-pagination--link {
		color: #fff;
    background-color: #0155ab;
		text-decoration: none!important;
	}
	
	.ais-pagination--link:hover {
		color: #fff!important;
		background-color: #009CFF;
	}
	#stats * {
	display: inline-block;
	}
	.ais-stats--time { display:none !important; }
	.ais-search-box:focus, .ais-search-box:active {
		    outline: initial;
	}
	
</style>
<?php $base = \Dsc\Url::full(false); ?>
<div class="row">
	<div class="col-xs-12 paddingTop paddingBottom">
		<input id="search-box" value="<?php echo @$terms; ?>" />
	</div>

</div>
<div>
		<div class="col-lg-3 col-md-3 hidden-sm hidden-xs" id="categoryFilterList" style="margin-top: 15px;">
		<div class="catsPanelHead">
			Product Filters
		</div>
		<div class="filters_outer_wrapper">
			<div id="categories"></div> 
			<div id="brands"></div> 
			<div id="doors"></div> 
		</div>
	     

        

    </div>
	<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 paddingTop">

	   
	   <div class="paddingBottom">
	      
		 
		    	<?php echo $this->renderView('RallySport/Site/Views::search/grid.php'); ?>
		 
	  </div> 
	</div>
	


	</div>
</div>


<script>
    var search = instantsearch({
	  appId: '44PNGO64HW',
	  apiKey: '8f2ef61d4fdeb5549c7cd429677f88c5',
	  indexName: 'rallysport-products',
	  urlSync: true<?php if (!empty($activeVechile)) : ?>,
			searchFunction: function(helper) {
				search.helper.setQueryParameter('filters', 'universal_item:true OR ymm_hashs: <?php echo $activeVechile['hash']; ?>');
				search.helper.setPage(helper.state.page);
				helper.search();
			},
			<?php endif; ?>
			
	});
   

	var item_template = '<?php echo $this->renderLayout('RallySport\Site\Views::search/algolia_list_item.php');?>';

	
	  search.addWidget(
	    instantsearch.widgets.searchBox({
	      container: '#search-box',
	      placeholder: 'Search for...',
	    })
	  );
	  
	
	
      search.addWidget(
        instantsearch.widgets.hits({
          container: '#hits-container',
          templates: {
            item: item_template
                
          },
					hitsPerPage: 21,
					cssClasses: {
							root: 'row',
							item: 'item'
					}
        })
      );

      search.addWidget(
        instantsearch.widgets.pagination({
          container: '#pagination-container'
        })
      );

      search.addWidget(
  		    instantsearch.widgets.stats({
  		      container: '#stats',
  		    })
  		  );
      /* search.addWidget(
    		  instantsearch.widgets.menu({
    		    container: '#categories',
    		    attributeName: 'Category',
    		    limit: 10,
    		    templates: {
    		      header: 'Categories'
    		    }
    		  })
    		); */
      search.addWidget(
    		  instantsearch.widgets.refinementList({
    		    container: '#brands',
    		    attributeName: 'Brand',
    		    limit: 20,
						searchForFacetValues: true,
    		    templates: {
    		      header: 'Brand'
    		    }
    		  })
    		);
      search.addWidget(
    		  instantsearch.widgets.hierarchicalMenu({
    		    container: '#categories',
    		    attributes: ['hierarchicalCategories<?php echo filter_var(\Base::instance()->get('algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') : ''; ?>.lvl0', 'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') : ''; ?>.lvl1', 'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') : ''; ?>.lvl2'],
    		    templates: {
    		      header: 'Categories'
    		    }
    		  })
    		);
		
      /* search.addWidget(
    		  instantsearch.widgets.menu({
    		    container: '#doors',
    		    attributeName: 'Doors',
    		    limit: 10,
    		    templates: {
    		      header: 'Doors'
    		    }
    		  })
    		); */
      search.start();
	
		var onRenderHandler = function() {
				$('.item:nth-of-type(3n+3)').after('<div class="clearfix"></div>');
		};
		search.on('render', onRenderHandler);
</script>
