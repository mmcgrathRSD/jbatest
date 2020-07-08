<div class="row main">
<h1 class="marginTopNone marginBottomNone" itemprop="name">
            <strong><?php echo $product->{'title'}?></strong>
        </h1>
        <div><small>Part#: <?php echo $product->{'tracking.model_number'}; ?></small></div>
        <div class="paddingTopMd product-rating" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
            <?php
            $stars = $product->get('review_rating_counts.overall');
            echo \Shop\Models\UserContent::outputStars($stars);
            /* echo ' ' . $product->{'review_rating_counts.overall'}; */
            ?>
        </div>

        <?php
        \Base::instance()->set('item', $product);
        echo $this->renderView ( 'Shop/Site/Views::product/blocks/lockup/price.php' );
        ?>
        <hr />
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
    <h2><strong>POST A PHOTO</strong></h2>


    <form method="POST" enctype="multipart/form-data">
    <div class="row hideOnUpload user_image_margin">
                <a data-loading-text="Loading ..." class="photoUploadBtn btn btn-default btn-lg btn-block" ><span class="glyphicon glyphicon-plus"></span>+ ADD PHOTO</a>
                <div id="uploadedPhotos"></div>
        </div>

        <div class="row user_image_margin">
            <div id="userPhotos" class="paddingTop"></div>
        </div>

        <div class="form-group">
            <label for="question1">Caption</label>
            <input class="form-control" name="caption">
        </div>

        

        

        <hr>

        <button type="submit" class="btn btn-primary btn-lg btn-block jba_button">Submit</button><br>
    </form>
</div>

<div class="col-lg-4 col-md-4  col-sm-12 col-xs-12 paddingTopLg">
    <div class="row">
        <div class=" col-lg-8 col-md-8 col-sm-7 col-xs-12">
            <a href="<?php echo $product->generateStandardURL();?>"><img src="<?php echo $product->featuredImage(); ?>" class="img-responsive" /></a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 brandButton1">
            <?php if ( !empty($product->brandLogo())): ?>
                <a href=""></a><img src="<?php echo $product->brandLogo(); ?>"  alt="160x66" class="img-responsive hidden-xs"></a>
            <?php endif;?>
        </div>
    </div>
</div>


<style>
    .user_image_margin {
        margin-top: 10px;
        margin-bottom: 10px;
        padding-left: 15px;
        padding-right: 15px;
    }

    .thumbnail {
        width: 200px;
    }
</style>