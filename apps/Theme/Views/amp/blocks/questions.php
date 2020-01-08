<amp-accordion>
    <section>
<h2 class="aamp_accordion_box">
    Questions & Answers
</h2>
		<div>
<?php $questions = (new \Shop\Models\UserContent())->setCondition('product_id', $item->id)->setCondition('type', 'question')->setCondition('publication.status', 'published')->setState('list.limit', 3)->getList(); ?>
	
							<div>
								<a href="<?php echo $item->generateStandardURL(); ?>/create/question" class="aamp_write_rev-qa"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Ask a question</a>
								<?php if(empty($questions)) : ?>
								<div>
									No questions have been asked about this product. 
								</div>
								<?php else : ?>
								<?php foreach ($questions as $question) : ?>
                                <div class="aamp_single_review">
                                    <strong>Q: </strong><?php echo $question->title; ?>&nbsp;&nbsp;<small><strong><a href="/profiles/<?php echo @$question['user_id']; ?>"><?php echo @$question['user_name']; ?></a></strong> <small class="text-muted"> <?php echo $question->timeSince(); ?></small> </small></h4><a href="<?php echo $item->generateStandardURL(); ?>/create/answer/<?php echo $question->id?>" class="btn btn-xs btn-primary pull-right">Answer</a>
                                <br />
                                    <?php echo $question->copy; ?>
                                
														<?php if(!empty($question->images)) :?>																											
														<?php foreach($question->images as $image) : if (!empty($image)) {?>
															
															<amp-img src="<?php echo $question->showImageThumb($image); ?>" layout="fixed-height" height="85">
														<?php } endforeach;  endif;?>
														
														<?php if ( !empty($question->videoid) ) : ?>
														<?php
														endif;
														?>	
                                                                </div>
                                
                                    
                                <div>
                                    
                                
										<?php if ( !empty( $question->answers ) ) :
											foreach($question->answers as $answer) : 
										?>
                                <div class="aamp_single_answer">
                                    <strong>A: </strong>
													<?php echo $answer['message']; ?>
                                    <br />&nbsp;<br />
													<?php if(!empty($answer['images'])) : ?>
													<?php foreach($answer['images'] as $image) : if (!empty($image)) {?>
													<?php } endforeach; endif; ?>
													
													
													<?php if ( !empty($answer['videoid']) ) : ?>
														<?php
														endif;
													?>	

													<small class="text-muted"><strong><a href="/profiles/<?php echo @$answer['user_id']; ?>"><?php echo @$answer['username']; ?></a></strong></small> 
													
													<?php

											
													 switch (@strtolower($answer['role']))  { 
														
														case 'staff': 
															echo '<amp-img src="/theme/img/icon_social_staff.gif" width="47" height="17" /> ';
															break;
														case 'industry': 
															echo '<amp-img src="/theme/img/icon_social_rep.gif" width="47" height="17" /> ';
															break;
													
													}?>
														
													
												<small class="text-muted"><small><?php echo $question->timeSince($answer['created']); ?></small></small>
												
												<?php if ( empty($this->auth->getIdentity()->id) ) : ?>
															<?php //TODO: Removing buttons for voting when not logged in. SHould be images at some point. -Austin ?>
														<?php else : ?>
															<div class="paddingTopMd"><button class="btn btn-default reviewBtn" data-reviewRoute="/usercontent/<?php echo $question->id; ?>/<?php echo @$answer['answer_id']; ?>/up"> <i class="fa fa-thumbs-up"></i>&nbsp;<span class="upVoteCount"><?php echo (@$answer['votes']['up'] ?  $answer['votes']['up'] : 0); ?></span></button> <button class="btn btn-default reviewBtn" data-reviewRoute="/usercontent/<?php echo $question->id; ?>/<?php echo @$answer['answer_id']; ?>/down"> <i class="fa fa-thumbs-down"></i>&nbsp;<span class="downVoteCount"><?php echo (@$answer['votes']['up'] ?  $answer['votes']['up'] : 0); ?></span></button></div>
														<?php endif; ?>

											</div>
											<?php endforeach; endif; ?>
							</div>

								<?php endforeach;?>
                                <div class="aamp_center">
                                    <a class="aamp_write_rev-qa" href="<?php echo $item->generateStandardURL(); ?>/questions">VIEW ALL Q & A</a>
                                </div>
                            <?php endif; ?>				
</div> 
            </div>
</section>
</amp-accordion>