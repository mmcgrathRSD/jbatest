<div class="row">
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 paddingBottom">

<div class="visible-xs visible-sm">
		
			<h2 class="paddingTopLg">
				<a href="/user/change-avatar"><img class="img-responsive img-circle"
					src="<?php echo $user->profilePicture('/images/profile/default.png');?>" /></a>
			</h2>
			<h2>
			    <a name="profile"></a>
				<h3><?php echo $user['first_name']; ?><br /></h3> 
			</h2>
</div>

<!-- Nav tabs -->


	<h1 class="hidden-xs hidden-sm"><?php echo $user['first_name']; ?></h1>

	
	  <ul class="nav nav-tabs paddingTop" role="tablist">
    <li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Profile</a></li>
    <li role="presentation"><a href="#reviews" aria-controls="profile" role="tab" data-toggle="tab">Reviews</a></li>
    <li role="presentation"><a href="#qa" aria-controls="messages" role="tab" data-toggle="tab">Questions & Answers</a></li>
    <li role="presentation"><a href="#images" aria-controls="settings" role="tab" data-toggle="tab">Images</a></li>
  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="profile">
    
	<h3>Garage</h3>

			<?php if(count($user->garage) && $user->{'preferences.profile.showgarage'} ) : ?>
			
				<ul class="list-unstyled">
									
					<?php foreach($user->garage as $key=>$car) : ?>
						<li class="list-group-item">
							<a href="/shop/vehicle/<?php echo $car['slug']; ?>"><?php echo $car['title']; ?></a>
						</li>
					<?php endforeach;?>
							
				</ul>
			<?php else: ?>
			<div class="alert alert-warning" role="alert">
  				<strong><?php echo $user->{'profile.screen_name'}; ?></strong> has an empty or private garage.
			</div>
			<?php endif;?>    
    </div>
    <div role="tabpanel" class="tab-pane" id="reviews">
    
