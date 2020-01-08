<div>
<div class="clearfix">

<div class="col-md-12">

    <div class="portlet">

        <div class="portlet-header">

            <h3>Link</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
        	<input name="promo_link" class="form-control" type="text" value="<?php echo $item->{'promo_link'};?>">
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    

    <div class="portlet">

        <div class="portlet-header">

            <h3>Text</h3>
                <p>Must put {timer} in this section with your text to include timer ex: Black Friday Sale {timer}</p>
        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_text" class="form-control" type="text" value="<?php echo $item->{'promo_text'};?>">
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    

 <div class="portlet">

        <div class="portlet-header">

            <h3>Count Down Time - Always MST! Format: YYYY-MM-DDTHH:MM-HH:MM (ex: 2017-11-17T12:00:00-06:30)</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_count_down_time" class="form-control" type="text" value="<?php echo $item->{'promo_count_down_time'};?>">
        </div>
        <!-- /.portlet-content -->

    </div>


 <div class="portlet">

        <div class="portlet-header">

            <h3>Count Down Format (%D days %H:%M:%S)</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_count_down_format" class="form-control" type="text" value="<?php echo ($item->{'promo_count_down_format'} ?  $item->{'promo_count_down_format'} :  '%D days %H:%M:%S');  ?>">
        </div>
        <!-- /.portlet-content -->

    </div>
 
<hr>
<h2>Look and Feel</h2>
 <div class="portlet">

        <div class="portlet-header">

            <h3>#HEX COLOR CODE OF FOOTER BACKGROUND</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_background_hex_code" class="form-control" type="text" value="<?php echo ($item->{'promo_background_hex_code'} ?  $item->{'promo_background_hex_code'} :  '#0155ab');  ?>">
        </div>
        <!-- /.portlet-content -->

    </div>
     <div class="portlet">

        <div class="portlet-header">

            <h3>#HEX COLOR CODE OF FOOTER TEXT COLOR</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_text_color_hex_code" class="form-control" type="text" value="<?php echo ($item->{'promo_text_color_hex_code'} ?  $item->{'promo_text_color_hex_code'} :  '#FFFFFF');  ?>">
        </div>
        <!-- /.portlet-content -->

    </div>
    
 <div class="portlet">

        <div class="portlet-header">

            <h3>#HEX COLOR CODE OF FOOTER TEXT COLOR : Highlight Color of <strong>strong</strong> Tag</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input name="promo_text_highlight_color_hex_code" class="form-control" type="text" value="<?php echo ($item->{'promo_text_highlight_color_hex_code'} ?  $item->{'promo_text_highlight_color_hex_code'} :  '#FFFFFF');  ?>">
        </div>
        <!-- /.portlet-content -->

    </div>


</div>