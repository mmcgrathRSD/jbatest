  <form action="/search">
  <input class="form-control" name="q" value="<?php echo $terms; ?>"><button class="btn btn-primary">Search</button>
  </form>
  
  
  <div id="productsHolder">
<?php if (!empty($paginated->items)) : ?>
   
   
   
    <?php foreach ($paginated->items as $position=>$item) : ?>
        
        <div class="position-<?php echo $position; ?>   productItem paddingBottom noCompare col-xs-12 col-lg-4 col-md-4 col-sm-12">
        <?php $this->item = $item; ?> 
        <?php echo $this->renderLayout('RallySport/Site/Views::search/list_item.php'); ?> 
        </div>
       
    <?php endforeach; ?>
   
<?php if (!empty($paginated->total_pages)) :?>
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
<?php endif; ?>
<?php  else : ?>
    
    <?php echo  $this->renderLayout('Shop/Site/Views::category/noproducts.php'); ?>
    
<?php endif; ?>
  </div> 