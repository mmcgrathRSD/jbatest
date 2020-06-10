<div class="row main">
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
    <h2><strong>POST A PHOTO</strong></h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="question1">Caption</label>
            <input class="form-control" name="caption">
        </div>

        <div class="row hideOnUpload">
            <div class="col-sm-12 paddingTop">
                <a data-loading-text="Loading ..." class="photoUploadBtn btn btn-default btn-lg btn-block" ><span class="glyphicon glyphicon-plus"></span>+ ADD PHOTO</a>
                <div id="uploadedPhotos"></div>
            </div>
        </div>

        <div class="row">
            <div id="userPhotos" class="col-sm-12 paddingTop"></div>
        </div>

        <hr>

        <button type="submit" class="btn btn-primary btn-lg btn-block">POST</button><br>
    </form>
</div>

<?php echo $this->renderView ('Shop/Site/Views::usercontent/lockup.php'); ?>
