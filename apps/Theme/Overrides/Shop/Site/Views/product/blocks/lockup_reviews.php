<?php
$sales_channel = \Base::instance()->get('sales_channel');
//Adding item if this view was called from the reviews page, not the product page.
if(empty($item)) {
	$item = $product;
}
if(!empty($reviews)) { //This is also to use this view in the reviews page, not product page. If reviews already exists from the controller, use that rather than making a new query. (Austin's notes)
	$save = [];
	foreach($reviews->items as $review) {
		$save[] = $review;
	}
	$reviews = $save;
	if(empty($questions)) {
		$save = 1;
	}
}
else {
	$reviews = (new \Shop\Models\UserContent())->setCondition('product_id', $item->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->setCondition('publication.sales_channels.slug', $sales_channel)->getList();
}

//This is a catch if the queue task is not run to update the product about review counts
if(!empty($reviews) && empty($item->{'review_rating_counts.total'})) {
    $item->set('review_rating_counts.total',count($reviews));
}

\Dsc\System::instance()->get( 'session' )->set('site.login.redirect', \Base::instance()->get('PARAMS.0'));

$ease = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.ease')) ? ((float) \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.ease')) * 20 : 0;
$overall = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) ? ((float) \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) * 20 : 0;
$fit = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.fit')) ? ((float) \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.fit')) * 20 : 0;
$count = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total')) ? \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total') : 0;
?>
<div class="collateral-box dedicated-review-box" id="product-customer-reviews">
    <div class="title-container clearfix">
        <h3><?php echo $count; ?> customer review<?php echo $count != 1 ? 's' : ''; ?></h3>
        <button type="button" title="Submit Review" class="button jba_button">Submit Review</button>
    </div>
    <div class="row">
        <div class="average-rating col-md-5 col-sm-12">
            <div class="f-left">
                <strong>Average rating</strong>
                <div class="rating-box rating-large">
                <div class="rating" style="width: <?php echo $overall; ?>%"></div>
                </div>
                <span class="reviews-count">(based on <?php echo $count; ?> review<?php echo $count != 1 ? 's' : ''; ?>)</span>
            </div>
            <div class="f-left">
                <table class="ratings ratings-table">
                <colgroup>
                    <col width="1">
                    <col>
                </colgroup>
                <tbody>
                    <tr>
                        <td>
                            <div class="rating-box">
                            <div class="rating" style="width: <?php echo $ease; ?>%"></div>
                            </div>
                        </td>
                        <th><span>Ease of Installation</span></th>
                    </tr>
                    <tr>
                        <td>
                            <div class="rating-box">
                            <div class="rating" style="width: <?php echo $fit; ?>%"></div>
                            </div>
                        </td>
                        <th><span>Fit / Quality</span></th>
                    </tr>
                    <tr>
                        <td>
                            <div class="rating-box">
                            <div class="rating" style="width: <?php echo $overall; ?>%"></div>
                            </div>
                        </td>
                        <th><span>Overall Satisfaction</span></th>
                    </tr>
                </tbody>
                </table>
            </div>
            <div class="clear"></div>
            
        </div>
        <div class="col-md-6 col-sm-12 user_media">
            <?php echo $this->renderView ( 'Shop/Site/Views::product/usercontent/new_media.php' ); ?>
        </div>
    </div>
    <ol class="reviews-list" style="clear: both">
        <?php foreach($reviews as $review) : ?>
            <li>
            <h3 class="review-title"><?php echo $review['title']; ?></h3>
            <div class="review-info">
                <div class="rating-box">
                    <div class="rating" style="width:<?php echo $review->rating * 20; ?>%;"></div>
                </div>
                By <b><?php echo !empty($review->user_name) ? $review->user_name : 'Anonymous'; ?></b>	            <span class="separator"></span>
                <b><?php echo \DateTime::createFromFormat('Y-m-d H:i:s', $review->get('metadata.created.local'))->format('F j, Y'); ?></b>
            </div>
            <p><?php echo $review->copy; ?></p>
            </li>
        <?php endforeach; ?>
    </ol>
</div>
<div class="add-review" id="review-form">
    <div class="form-add">
        <h2>Write Your Own Review</h2>
        <?php if(empty($this->auth->getIdentity()->id)) : ?>
            <p class="review-nologged" id="review-form">
                Only registered users can write reviews. Please, <a href="/sign-in">log in</a> or <a href="/register">register</a>    
            </p>
        <?php else : ?>
            <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/add_review.php')?>
        <?php endif; ?>
    </div>
</div>