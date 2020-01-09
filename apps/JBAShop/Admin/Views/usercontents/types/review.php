 <?php $xEditable = new \Dsc\Html\xEditable($this->item, '/admin/shop/usercontent/edit/inline'); ?>
    	  <?php $publishStates =  array(array('value' => 'review', 'text' => 'review'), array('value' => 'unpublished', 'text' => 'unpublished'), array('value' => 'published', 'text' => 'published')) ;
    	   ; ?>
<div class="list-group-item <?php echo $this->item->type; ?>">
    <?php if (!empty($this->item->product()->id)) : ?>
        <div>
            <h3>R: <small><?php echo $this->item->product()->title; ?> | <a href="shop/product/<?php echo $this->item->product()->{'slug'}; ?>" target="_blank"><?php echo $this->item->product()->{'tracking.model_number'}; ?></a></small>
                <?php if ($this->item->order_verified) : ?>
                    Verified Purchase
                <?php endif; ?>
            </h3>
        </div>
    <?php endif;?>
                <div class="row">
                   

                    <div class="col-xs-8 col-sm-10 col-md-10">
                        <div class="row">
             
                            <div class="col-xs-12 col-sm-8 col-md-8">
                            
                            
                            <div><?php echo $xEditable->field('title'); ?></div><br>
                                <div class="usercontent-detail">
	                                <blockquote>
	                                <?php echo $xEditable->field('copy', 'textarea'); ?>
	                                <footer><?php echo $xEditable->field('rating'); ?> /5   By <a href="profiles/<?php echo (string) $this->item->user_id ?>"><cite title="<?php echo $this->item->{'user_name'}; ?>"><?php echo $this->item->{'user_name'}; ?> (<?php echo $this->item->user()->email(true); ?>)</cite></a></footer>
	                                </blockquote>
                                </div>

                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <?php echo $xEditable->publishable( $this->item->{'publication.status'},'publication.status', $publishStates); ?>
                                    <div><small class="help-block"><?php echo date('M j, Y', $this->item->{'metadata.created.time'}); ?></small></div>
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