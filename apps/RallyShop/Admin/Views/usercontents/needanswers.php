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
				Questions that need Answers
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

	</div>
</div>



  
         
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
      		 
 
 
 <?php $xEditable = new \Dsc\Html\xEditable($this->item, '/admin/shop/usercontent/edit/inline'); ?>
    	  <?php $publishStates =  array(array('value' => 'review', 'text' => 'review'), array('value' => 'unpublished', 'text' => 'unpublished'), array('value' => 'published', 'text' => 'published')) ;
    	   ; ?>
<div class="list-group-item <?php echo $this->item->type; ?> ">        
                <div class="row">
                  
                                                
                    <div class="col-xs-8 col-sm-10 col-md-10">
                        <div class="row">


                            <div class="col-xs-12 col-sm-8 col-md-8">
                                <div>
                                    <h3>Q: <small><?php echo $this->item->product()->title; ?> | <a href="shop/product/<?php echo $this->item->product()->{'slug'}; ?>" target="_blank"><?php echo $this->item->product()->{'tracking.model_number'}; ?></a></small>
                                    </h3>
                                </div>

                                <div><?php echo $this->item->title; ?></div><br>
                                
                                <div class="usercontent-detail">
                                    <blockquote>
	                                <span style="font-size: 14px; !important"><?php echo $this->item->copy; ?></span>
	                                <footer> By <a href="profiles/<?php echo (string) $this->item->user_id ?>"><cite title="<?php echo $this->item->{'user_name'}; ?>"><?php echo $this->item->{'user_name'}; ?> (<?php echo $this->item->user()->email; ?>)</cite></a></footer>
	                                </blockquote>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <div><small class="help-block">Published <?php echo date('M j, Y', $this->item->{'metadata.created.time'}); ?></small></div>
                                </p>
                                 <p>
                                 
                            <a class="btn btn-xs btn-default" target="_blank" href="/shop/product/<?php echo $this->item->product()->slug; ?>/create/answer/<?php echo $this->item->id; ?>">
                                <i class="fa  fa-wechat"></i>
                                <small>Answer Question</small>
                            </a>
                        </p>
                                <?php if (!empty($this->item->videoid)): ?>
                                    <a target="_blank" href="https://youtube.com/watch?v=<?php echo $this->item->videoid ?>">Video</a>
                                <?php endif; ?>

                                <?php foreach ($this->item->images as $image): ?>
                                    <img class="img-responsive" src="<?php echo $this->item->image_thumb($image); ?>" />
                                <?php endforeach; ?>
                            </div>
                        </div>

                    </div>
                    
                                    
                    <div class="col-xs-3 col-sm-2 col-md-2">
    		         
                        
                       
                          <p>
                           <?php if(empty($this->item->ticket_created)) :?>
                            <a class="btn btn-xs btn-info" data-bootbox="confirm" href="./admin/shop/usercontent/ticket/<?php echo $this->item->id; ?>">
                                <i class="fa fa-email"></i>
                                <small>Create Ticket</small>
                            </a>
                            <?php else :?> 
                             <a class="btn btn-xs btn-info disabled" disabled  >
                                <i class="fa fa-email"></i>
                                <small>Ticket Created</small>
                            </a>
                            <?php endif; ?>
                       	 </p>
                       	 
                       	 
                    </div>
                    
                </div>
            </div>
         
            
            
            
            
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
 