<div class="product-img-box">
    <?php if($item->{'product_type'} == 'dynamic_group') : ?>
        <?php echo $this->renderLayout('Shop/Site/Views::product/kits.php')?>
    <?php endif; ?>
    <div class="product-image">
    <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/flag.php')?>
    <a href="#data-image-modal-main" rel="lighbox-zoom-gallery">
    
    <img src="<?php echo $item->featuredImage(); ?>" data-srcx2="<?php echo $item->featuredImage(); ?>" alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?>" width="473" height="473">
    </a>
    <div style="display:none"><div id="data-image-modal-main"><img src="<?php echo $item->featuredImage(); ?>" alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?>"></div></div>
    </div>
    <?php if (count($images) > 1) : ?>
    <div class="more-views">
    <div class="more-views-nav" id="thumbs_slider_nav">
        <ul>
            <li><a class="prev disabled" href="#"></a></li>
            <li><a class="next" href="#"></a></li>
        </ul>
    </div>
    <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
        <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
            <ul class="jcarousel-slider slides jcarousel-list jcarousel-list-horizontal" id="thumbs_slider" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 705px;">
            <?php foreach (array_values($images) as $key=>$image) : ?>
                <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                <a href="#data-image-modal-<?php echo $key; ?>" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                <span style="width: 92px; height: 92px;"></span>
                <img alt="<?php echo $item->title; if(!empty($item->title_suffix)) { echo ' - ' . $item->title_suffix; } ?>" title="<?php echo $item->title; ?> (Part Number: <?php echo $item->get('tracking.oem_model_number'); ?>)" src="<?php echo $item->product_image($image, [
                    'transformation' => \Base::instance()->get('cloudinary.product_page_thumb')
                ]);?>" data-srcx2="<?php echo $item->product_image($image)?>" width="110" height="110" alt="">
                </a>
                <div style="display:none"><div id="data-image-modal-<?php echo $key; ?>"><img src="<?php echo $item->product_image($image, [
                    'transformation' => \Base::instance()->get('cloudinary.product')
                ]);?>" /></div></div>
                </li>
            <?php endforeach; ?>
            </ul>
        </div>
    </div>
    </div>
    <?php endif; ?>
</div>