<?php
			$reviews = (new \JBAShop\Models\UserContent())->setCondition('user_id', $user->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->getList();
			?>
			<h3>Reviews</h3>
			<?php	
			if(count($reviews)) : ?>
			<h4><?php echo $user['first_name']; ?> has reviewed <Strong><?php echo count($reviews)?></Strong> Product(s).</h4>
				<ul class="list-unstyled">
								
									<?php foreach($reviews as $review) : ?>
									<div class="well ">	<?php echo \JBAShop\Models\UserContent::outputStars($review->rating); ?>

														<br/>
														<strong><?php echo $review->title; ?></strong>
														<br/>
														<strong><a href="/profiles/<?php echo $review->user_id; ?>"><?php echo $review->user_name; ?></a></strong> <small class="text-muted"><?php echo $review->timeSince();?></small>
													    <br/>														
														<small>  
															<?php echo $review->copy; ?>
														</small>
														<?php if(!empty($review->images)) :?><hr>																												
														<?php foreach($review->images as $image) : if (!empty($image)) {?>
															
															<a href="#" data-href="<?php echo $review->showImage($image); ?>" data-toggle="modal" data-target="#lightbox" class=""><img src="<?php echo $review->showImageThumb($image); ?>" class="img-thumbnail"  style="height:85px"></a>
														<?php } endforeach;  endif;?>
														
														<?php if ( !empty($review->videoid) ) : ?>
																	<a href="#"  data-video="<?php echo $review->videoid; ?>" data-toggle="modal" data-target="#lightbox"><div class="videothumbnail"> <i class="glyphicon glyphicon-play-button"></i><img src="//img.youtube.com/vi/<?php echo $review->videoid; ?>/2.jpg" class="img-thumbnail videoLink" style="height:85px"></div></a>
														<?php
														endif;
														?>														
														<hr class="marginTopBottom">	
														<?php if ( empty($review->user_name) ) {
														
															$review->user_name = "Anonymous";
														}
														?>													
													
														<a href="/shop/product/<?php echo $review->product_slug ?>"><?php echo $review->product_title; ?></a>	
													</div>
									<?php endforeach;?>		
							
				</ul>
			<?php else: ?>
			<div class="alert alert-warning" role="alert">
  				<strong><?php echo $user->{'profile.screen_name'}; ?></strong> has not reviewed any products.
			</div>
			<?php endif;?>    
    </div>
    <div role="tabpanel" class="tab-pane" id="qa">
    
 			<h3>Questions</h3>
			<?php
			$questions = (new \JBAShop\Models\UserContent())->setCondition('user_id', $user->id)->setCondition('type', 'question')->setCondition('publication.status', 'published')->getList();
				
			
			
			if(count($questions)) : ?>
			<h4><?php echo $user['first_name']; ?> has asked <Strong><?php echo count($questions)?></Strong> Question(s).</h4>
				<ul class="list-unstyled">
									
<?php foreach ($questions as $question) : ?>
							
									<li>
										
										<h4 ><a href="/shop/product/<?php echo $question->product_slug ;?>/create/answer/<?php echo $question->id?>" class="btn btn-xs btn-primary pull-right">Answer</a><strong>Q: </strong><?php echo $question->title; ?>&nbsp;&nbsp;<small><strong><a href="/profiles/<?php echo @$question['user_id']; ?>"><?php echo @$question['user_name']; ?></a></strong> <small class="text-muted"> <?php echo $question->timeSince(); ?></small> </small></h4>
										    <p>
												<?php echo $question->copy; ?>
																	
														<?php if(!empty($question->images)) :?><hr>																												
														<?php foreach($question->images as $image) : if (!empty($image)) {?>
															
															<a href="#" data-href="<?php echo $question->showImage($image); ?>" data-toggle="modal" data-target="#lightbox" class=""><img src="<?php echo $question->showImageThumb($image); ?>" class="img-thumbnail"  style="height:85px"></a>
														<?php } endforeach;  endif;?>
														
														<?php if ( !empty($question->videoid) ) : ?>
																	<a href="#"  data-video="<?php echo $question->videoid; ?>" data-toggle="modal" data-target="#lightbox"><div class="videothumbnail"> <i class="glyphicon glyphicon-play-button"></i><img src="//img.youtube.com/vi/<?php echo $question->videoid; ?>/2.jpg" class="img-thumbnail videoLink" style="height:85px"></div></a>
														<?php
														endif;
														?>	
												
											</p>


										<br/>

								
			 						</li>
					<?php endforeach;?>
							
				</ul>
			<?php else: ?>
			
			<div class="alert alert-warning" role="alert">
  				<strong><?php echo $user->{'profile.screen_name'}; ?></strong> has not asked any questions.
			</div>

			<?php endif;?>
			<h3>Answers</h3>
			<?php
			$answers = (new \JBAShop\Models\UserContent())->setCondition('answers.user_id', $user->id)->setCondition('type', 'question')->setCondition('publication.status', 'published')->getList();
				
			
			
			if(count($answers)) : ?>
			<h4><?php echo $user['first_name']; ?> has answered <Strong><?php echo count($answers)?></Strong> Question(s).</h4>
				<ul class="list-unstyled">
					<?php $i=0; ?>				
					<?php foreach($answers as $answer) : ?>
					
					<?php if(++$i > 5) break; ?>
						<li class="list-group-item">
							<small><?php echo $answer->title ?></small>
							<p><?php echo $answer->copy ?></p>
							<div>
							A: <?php foreach($answer->answers as $doc) :?>
							<?php if(@$doc['user_id'] == $user->id) :?>
							<?php echo $doc['message']; ?>
							<?php endif;?>
								 <?php endforeach ;?>
							</div>
							
						</li>
					<?php endforeach;?>
							
				</ul>
			<?php else: ?>
			<div class="alert alert-warning" role="alert">
  				<strong><?php echo $user->{'profile.screen_name'}; ?></strong> has not answered any questions.
			</div>
			<?php endif;?>   
    </div>
    <div role="tabpanel" class="tab-pane" id="images">
    
    <h3>Images</h3>
			<?php
			$images = (new \JBAShop\Models\UserContent())->setCondition('user_id', $user->id)->setCondition('publication.status', 'published')->getList();
				
			
			
			if(count($images)) : ?>
		
				<ul class="list-unstyled">
									
					<?php foreach($images as $image) : ?>
						<a href="#" data-href="/asset/<?php echo $image->image ?>" data-toggle="modal" data-target="#lightbox">
							<img src="<?php #echo $userContent->media_thumb($image) ?>" class="img-thumbnail">
    					</a>
					<?php endforeach;?>
							
				</ul>
			<?php else: ?>
			<div class="alert alert-warning" role="alert">
  				<strong><?php echo $user->{'profile.screen_name'}; ?></strong> has not submitted any images.
			</div>
			<?php endif;?>
    
    </div>
  </div>
	
	


			
			

			
			
			
			
</div>
<div class="col-xs-3 paddingBottom hidden-sm hidden-xs">

			<h2 class="paddingTopLg">
				<a href="/user/change-avatar"><img class="img-responsive img-circle"
					src="<?php echo $user->profilePicture('/images/profile/default.png');?>" /></a>
			</h2>
			<h2>
			    <a name="profile"></a>
				<h3><?php echo $user['first_name']; ?><br /></h3>


			</h2>
		
</div>		
</div>