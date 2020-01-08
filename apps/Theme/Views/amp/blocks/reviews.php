<div class="aamp_review_head" id="reviews">
	<div class="aamp_progress">
		<div class="aamp_center">
			<strong>Overall Rating <small>(<?php echo $item->get('review_rating_counts.total'); ?> reviews)</small></strong>
		</div>
		<?php $reviews = (new \Shop\Models\UserContent())->setCondition('product_id', $item->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->setState('list.limit', 3)->getList(); ?>
		<?php if(!empty($reviews)) : ?>
		<div class="aamp_star_container">
		<?php //first echo start then review progres bars
		$stars = $item->get('review_rating_counts.overall');
		echo \Shop\Models\UserContent::outputStars($stars);
		echo ' ' . $item->{'review_rating_counts.overall'};
		?>
		</div>
		<?php if(!empty($item->get('review_rating_counts.ratings'))) : ?>
		<?php foreach ($item->get('review_rating_counts.ratings') as $key => $value) :?>
		<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong><?php echo $key?> STAR</strong></div>
		<?php if ($value !== 0) { ?>
		<?php $progPercent = round((int)$value / ($item->{'review_rating_counts.total'} / 100),0,PHP_ROUND_HALF_DOWN); ?>
			<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length bars<?php echo $progPercent; ?>"></div>
			</div>
			<div class="aamp_inline">
				<?php echo $progPercent; ?>%
			</div>
		<?php  } else { ?>
			<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length bars0"></div>
			</div>								
		<?php }?>
		</div>

		<?php endforeach;?>
		<?php endif; ?>
		<?php else : //else if!reviews ?>
		<div class="aamp_progress">
						<div class="aamp_star_container">
		<small><small>This product has no reviews.</small></small></div>
						<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong>5 STAR</strong></div>
							<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length"></div>
			</div>
				</div>

				<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong>4 STAR</strong></div>
							<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length"></div>
			</div>
				</div>

				<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong>3 STAR</strong></div>
							<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length"></div>
			</div>
				</div>

				<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong>2 STAR</strong></div>
							<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length"></div>
			</div>
				</div>

				<div class="aamp_progress_shell">
			<div class="aamp_inline"><strong>1 STAR</strong></div>
							<div class="aamp_progress_bar aamp_inline">
				<div class="aamp_progress_length"></div>
			</div>
				</div>

							</div>
		<?php endif; //end if!reviews ?>
	</div>
	<?php echo $this->renderView ( 'Theme/Views::amp/blocks/review_media.php' ); ?>
</div>
<?php if(!empty($reviews)) : ?>
<div class="clear">
	
</div>
<amp-accordion>
    <section>
		<h2 class="aamp_accordion_box">
			Reviews
		</h2>
		<div>
			<a href="<?php echo $item->generateStandardURL(false, true);?>/create/review" class="aamp_write_rev-qa"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Write a review</a>
			
		
<?php foreach($reviews as $review) : ?>
<div class="aamp_single_review">	<?php echo \Shop\Models\UserContent::outputStars($review->rating); ?>
				<?php if ($review->order_verified) : ?>

			<div class="cc-success"><small><strong>Verified Purchase</strong></small></div>
				<?php endif; ?>
					<br/>
					<strong><?php echo $review->title; ?></strong>
					<br/>
					<strong><a href="/profiles/<?php echo $review->user_id; ?>"><?php echo $review->user_name; ?></a></strong> <small class="text-muted"><?php echo $review->timeSince();?></small>
					<br/>
					<?php echo $review->copy; ?>
					<?php if(!empty($review->images) || !empty($review->videoid)) : $enableMedia = true; ?>
					<amp-carousel
						height="220"
						layout="fixed-height"
						type="slides"
						controls>
					<?php endif; ?>
					<?php if(!empty($review->images)) :?>
					<?php foreach($review->images as $index => $image) : if (!empty($image)) {?>
                    <a href="<?php echo $item->generateStandardURL(false, true);?>/reviews?open-review-modal=<?php echo $review->_id; ?>&image-index=<?php echo $index ?>">
					<amp-img src="<?php echo $review->showImage($image); ?>" height="220" layout="fixed-height"></amp-img>
                    </a>
					<?php } endforeach;  endif;?>

					<?php if ( !empty($review->videoid) ) : ?>
					<amp-youtube height="190"
                          layout="fixed-height"
                          data-videoid="<?php echo $review->videoid; ?>">
                    </amp-youtube>
					<?php
					endif;
					?>
					<?php if(isset($enableMedia)) : ?>
					</amp-carousel>
					<?php endif; ?>
                    <?php for ($i = 0; $i <= count($review->comments); $i++ ) : ?>
                        <?php if(!empty($review->comments[$i]["message"])) : ?>
                            <div class="aamp_review_comment_container">
                                <strong><a href="/profiles/<?php echo $review->comments[$i]["user_id"]; ?>"><?php echo $review->comments[$i]["username"]; ?></a></strong> <small class="text-muted"><?php echo $review->timeSince($review->comments[$i]['created']);?></small>
                                <div class="next-message">
                                    <?php echo $review->comments[$i]["message"]; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endfor; ?>
					<hr />
					<?php if ( empty($review->user_name) ) {
						$review->user_name = "Anonymous";
					}
					?>													

					<?php if ( empty($this->auth->getIdentity()->id) ) : ?>
						<?php // NOTE: should add some images here in place of what used to be buttons. No time now. AMP doesn't allow 'disabled' attribute on elements. -Austin ?>
					<?php endif; ?>
                    <a href="<?php echo $item->generateStandardURL(false, true);?>/create/comment/<?php echo $review->id; ?>" class="btn comment-btn">Comment</a>
				</div>
<?php endforeach;?>	
<?php if($item->get('review_rating_counts.total') > 3) : ?>
<div class="aamp_center">
	<a class="aamp_write_rev-qa" href="<?php echo $item->generateStandardURL(false, true);?>/reviews">VIEW ALL REVIEWS</a>
</div>
<?php endif; ?>
			</div>
</section>
</amp-accordion>
<?php endif;?>