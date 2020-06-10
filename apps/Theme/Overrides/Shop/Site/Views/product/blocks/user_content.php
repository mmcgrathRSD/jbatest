<div class="box-collateral box-amcustomerimg-form" id="box-amcustomerimg-form" style="">
    <h2>Upload images for this product</h2>
    <iframe id="box-amcustomerimg-form-target" name="box-amcustomerimg-form-target" style="display: none; width: 600px; height: 600px;"></iframe>
    <div><br>Feel free to share pictures of your vehicle with this product!<br><br></div>
    <div id="box-amcustomerimg-form-error" style="display: none;"></div>
    <div id="box-amcustomerimg-form-success" style="display: none;"></div>
    <div id="box-amcustomerimg-form-openlink">
    <a href="<?php echo $item->generateStandardURL(); ?>/create/image"">+ Start Uploading My Own Images</a>
    </div>
    <div id="box-amcustomerimg-form-form-container" style="display: none;">
    <form method="post" action="https://www.subispeed.com/amcustomerimg/image/upload/" id="box-amcustomerimg-form-form" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="product_id" value="13193">
        <div class="form-add">
            <label>Add Your Images:</label>
            <div class="input-box">
                <p><input type="file" name="image[0]" class="input-file" size="50"> &nbsp;&nbsp;
                Title:  <input type="text" name="title[0]" class="input-text" value="" size="30">
                </p>
                <p><input type="file" name="image[1]" class="input-file" size="50"> &nbsp;&nbsp;
                Title:  <input type="text" name="title[1]" class="input-text" value="" size="30">
                </p>
                <p><input type="file" name="image[2]" class="input-file" size="50"> &nbsp;&nbsp;
                Title:  <input type="text" name="title[2]" class="input-text" value="" size="30">
                </p>
            </div>
            Email address:                         <em style="color: red;">*</em>                        <br>
            <input type="text" name="guest_email" id="guest_email" class="input-text validate-email required-entry" size="42">
            <input type="hidden" name="check1" id="check1" value="¦¨¢">
            <input type="hidden" name="check2" id="check2" value="">
            <br>                
            <p>
                <button type="button" onclick="javascript: amcustomerimg_upload('box-amcustomerimg-form');" class="button upload-button" title="Upload Images">
            <p></p>
            <span>
            <span>Upload Images</span>
            </span>
            </button>
            </p>
        </div>
    </form>
    </div>
</div>