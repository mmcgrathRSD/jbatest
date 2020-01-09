<style>
font-size: 17.5px; */
.usercontent-detail blockquote {
font-size: 12px;
}
.usercontent-detail blockquote a.xeditable {
text-decoration: none  !important;
border-bottom: none !important;
font-size: 12px;
color:#000;
}

</style>

<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				User Content 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

	</div>
</div>

<form class="searchForm" method="post" action="./admin/shop/usercontents">

    <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
    <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
        
    <div class="row">
        <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
            <ul class="list-filters list-unstyled list-inline">
                <li>
                    <a class="btn btn-link" href="javascript:void(0);" onclick="ShopToggleAdvancedFilters();">Advanced Filters</a>
                </li>
                <li>Published State:
                    <select name="filter[publication_status]" class="form-control" onchange="this.form.submit();">
                    
                         <option value="review" <?php if ($state->get('filter.publication_status') == 'review') { echo "selected='selected'"; } ?>>Review</option>
                       
                        <option value="published" <?php if ($state->get('filter.publication_status') == 'published') { echo "selected='selected'"; } ?>>Published</option>
                        <option value="unpublished" <?php if ($state->get('filter.publication_status') == 'unpublished') { echo "selected='selected'"; } ?>>Unpublished</option>
                    </select>
                </li>
                
                 <li>
                 Type:
                    <select name="filter[type]" class="form-control" onchange="this.form.submit();">
                    <option value="" >Select</option>
                         <option value="question" <?php if ($state->get('filter.type') == 'question') { echo "selected='selected'"; } ?>>Question</option>
                       
                        <option value="review" <?php if ($state->get('filter.type') == 'review') { echo "selected='selected'"; } ?>>Review</option>
            
                    </select>
                </li>
                <li>
                    <div style="min-width: 150px;">
                        <input id="filter_products" name="filter[product_ids]" value="<?php echo implode(",", (array) $state->get('filter.product_ids') ); ?>" type="text" class="form-control" onchange="this.form.submit();" />
                    </div>
                    <script>
                    jQuery(document).ready(function() {
                        
                        jQuery("#filter_products").select2({
                            allowClear: true, 
                            placeholder: "Product...",
                            multiple: true,
                            minimumInputLength: 3,
                            ajax: {
                                url: "./admin/shop/products/forSelection",
                                dataType: 'json',
                                data: function (term, page) {
                                    return {
                                        q: term
                                    };
                                },
                                results: function (data, page) {
                                    return {results: data.results};
                                }
                            }
                            <?php if ($state->get('filter.product_ids')) { ?>
                            , initSelection : function (element, callback) {
                                var data = <?php echo json_encode( \Shop\Models\Products::forSelection( array('_id'=>array('$in'=>array_map( function($input){ return new \MongoDB\BSON\ObjectID($input); }, explode(",", $state->get('filter.product_ids') ) ) ) ) ) ); ?>;
                                callback(data);            
                            }
                            <?php } ?>
                        });
                    });
                    </script>                    
                </li>
            </ul>        
        </div>
        <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                    <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                        <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset Filters</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div id="advanced-filters" class="panel panel-default" 
    <?php 
    if (!$state->get('filter.last_modified_after')
        && !$state->get('filter.last_modified_before')            
    ) { ?>
        style="display: none;"
    <?php } ?>
    >
        <div class="panel-body">
            <div class="row">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-2">
                            <h4>Last Modified</h4>
                        </div>
                        <div class="col-md-10">
                            <div class="form-group">
                                <div class="input-daterange input-group" id="datepicker">
                                    <input type="text" name="filter[last_modified_after]" value="<?php echo $state->get('filter.last_modified_after'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                    <span class="input-group-addon">to</span>
                                    <input type="text" name="filter[last_modified_before]" value="<?php echo $state->get('filter.last_modified_before'); ?>" class="input-sm ui-datepicker form-control" data-date-format="yyyy-mm-dd" data-date-today-highlight="true" data-date-today-btn="true" />
                                </div>
                            </div>
                        </div>                
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary pull-right">Go</button>
                </div>
            </div>   
        </div> 
    </div>
    
    <script>
    ShopToggleAdvancedFilters = function(el) {
        var filters = jQuery('#advanced-filters');
        if (filters.is(':hidden')) {
            filters.slideDown();        
        } else {
        	filters.slideUp();
        }
    }
    </script>        
         
    <?php if (!empty($paginated->items)) { ?>
    <div class="panel panel-default">
        <div class="panel-heading">

            <div class="row">
               
                  
                <div class="col-xs-8 col-sm-5 col-md-5 col-lg-6">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>            
                </div>
                
                <?php if (!empty($paginated->items)) { ?>
                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-3 text-align-right">
                    <span class="pagination">
                        <span class="hidden-xs hidden-sm">
                            <?php echo $paginated->getResultsCounter(); ?>
                        </span>
                    </span>
                    <span class="pagination">
                        <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                    </span>                                        
                </div>
                <?php } ?>        
                
            </div>            
            
        </div>
        <div class="panel-body">
            <div class="list-group-item">
                <div class="row">
                    
                    <div class="col-xs-12 col-md-12">
                        Sort by:
                        <a class="btn btn-link" data-sortable="title">Title</a>
                        <a class="btn btn-link" data-sortable="rating">Rating</a>
                        <a class="btn btn-link" data-sortable="metadata.created.time">Creation Date</a>            
                    </div>
                </div>
            </div>        

            <?php foreach($paginated->items as $item) { ?>
           
      	
      		<?php $this->item = $item; 
      		
      	
      		?>
      			<?php echo $this->renderLayout("JBAShop/Admin/Views::usercontents/types/{$item->type}.php"); ?>
            
            
            
            <?php } ?>
            
            <?php } else { ?>
                <div class="">No items found.</div>
            <?php } ?>
        
            <div class="dt-row dt-bottom-row">
                <div class="row">
                    <div class="col-sm-10">
                        <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                            <?php echo $paginated->serve(); ?>
                        <?php } ?>
                    </div>
                    <div class="col-sm-2">
                        <div class="datatable-results-count pull-right">
                            <span class="pagination">
                                <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        
        </div>
    
</form>