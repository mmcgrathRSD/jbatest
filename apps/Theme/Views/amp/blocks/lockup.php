

    <meta itemprop="manufacturer" content="<?php echo $item->get('manufacturer.title'); ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" itemprop="offers" itemscope itemtype="http://schema.org/Offer">

    <?php echo $this->renderView ( 'Theme/Views::amp/blocks/price.php' ); ?>

     <?php if($rebate = $item->checkManfacturerRebate()) : ?>
            <?php if(!empty($rebate->{'rebate.html'}) && \Dsc\ArrayHelper::get($rebate->publication, 'status') == 'published') : ?>
            <?php echo $rebate->{'rebate.html'}?>
            <?php endif; ?>
        <?php endif;?>
        
                                                       <?php 
        if ( !empty($item->{'ymmtext.additional'})):
        switch ($item->confirmFitment ()) {

            case 'confirmed' :
                echo $this->renderView ( 'Shop/Site/Views::product/fitments/confirmed.php' );
                break;

            case 'nofit' :
                echo $this->renderView ( 'Shop/Site/Views::product/fitments/nofit.php' );
                break;


            case 'universal' :
                echo $this->renderView ( 'Shop/Site/Views::product/fitments/universal.php' );
                break;
        }
        endif;
        ?>

    </div>