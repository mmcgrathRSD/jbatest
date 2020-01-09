<div>
<div class="clearfix">

<div class="col-md-12">

    <div class="portlet">

        <div class="portlet-header">

            <h3>Image</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">

            <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('promo_image', $item->{'promo_image'}, array('field'=>'promo_image') ); ?>
        
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    

</div>
<!-- /.col-md-8 -->

<div class="col-md-12">

    <div class="portlet">

        <div class="portlet-header">

            <h3>Link</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
	<input name="promo_link" class="form-control" type="text" value="<?php echo $item->{'promo_link'};?>">
	
	<input name="display[output_type]" value="raw" type="hidden">
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    

</div>

</div>