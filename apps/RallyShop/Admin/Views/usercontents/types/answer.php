 <?php $xEditable = new \Dsc\Html\xEditable($this->item, '/admin/shop/usercontent/edit/inline'); ?>
    	  <?php $publishStates =  array(array('value' => 'review', 'text' => 'review'), array('value' => 'unpublished', 'text' => 'unpublished'), array('value' => 'published', 'text' => 'published')) ;
    	   ; ?>
<div class="list-group-item <?php echo $this->item->type; ?>">        
                <div class="row">
                    <div class="checkbox-column col-xs-1 col-sm-1 col-md-1">
                        <input type="checkbox" class="icheck-input icheck-id" name="ids[]" value="<?php echo $this->item->id; ?>">
                    </div>
                                                
                    <div class="col-xs-8 col-sm-9 col-md-9">
                        <div class="row">
                            <div class="hidden-xs col-sm-2 col-md-2">
                                <?php if (!empty($this->item->images[0])) { ?>
                                    <img class="img-responsive" src="./asset/thumb/<?php echo $this->item->images[0]; ?>" />
                                <?php } ?>
                            </div>
                                
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div>
                                    <a href="./admin/shop/usercontent/edit/<?php echo $this->item->id; ?>">
                                    <?php echo $this->item->{'title'}; ?>
                                    </a>
                                </div>
                                
                                <small>
                                    <input class="rating" data-size="xs" data-disabled="true" data-readonly="true" data-show-clear="false" data-show-caption="false" value="<?php echo (int) $this->item->rating; ?>" >
                                </small>
                                
                                <?php if ($this->item->{'user_id'}) { ?>
                                <div>
                                    <label><?php echo $this->item->{'user_name'}; ?>:</label> <?php echo $this->item->user()->email(true); ?>
                                </div>
                                <?php } ?>
                                
                                <?php if (!empty($this->item->product()->id)) { ?>
                                <div>
                                    <a href="./shop/product/<?php echo $this->item->product()->slug; ?>#reviews" target="_blank">
                                    <?php echo $this->item->product()->title; ?>: <?php echo $this->item->product()->{'tracking.sku'}; ?>
                                    </a>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <?php echo $xEditable->publishable( $this->item->{'publication.status'},'publication.status', $publishStates); ?>
                                    <div><small class="help-block"><?php echo date('M j, Y', $this->item->{'metadata.created.time'}); ?></small></div>
                                </p>
                            </div>
                        </div>

                    </div>
                    
                                    
                    <div class="col-xs-3 col-sm-2 col-md-2">
    		            <p>
                            <a class="btn btn-xs btn-success" href="./admin/shop/usercontent/edit/<?php echo $this->item->id; ?>">
                                <i class="fa fa-pencil"></i>
                                <small>Edit</small>
                            </a>
                        </p>

                        <p>
                            <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/usercontent/delete/<?php echo $this->item->id; ?>">
                                <i class="fa fa-times"></i>
                                <small>Delete</small>
                            </a>
                        </p>
                         <p>
                             <a class="btn btn-xs btn-info" data-bootbox="confirm" href="./admin/shop/usercontent/ticket/<?php echo $this->item->id; ?>">
                                 <i class="fa fa-email"></i>
                                 <small>
                                     <?php if(empty($this->item->ticket_created)): ?>
                                         Create Ticket
                                     <?php else: ?>
                                         Resend Ticket
                                     <?php endif; ?>
                                 </small>
                             </a>
                       	 </p>
                    </div>
                    
                </div>
            </div>