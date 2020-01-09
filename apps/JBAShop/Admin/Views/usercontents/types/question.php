 
 
 
 <?php $xEditable = new \Dsc\Html\xEditable($this->item, '/admin/shop/usercontent/edit/inline'); ?>
    	  <?php $publishStates =  array(array('value' => 'review', 'text' => 'review'), array('value' => 'unpublished', 'text' => 'unpublished'), array('value' => 'published', 'text' => 'published')) ;
    	   ; ?>
<div class="list-group-item <?php echo $this->item->type; ?> ">
    <?php if (!empty($this->item->product()->id)) : ?>
        <div> <h3>Q: <small><?php echo $this->item->product()->title; ?> | <a href="shop/product/<?php echo $this->item->product()->{'slug'}; ?>" target="_blank"><?php echo $this->item->product()->{'tracking.model_number'}; ?></a></small></h3></div>
    <?php endif;?>

                <div class="row">
                  
                                                
                    <div class="col-xs-8 col-sm-10 col-md-10">
                        <div class="row">

                                
                            <div class="col-xs-12 col-sm-8 col-md-8">
                             

                                <div><?php echo $xEditable->field('title'); ?></div><br>
                                
                                <div class="usercontent-detail">
	                                <blockquote>
	                                <?php echo $xEditable->field('copy', 'textarea'); ?>
	                                <footer> By <a href="profiles/<?php echo (string) $this->item->user_id ?>"><cite title="<?php echo $this->item->{'user_name'}; ?>"><?php echo $this->item->{'user_name'}; ?> (<?php echo $this->item->user()->email(true); ?>)</cite></a></footer>
	                                </blockquote>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <p>
                                    <?php echo $xEditable->publishable( $this->item->{'publication.status'},'publication.status', $publishStates); ?>
                                    <div><small class="help-block"><?php echo date('M j, Y', $this->item->{'metadata.created.time'}); ?></small></div>
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
            
            <div class="list-group">
            <?php if(!empty($this->item->answers)) : ?>
            <?php foreach ($this->item->answers as $key => $answer ) :?>
            <?php if(!empty($answer['answer_id'])) : ?>
            <div class="list-group-item answer ">        
                <div class="row">
                    <div class="col-xs-8 col-sm-10 col-md-10">
                        <div class="row">
                            <div class="checkbox-column col-xs-1 col-sm-1 col-md-1">
                                <h1>A:</h1>
                            </div>

                            <div class="col-xs-12 col-sm-7 col-md-7 usercontent-detail">
                                <blockquote><?php echo $xEditable->field('answers.'.$key.'.message','textarea'); ?>
                                    <footer>By <a href="profiles/<?php echo (string) $this->item->{'answers.'.$key.'.user_id'} ?>"><cite title="<?php echo $this->item->{'answers.'.$key.'.username'}; ?>"><?php echo $this->item->{'answers.'.$key.'.username'}; ?></cite><a/></footer>
                                </blockquote>
                            </div>
                            <div class="col-xs-12 col-sm-4 col-md-4">
                                <!-- // TODO: published state not supported for answers yet -->
                                <!-- Published State: --><?php //echo $xEditable->publishable( $this->item->{'answers.'.$key.'.publication.status'},'answers.'.$key.'.publication.status', $publishStates); ?>

                                <?php if (!empty($answer['videoid'])): ?>
                                    <a target="_blank" href="https://youtube.com/watch?v=<?php echo $answer['videoid'] ?>">Video</a>
                                <?php endif; ?>

                                <?php if (!empty($answer['images'])): foreach ($answer['images'] as $image): ?>
                                    <img class="img-responsive" src="<?php echo $this->item->image_thumb($image); ?>" />
                                <?php endforeach; endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-3 col-sm-2 col-md-2">
                        <p>
                            <a class="btn btn-xs btn-danger" data-bootbox="confirm" href="./admin/shop/usercontent/question/<?php echo (string) $this->item->id ?>/deleteanswer/<?php echo (string) $answer['answer_id'] ?>">
                                <i class="fa fa-times"></i>
                                <small>Delete</small>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php endforeach;?>
            <?php else :?>
            
            There are no answers yet
            <?php endif;?>
            </div>
            