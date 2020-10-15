<?php 
$gtin = trim($item->get('tracking.upc'));
$sales_channel = \Base::instance()->get('sales_channel');
$count = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total')) ? \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.total') : 0; 
$overall_rating = !empty(\Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) ? ((float) \Dsc\ArrayHelper::get((array) $item, 'review_rating_counts.sales_channel.' . $sales_channel . '.overall')) * 20 : 0;
?>
<meta itemprop="brand" content="<?php echo $item->manufacturer['title']; ?>">
<meta itemprop="sku" content="<?php echo $item->get('tracking.oem_model_number'); ?>">
<meta itemprop="mpn" content="<?php echo $item->get('tracking.oem_model_number'); ?>">
<?php if(!empty($gtin) && $item->isValidBarcode($gtin)) : ?>
<meta itemprop="gtin" content="<?php echo $gtin; ?>">
<?php endif; ?>
<meta itemprop="category" content="<?php echo $item->get('categories.0.title'); ?>">
<meta itemprop="itemCondition" content="https://schema.org/NewCondition" />
<meta itemprop="image" content="<?php echo $item->featuredImage(); ?>">
<span itemprop="weight" itemscope itemtype="http://schema.org/QuantitativeValue">
    <meta itemprop="value" content="<?php echo $item->get('shipping.packages.0.weight'); ?>"/>
    <meta itemprop="unitCode" content="LBR">
</span>
<div class="product-name " itemprop="name">
<h1><?php echo $item->title; ?></h1>
<?php if(!empty($item->title_suffix)) : ?>
<h2 class="product_suffix block marginTopNone"> - <?php echo $item->title_suffix; ?></h2>
<?php endif; ?>
<?php if(!empty($item->h2)) : ?>
<h2 class="product_suffix block marginTopNone"> - <?php echo $item->h2; ?></h2>
<?php endif; ?>
</div>
<div class="ratings" <?php if($count > 0) : ?>itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating"<?php endif; ?>>
<?php if($count > 0) : ?>
<meta itemprop="ratingValue" content="<?php echo $overall_rating / 20; ?>" />
<meta itemprop="reviewCount" content="<?php echo $count; ?>" />
<?php endif; ?>
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