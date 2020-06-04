<?php
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
	$reviews = (new \Shop\Models\UserContent())->setCondition('product_id', $item->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->setState('list.limit', 5)->getList();
}

//This is a catch if the queue task is not run to update the product about review counts
if(!empty($reviews) && empty($item->{'review_rating_counts.total'})) {
    $item->set('review_rating_counts.total',count($reviews));
}

?>
<div class="collateral-box dedicated-review-box" id="product-customer-reviews">
    <div class="title-container clearfix">
        <h3>3 customer reviews</h3>
        <button type="button" title="Submit Review" class="button"><span><span>Submit Review</span></span></button>
    </div>
    <div class="average-rating">
        <div class="f-left">
            <strong>Average rating</strong>
            <div class="rating-box rating-large">
            <div class="rating" style="width:93%;"></div>
            </div>
            <span class="reviews-count">(based on 3 reviews)</span>
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
                        <div class="rating" style="width:80%;"></div>
                        </div>
                    </td>
                    <th><span>Ease of Installation</span></th>
                </tr>
                <tr>
                    <td>
                        <div class="rating-box">
                        <div class="rating" style="width:100%;"></div>
                        </div>
                    </td>
                    <th><span>Fit / Quality</span></th>
                </tr>
                <tr>
                    <td>
                        <div class="rating-box">
                        <div class="rating" style="width:100%;"></div>
                        </div>
                    </td>
                    <th><span>Overall Satisfaction</span></th>
                </tr>
            </tbody>
            </table>
        </div>
        <div class="clear"></div>
    </div>
    <ol class="reviews-list">
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
<div class="add-review">
    <div class="form-add">
        <h2>Write Your Own Review</h2>
        <p class="review-nologged" id="review-form">
            Only registered users can write reviews. Please, <a href="https://www.subispeed.com/customer/account/login/referer/aHR0cHM6Ly93d3cuc3ViaXNwZWVkLmNvbS9jYXRhbG9nL3Byb2R1Y3Qvdmlldy9pZC8xMzE5My8jcmV2aWV3LWZvcm0,/">log in</a> or <a href="https://www.subispeed.com/customer/account/create/">register</a>    
        </p>
    </div>
</div>