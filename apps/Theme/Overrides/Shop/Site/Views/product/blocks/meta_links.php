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
    <div class="rating" style="width:100%;" NOTE></div>
</div>
<p class="rating-links">
    <a href="NOTE">X Review(s)</a>
    <span class="separator">|</span>
    <a href="NOTE">Add Your Review</a>
</p>
</div>