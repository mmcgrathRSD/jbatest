<?php 
    $options = $item->get('Hero_Slides')
?>
<div id="hero-images">
    <div class="clearfix"></div>
<?php $i=1; foreach($options as $option){ ?>
    <div class="hero-slide">
        <span class="removeSlide" style="color:#a20808; text-align:right; float:right;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>REMOVE SLIDE</span>
        <h4>
            <strong>Slide #<?php echo $i; ?></strong>
        </h4>
        <div class="hero-slide-inner">
            <div class="col-sm-12">
                <label>Desktop Slide Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][desktop_image][src]" id="HERO" value="<?php if(isset($option['desktop_image']['src'])){echo $option['desktop_image']['src'];} ?>">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <label>Mobile Slide Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][mobile_image][src]" id="HERO" value="<?php if(isset($option['mobile_image']['src'])){echo $option['mobile_image']['src'];} ?>">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <label>Alt tag</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][image][alt]" id="HERO" value="<?php if(isset($option['image']['alt'])){echo $option['image']['alt'];} ?>">
            </div>
            <div class="col-sm-6">
                <label>Image Width</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][image][width]" id="HERO" value="<?php if(isset($option['image']['width'])){echo $option['image']['width'];} ?>">
            </div>
            <div class="col-sm-6">
                <label>Image Height</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][image][height]" id="HERO" value="<?php if(isset($option['image']['height'])){echo $option['image']['height'];} ?>">
            </div>
            <div class="col-sm-12">
                <label>Line 1</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][line1]" value="<?php if(isset($option['line1'])) { echo $option['line1']; }?>">
            </div>
            <div class="col-sm-12">
                <label>Line 2</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][line2]" value="<?php if(isset($option['line2'])) { echo $option['line2']; }?>">
            </div>
            <div class="col-sm-12">
                <label>Line 3</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][line3]" value="<?php if(isset($option['line3'])) { echo $option['line3']; }?>">
            </div>
            <div class="col-sm-12">
                <label>Link</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][link][href]" value="<?php if(isset($option['link']['href'])) { echo $option['link']['href']; }?>">
            </div>
            <div class="col-sm-12">
                <label>Link Text</label>
                <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][link][text]" value="<?php if(isset($option['link']['text'])) { echo $option['link']['text']; }?>">
            </div>
        </div>
       
    </div>
<?php $i++;}?>
    


 <template type="text/template" id="add-slide-template">
        <fieldset class="template well clearfix">
            <a class="remove-image btn btn-xs btn-danger pull-right" onclick="RemoveRelatedImage(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>
            <label>New Slide</label>
            <div class="col-sm-12">
                <label>Desktop Slide Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide{id}][desktop_image][src]" value="">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <label>Mobile Slide Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide{id}][mobile_image][src]" value="">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <label>Alt tag</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][image][alt]" id="HERO" value="">
            </div>
            <div class="col-sm-6">
                <label>Image Width</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][image][width]" id="HERO" value="">
            </div>
            <div class="col-sm-6">
                <label>Image Height</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][image][height]" id="HERO" value="">
            </div>
            <div class="col-sm-12">
                <label>Line 1</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][line1]" value="">
            </div>
            <div class="col-sm-12">
                <label>Line 2</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][line2]" value="">
            </div>
            <div class="col-sm-12">
                <label>Line 3</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][line3]" value="">
            </div>
            <div class="col-sm-12">
                <label>Link</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][href]" value="">
            </div>
            <div class="col-sm-12">
                <label>Link Text</label>
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][text]" value="">
            </div>
        </fieldset>
</template>
<div id="new-slider-images" class="form-group"></div>
<div class="form-group">
    <a class="btn btn-warning" id="add-slide-image">Add New Slide</a>
</div>  

<script>
jQuery(document).ready(function(){
    window.new_Slide_images = $('.hero-slide').length+1;
    jQuery('#add-slide-image').click(function(){
        var container = jQuery('#new-slider-images');
        var template = jQuery('#add-slide-template').html();
        template = template.replace( new RegExp("{id}", 'g'), window.new_Slide_images);
        container.append(template);
        window.new_Slide_images = window.new_Slide_images + 1;
        Dsc.setupColorbox();
    });

    RemoveRelatedImage = function(el) {
        jQuery(el).parents('.template').remove();
    }

    $('.removeSlide').on('click',function(){
        $(this).parent().remove();
    });

});
</script>
</div>