<?php
$imageIndex = 1;
if(!empty($images)) {
    $imageIndex += count($images);
}
if(!empty($item->video_id)) {
    $imageIndex += 1;
}

if(empty($item)) {
	$item = $product;
}

if(empty($item->{'featured_image.slug'}) && !empty($images))  {
    $item->set('featured_image.slug', $images[0]);
}

//get all the user data
if(!empty($userData)) {
    $userMedia = [];


    foreach($userData as $data) {
        if(!empty($data->images)) {
            foreach($data->images as $image) {
                $userMedia[] = [
                        'image',
                        $image
                    ];
            }
        }

        if(!empty($data->videoid)) {
            $userMedia[] = [
                    'video',
                    $data->videoid
                ];
        }
    }
}
?>

<?php
if(empty($item)) {
	$item = $product;
}

if(empty($item->{'featured_image.slug'}) && !empty($images))  {
    $item->set('featured_image.slug', $images[0]);
}

//get all the user data
$userData = $item->getUserMedia();
$userMedia = [];
$imageIndex = 1;

foreach($userData as $data) {
    if(!empty($data->images)) {
        foreach($data->images as $image) {
            $userMedia[] = [
                    'image',
                    $image
                ];
        }
    }

    if(!empty($data->videoid)) {
        $userMedia[] = [
                'video',
                $data->videoid
            ];
    }
}
?>

<!-- <div class="user_media_heading">Customer Photos and Videos</div>
<div class="variable-width"> -->
   <?php
      //Adding item if this view was called from the reviews page, not the product page.
      if(empty($item)) {
      	$item = $product;
      }
     ?>
   <?php if($userMedia) : ?>
   <div class="product-img-box">
    <div class="more-views">
    <div class="more-views-nav" id="thumbs_slider_nav">
        <ul>
            <li><a class="prev user-prev" href="#"></a></li>
            <li><a class="next user-next" href="#"></a></li>
        </ul>
    </div>
    <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
        <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
            <ul class="jcarousel-slider2 slides jcarousel-list jcarousel-list-horizontal" id="thumbs_slider2" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 705px;">
   <?php foreach($userMedia as $key=>$media) : ?>
        
                <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1<?php echo $key+'123123'; ?> jcarousel-item-1<?php echo $key+'123123'; ?>-horizontal" jcarouselindex="1<?php echo $key+'123123'; ?>" style="float: left; list-style: none;">
                    <a href="#data-image-modal-<?php echo $key+'123123'; ?>" class="lighbox-zoom-gallery" rel="lighbox-zoom-gallery" title="">
                        <span style="width: 110px; height: 110px;"></span>
                        <img alt="<?php echo $item->title; ?>" title="<?php echo $item->title; ?> (Part Number: <?php echo $item->get('tracking.oem_model_number'); ?>)" src="<?php echo $userData[0]->media_thumb($media[1]); ?>" data-srcx2="<?php echo $userData[0]->media_thumb($media[1]); ?>" width="110" height="110" alt="">
                    </a>
                    <div style="display:none"><div id="data-image-modal-<?php echo $key+'123123'; ?>"><img src="<?php echo $userData[0]->media_thumb($media[1]); ?>" /></div></div>
                </li>
            
        <?php
        $imageIndex++;
        endforeach;
        ?>
        </ul>
        </div>
    </div>
    </div>
    </div>
   <?php endif;?>
<style>

.user_media .jcarousel-item {
    min-height: 110px;
}
</style>
<div style="clear:both;"></div>