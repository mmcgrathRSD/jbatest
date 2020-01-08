<div class="aamp_review_media">
    <div class="aamp_center">
        <strong>User Media</strong>
    </div>
    <?php 
    $saveUserContent = [];
    $hasMedia = false;
    foreach($item->getUserMedia() as $allUserContent) :
        $saveUserContent[] = $allUserContent;
        if(count($allUserContent->images) > 0 || !empty($allUserContent->videoid)) { $hasMedia = true; }
    endforeach;
    if($hasMedia) : ?>
    <amp-carousel 
        height="220"
        layout="fixed-height"
        type="slides"
        controls>
        <?php foreach($saveUserContent as $userContent) : ?>
            <?php if(!empty($userContent)) : ?>
                  <?php foreach($userContent->images as $index => $image): ?>
                    <?php if (!empty($image)) : ?>
                        <a href="<?php echo $item->generateStandardURL(false, true);?>/reviews?open-review-modal=<?php echo $userContent->_id; ?>&image-index=<?php echo $index ?>">
                            <amp-img src="<?php echo $userContent->image($image) ?>"
                                height="220"
                                layout="fixed-height"
                                <?php echo in_array($userContent->type, ['photo', 'video']) ? 'title="' . $userContent->copy . '"' : '' ?>></amp-img>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?> 
                <?php if ( !empty($userContent->videoid) ) { ?>
                    <amp-youtube height="190"
                          layout="fixed-height"
                          data-videoid="<?php echo $userContent->videoid; ?>">
                    </amp-youtube>
                <?php } ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </amp-carousel>
    <?php else : ?>
        <div class="aamp_empty_review">
        This product has no user uploaded media. You can upload your photo or video of your install by posting a <a href="<?php echo $item->generateStandardURL();?>/create/review">review</a>. 
        </div>
    <?php endif;?>
</div>
