<div class="aamp_product_container">
    <h1 class="aamp_padding">
        <?php echo $item->title(); ?>
    </h1>
    <?php if(!empty($item->get('review_rating_counts.overall'))) : ?>
    <div class="aamp_padding" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

        <?php 
            $stars= $item->get('review_rating_counts.overall');
            $count= $item->get('review_rating_counts.total');
            echo \Shop\Models\UserContent::outputStars($stars);


        ?>

        <meta itemprop="ratingValue" content="<?php echo $stars; ?>">
        <meta itemprop="reviewCount" content="<?php echo $count; ?>">
        <meta itemprop="bestRating" content="5"/>
        <meta itemprop="worstRating" content="1"/>  
        <a id="reviewsShow" href="#reviews"><?php echo $count; ?> REVIEWS</a><a href="<?php echo $item->generateStandardURL(); ?>/reviews">SEE ALL REVIEWS</a>
    </div>
    <?php endif; ?> 
    <small class="aamp_mfg_num aamp_padding">MODEL # <?php echo $item->{'tracking.model_number'}; ?></small><br />
    <small class="aamp_mfg_num aamp_padding">Manufacturer Part # <?php echo $item->{'tracking.oem_model_number'}; ?></small>
    <?php echo $this->renderView('Theme/Views::amp/blocks/media.php'); ?>
    <div class="aamp_padding">
        <?php if ($item->product_type != 'dynamic_group') : ?>
            <div id="stock-status">
                <?php
                if (!empty($item->variantsAvailable()) && count($item->variantsAvailable()) > 1) {
                    echo '&nbsp;';
                } else {
                    echo $this->renderView('Theme/Views::amp/blocks/stockstatus.php', [
                        'hive' => [
                            'stockProduct' => $item
                        ]
                    ]);
                }
                ?>
            </div>
        <?php endif; ?>
        <?php echo $this->renderView('Theme/Views::amp/blocks/lockup.php'); ?>
        <?php echo $this->renderView ( 'Theme/Views::amp/blocks/add_cart.php' ); ?>
        <div class="aamp_readable">
            <?php if ($item->{'copy'}) : ?> 
                <?php echo strip_tags($item->{'copy'}, '<br><br/><a></a><p></p><b></b><strong></strong><ul><ol><li>'); ?>
            <?php endif; ?>
            <?php echo $this->renderView ( 'Theme/Views::amp/blocks/specs.php' ); ?>
            <?php echo $this->renderView ( 'Theme/Views::amp/blocks/reviews.php' ); ?>
            <?php echo $this->renderView ( 'Theme/Views::amp/blocks/questions.php' ); ?>
            <?php echo $this->renderView ( 'Theme/Views::amp/blocks/confirmed_fitment.php' ); ?>
        </div>
    </div>
</div>