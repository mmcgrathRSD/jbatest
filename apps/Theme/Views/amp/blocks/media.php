<amp-carousel 
    width="768"
    height="512"
    layout="responsive"
    type="slides"
    controls>
    <?php
    if(empty($item->{'featured_image.slug'}) && !empty($images))  { $item->set('featured_image.slug', $images[0]); }  ?>
    <?php if ($item->{'featured_image.slug'}) : ?>
    <amp-img src="<?php echo $item->featuredImage(); ?>"
        width="900"
        height="600"
        layout="responsive"
        title="<?php echo $item->title; ?> ( Part Number:<?php echo $item->get('tracking.model_number'); ?>)"
        alt="<?php echo $item->title; ?> ( Part Number:<?php echo $item->get('tracking.model_number'); ?>)"
        itemprop="image"></amp-img>
    <?php endif;?>

    <?php if (count($images) > 1) : ?>
    <?php foreach ($images as $key=>$image) : ?>
    <?php if($item->{'featured_image.slug'} != $image) : ?>
    <amp-img src="<?php echo $item->showProductImage($image); ?>"
        width="900"
        height="600"
        layout="responsive"
        alt="<?php echo $item->title; ?> ( Part Number:<?php echo $item->get('tracking.model_number'); ?>)" title="<?php echo $item->title; ?> ( Part Number:<?php echo $item->get('tracking.model_number'); ?>)"></amp-img>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>

    <?php if ( !empty($item->videoid) ) { ?>
    <amp-youtube width="900"
          height="600"
          layout=responsive
          data-videoid="<?php echo $item->videoid; ?>">
    </amp-youtube>
    <?php }?>
</amp-carousel>