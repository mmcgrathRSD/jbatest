<style>
    .hero-slide {
            padding: 15px;
    border: 1px solid #ccc;
    margin: 15px 0;
    }
    .hero-slide-inner {
        padding: 15px;
    }
    input#link_wrap_yes ~ .editable-container {
        -webkit-animation-name: example2; /* Chrome, Safari, Opera */
        -webkit-animation-duration: 1s; /* Chrome, Safari, Opera */
        animation-name: example2;
        animation-duration: 1s;

    }
    input#link_wrap_yes:checked ~ .editable-container {
        opacity: 0;
        overflow: hidden;
        -webkit-animation-name: example; /* Chrome, Safari, Opera */
        -webkit-animation-duration: .5s; /* Chrome, Safari, Opera */
        animation-name: example;
        animation-duration: .5s;
        height: 0;
        }

    /* Chrome, Safari, Opera */
    @-webkit-keyframes example {
        0% {
            opacity: 1;
            height: 100%;
        }
        99% {
            opacity: 0;
            height: 100%;
        }
        100% {
            height: 0;
        }
    }

    /* Standard syntax */
    @keyframes example {
        0% {
            opacity: 1;
            height: 100%;
        }
        99% {
            opacity: 0;
            height: 100%;
        }
        100% {
            height: 0;
        }
    }

        /* Chrome, Safari, Opera */
    @-webkit-keyframes example2 {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Standard syntax */
    @keyframes example2 {
        from {opacity: 0;}
        to {opacity: 1;}
    }
</style>
<div id="hero-options">
    <label>Change slide with timer?</label><br />
    <label class="radio-inline">
        <input type="radio" name="Hero_Options[timer]" value="yes" <?php if(isset($item->get('Hero_Options')['timer']) && $item->get('Hero_Options')['timer'] == "yes" ){echo "checked='checked'";} ?>> Yes
    </label>
    <label class="radio-inline">
       <input type="radio" name="Hero_Options[timer]" value="no" <?php if(isset($item->get('Hero_Options')['timer']) && $item->get('Hero_Options')['timer'] == "no" ){echo "checked='checked'";} ?>> No
    </label>

    <label>Length of timer (in seconds)</label><br />
    <input type="text" class="form-control"  name="Hero_Options[length]" value="<?php if(isset($item->get('Hero_Options')['length'])) { echo $item->get('Hero_Options')['length']; }?>">
</div>
<div id="hero-images">
    <div class="clearfix"></div>
    <?php $i=1; foreach ((array) $item->get('Hero_Slides') as $key=>$Slide) { ?>
    <div class="hero-slide">
        <span class="removeSlide" style="color:#a20808; text-align:right; float:right;"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
        REMOVE SLIDE</span>
        <h4>
            <strong>Slide #<?php echo $i; ?></strong>
        </h4>
        <div class="hero-slide-inner">

            <label>Desktop Image</label><br />
            <div class="portlet-content">
                <div class="input-group">
                    <div class="input-group-addon">Cloudinary ID</div>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][desktop_image]" id="HERO" value="<?php if(isset($Slide['desktop_image'])){echo $Slide['desktop_image'];} ?>">
                    <div class="input-group-addon"> PUBLIC ID</div>
                </div>
            </div>

            <label>Mobile Image</label><br />
            <div class="portlet-content">
                <div class="input-group">
                    <div class="input-group-addon">Cloudinary ID</div>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][mobile_image]" id="HERO" value="<?php if(isset($Slide['mobile_image'])){echo $Slide['mobile_image'];} ?>">
                    <div class="input-group-addon"> PUBLIC ID</div>
                </div>
            </div>
            <label>Link</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][link]" value="<?php if(isset($Slide['link'])){echo $Slide['link'];} ?>">

            <label>Text</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][text]" value="<?php if(isset($Slide['text'])){echo $Slide['text'];} ?>">
            
            <label>Alt Text</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][alt]" value="<?php if(isset($Slide['alt'])){echo $Slide['alt'];} ?>">

            <label>Font Color (6 digit hex only. Example: #ffffff)</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][color]" value="<?php if(isset($Slide['color'])){echo $Slide['color'];} ?>">

            <label>Google Analytics Internal Promo ID</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][promo]" value="<?php if(isset($Slide['promo'])){echo $Slide['promo'];} ?>">
        </div>


    </div>

    <?php $i++; } ?>
    <div class="col-md-12" data-object-type="slide-info">
        <?php foreach ((array) $flash->old('Slider_images') as $key=>$Slideimage) { ?>
        <fieldset class="template well clearfix">
            <a class="remove-image btn btn-xs btn-danger pull-right" onclick="RemoveRelatedImage(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>
            <label>Images</label>
            <div class="form-group clearfix">
                <div class="col-md-12">
                    <?php echo \Assets\Admin\Controllers\Assets::instance()->fetchElementImage('Slideimage_' . $key, $flash->old('Slider_images.'.$key.'.Slideimage'), array('field'=>'Slider_images['.$key.'][Slideimage]') ); ?>
                </div>
            </div>
        </fieldset>
    <?php } ?>


        <template type="text/template" id="add-slide-template">
        <fieldset class="template well clearfix">
            <a class="remove-image btn btn-xs btn-danger pull-right" onclick="RemoveRelatedImage(this);" href="javascript:void(0);">
                <i class="fa fa-times"></i>
            </a>
            <label>New Slide</label>
            <div class="form-group clearfix">
                <label>Desktop Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide{id}][desktop_image]" id="HERO" value="">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>

                <label>Mobile Image</label><br />
                <div class="portlet-content">
                    <div class="input-group">
                        <div class="input-group-addon">Cloudinary ID</div>
                        <input type="text" class="form-control" name="Hero_Slides[slide{id}][mobile_image]" id="HERO" value="">
                        <div class="input-group-addon"> PUBLIC ID</div>
                    </div>
                </div>
                <label>Link</label><br />
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][link]" value="">

                <label>Text</label><br />
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][text]" value="">
                
                <label>Alt Text</label><br />
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][alt]" value="">

                <label>Font Color (6 digit hex only. Example: #ffffff)</label><br />
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][color]" value="">

                <label>Google Analytics Internal Promo ID</label><br />
                <input type="text" class="form-control" name="Hero_Slides[slide{id}][promo]" value="">

            </div>

        </fieldset>
    </template>
        <div class="form-group">
        <a class="btn btn-warning" id="add-slide-image">Add New Slide</a>
    </div>





    <div id="new-slider-images" class="form-group"></div>
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





        <!-- /.portlet -->
    </div>



</div>

<input name="display[output_type]" value="raw" type="hidden">
