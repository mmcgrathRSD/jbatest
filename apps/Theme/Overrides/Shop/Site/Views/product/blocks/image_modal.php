<?php //west says he's sure that the first image in the array will 110% always be the featured image. 
$featuredImage = $item->featuredImage();
if(!empty($images[0])) {
    if(!empty($images[0]['alt'])) {
        $alt = $images[0]['alt'];
    } else {
        $alt = $item->title;

        if(!empty($item->title_suffix)) {
            $alt .= ' - ' . $item->title_suffix;
        }
    }
    

    $title = $images[0]['title'] ?? $item->title . ' (Part Number: ' . $item->get('tracking.oem_model_number') . ')';
} else {
    $alt = $item->title;
    $title = $item->title . ' (Part Number: ' . $item->get('tracking.oem_model_number') . ')';
}
?>
<div class="product-img-box">
    <?php if($item->{'product_type'} == 'dynamic_group') : ?>
        <?php echo $this->renderLayout('Shop/Site/Views::product/kits.php')?>
    <?php endif; ?>
    <div class="product-image">
    <?php echo $this->renderLayout('Shop/Site/Views::product/blocks/flag.php')?>
    <a href="#data-image-modal-main" rel="lighbox-zoom-gallery">
    
    <img src="<?php echo $featuredImage; ?>" data-srcx2="<?php echo $featuredImage; ?>" alt="<?php echo $alt ?>" title="<?php echo $title; ?>" width="473" height="473">
    </a>
    <div style="display:none"><div id="data-image-modal-main"><img src="<?php echo $featuredImage; ?>" alt="<?php echo $alt; ?>" title="<?php echo $title; ?>"></div></div>
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
                <?php
                if(!empty($image['alt'])) {
                    $alt = $image['alt'];
                } else {
                    $alt = $item->title;

                    if(!empty($item->title_suffix)) {
                        $alt .= ' - ' . $item->title_suffix;
                    }
                }
                

                $title = $image['title'] ?? $item->title . ' (Part Number: ' . $item->get('tracking.oem_model_number') . ')';

                ?>
                <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                <a href="#data-image-modal-<?php echo $key; ?>" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                <span style="width: 92px; height: 92px;"></span>
                <img alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" src="<?php echo $item->product_image($image['image'], [
                    'transformation' => \Base::instance()->get('cloudinary.product_page_thumb')
                ]);?>" data-srcx2="<?php echo $item->product_image($image['image'])?>" width="110" height="110">
                </a>
                <div style="display:none"><div id="data-image-modal-<?php echo $key; ?>"><img alt="<?php echo $alt; ?>" title="<?php echo $title; ?>" src="<?php echo $item->product_image($image['image'], [
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