<?php 
$sales_channel = \Base::instance()->get('sales_channel');
$count = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total')) ? \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total') : 0; 
$overall_rating = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) ? ((float) \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) * 20 : 0;
?>
<meta itemprop="brand" content="<?php echo $item->manufacturer['title']; ?>">
<meta itemprop="sku" content="<?php echo $item->tracking['model_number']; ?>">
<meta itemprop="category" content="<?php echo $this->app->get('gtm.category_name'); ?>">
<meta itemprop="image" content="<?php echo $item->featuredImage(); ?>">
<div class="product-name " itemprop="name">
<h1><?php echo $item->title; ?></h1>
<?php if(!empty($item->title_suffix)) : ?>
<h2 class="product_suffix block marginTopNone"> - <?php echo $item->title_suffix; ?></h2>
<?php endif; ?>
<?php if(!empty($item->h2)) : ?>
<h2 class="product_suffix block marginTopNone"> - <?php echo $item->h2; ?></h2>
<?php endif; ?>
</div>
<div class="ratings" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
<meta itemprop="ratingValue" content="NOTE">
<meta itemprop="reviewCount" content="NOTE">
<div class="rating-box">
    <div class="rating" style="width: <?php echo $overall_rating; ?>%;" NOTE></div>
</div>
<?php if($count == 0) : ?>
    <p class="no-rating"><a href="https://www.subispeed.com/review/product/list/id/23843/category/5/#review-form">Be the first to review this product</a></p>
<?php else : ?>
    <p class="rating-links">
        <a href="NOTE"><?php echo $count; ?> Review<?php echo $count != 1 ? 's' : ''; ?></a>
        <span class="separator">|</span>
        <a href="NOTE">Add Your Review</a>
    </p>
<?php endif; ?>
</div>