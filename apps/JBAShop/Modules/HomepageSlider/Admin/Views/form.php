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
    <div class="row">
        <div class="col-sm-6">
            <label>Change slide with timer?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[timer]" value="yes" <?php if(isset($item->get('Hero_Options')['timer']) && $item->get('Hero_Options')['timer'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[timer]" value="no" <?php if(isset($item->get('Hero_Options')['timer']) && $item->get('Hero_Options')['timer'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>
        <div class="col-sm-6">
            <label>Transition timer (in milliseconds)</label><br />
            <input type="text" class="form-control"  name="Hero_Options[length]" value="<?php if(isset($item->get('Hero_Options')['length'])) { echo $item->get('Hero_Options')['length']; }?>">
        </div>

        <div class="col-sm-6">
            <label>Auto Height Speed</label>
            <input type="number" class="form-control" name="Hero_Options[auto_height_speed]" min="0" value="<?php if(isset($item->get('Hero_Options')['auto_height_speed'])) { echo $item->get('Hero_Options')['auto_height_speed']; }?>">
        </div>

        <div class="col-sm-6">
            <label>Speed (in milliseconds)</label><br />
            <input type="text" class="form-control" name="Hero_Options[speed]" value="<?php if(isset($item->get('Hero_Options')['speed'])) { echo $item->get('Hero_Options')['speed']; }?>">
        </div>

        <div class="col-sm-6">
            <label>Pause On Hover?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[pause_on_hover]" value="yes" <?php if(isset($item->get('Hero_Options')['pause_on_hover']) && $item->get('Hero_Options')['pause_on_hover'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[pause_on_hover]" value="no" <?php if(isset($item->get('Hero_Options')['pause_on_hover']) && $item->get('Hero_Options')['pause_on_hover'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>
        <div class="col-sm-6">
            <label>Allow Wrap?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[allow_wrap]" value="yes" <?php if(isset($item->get('Hero_Options')['allow_wrap']) && $item->get('Hero_Options')['allow_wrap'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[allow_wrap]" value="no" <?php if(isset($item->get('Hero_Options')['allow_wrap']) && $item->get('Hero_Options')['allow_wrap'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>

        <div class="col-sm-6">
            <label>Log?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[log]" value="yes" <?php if(isset($item->get('Hero_Options')['log']) && $item->get('Hero_Options')['log'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[log]" value="no" <?php if(isset($item->get('Hero_Options')['log']) && $item->get('Hero_Options')['log'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>

        <div class="col-sm-6">
            <label>Sync?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[sync]" value="yes" <?php if(isset($item->get('Hero_Options')['sync']) && $item->get('Hero_Options')['sync'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[sync]" value="no" <?php if(isset($item->get('Hero_Options')['sync']) && $item->get('Hero_Options')['sync'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>

        <div class="col-sm-6">
            <label>Swipe?</label><br />
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[swipe]" value="yes" <?php if(isset($item->get('Hero_Options')['swipe']) && $item->get('Hero_Options')['swipe'] == "yes" ){echo "checked='checked'";} ?>> Yes
            </label>
            <label class="radio-inline">
                <input type="radio" name="Hero_Options[swipe]" value="no" <?php if(isset($item->get('Hero_Options')['swipe']) && $item->get('Hero_Options')['swipe'] == "no" ){echo "checked='checked'";} ?>> No
            </label>
        </div>
    </div>
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
            <label>Desktop Slide Image</label><br />
            <div class="portlet-content">
                <div class="input-group">
                    <div class="input-group-addon">Cloudinary ID</div>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][desktop_image][src]" id="HERO" value="<?php if(isset($Slide['desktop_image']['src'])){echo $Slide['desktop_image']['src'];} ?>">
                    <div class="input-group-addon"> PUBLIC ID</div>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="number" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][desktop_image][height]" value="<?php if(isset($Slide['desktop_image']['height'])){echo $Slide['desktop_image']['height'];} ?>">
                </div>
            </div>

            <label>Mobile Slide Image</label><br />
            <div class="portlet-content">
                <div class="input-group">
                    <div class="input-group-addon">Cloudinary ID</div>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][mobile_image][src]" id="HERO" value="<?php if(isset($Slide['mobile_image']['src'])){echo $Slide['mobile_image']['src'];} ?>">
                    <div class="input-group-addon"> PUBLIC ID</div>
                </div>
                <div class="input-group">
                    <label>Height</label>
                    <input type="number" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][mobile_image][height]" value="<?php if(isset($Slide['mobile_image']['height'])){echo $Slide['mobile_image']['height'];} ?>">
                </div>
            </div>
            
            <label>Text Color</label><br     />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][text_color]" value="<?php echo isset($Slide['text_color']) ? $Slide['text_color'] : '#ffffff'; ?>">
            <p class="help-block">Use a 6 digit hex value e.g. #ffffff</p>

            <label>Title</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][title]" value="<?php if(isset($Slide['title'])){echo $Slide['title'];} ?>">
            
            <label>Sub Title</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][sub_title]" value="<?php if(isset($Slide['sub_title'])){echo $Slide['sub_title'];} ?>">
            
            <label>Link</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][link][href]" value="<?php if(isset($Slide['link']['href'])){echo $Slide['link']['href'];} ?>">
            
            <label>Link Text</label><br />
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][link][text]" value="<?php if(isset($Slide['link']['text'])){echo $Slide['link']['text'];} ?>">

            <label>Slide Banner Link</label>
            <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][slide_banner][href]" value="<?php if(isset($Slide['slide_banner']['href'])){echo $Slide['slide_banner']['href'];} ?>">
            
            <label>Slide Banner Image</label>
            <div class="portlet-content">
                <div class="input-group">
                    <div class="input-group-addon">Cloudinary ID</div>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][slide_banner][src]" value="<?php if(isset($Slide['slide_banner']['src'])){echo $Slide['slide_banner']['src'];} ?>">
                    <div class="input-group-addon"> PUBLIC ID</div>
                </div>
                <div class="input-group">
                    <label>Alt Tag</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide<?php echo $i; ?>][slide_banner][alt]" value="<?php if(isset($Slide['slide_banner']['src'])){echo $Slide['slide_banner']['alt'];} ?>">
                </div>
            </div>
           
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
                    <label>Desktop Slide Image</label><br />
                    <div class="portlet-content">
                        <div class="input-group">
                            <div class="input-group-addon">Cloudinary ID</div>
                            <input type="text" class="form-control" name="Hero_Slides[slide{id}][desktop_image][src]" id="HERO" value="">
                            <div class="input-group-addon"> PUBLIC ID</div>
                        </div>
                        <div class="input-group">
                            <label>Height</label>
                            <input type="number" class="form-control" name="Hero_Slides[slide{id}][desktop_image][height]" id="HERO" value="">
                        </div>
                    </div>

                    <label>Mobile Slide Image</label><br />
                    <div class="portlet-content">
                        <div class="input-group">
                            <div class="input-group-addon">Cloudinary ID</div>
                            <input type="text" class="form-control" name="Hero_Slides[slide{id}][mobile_image][src]" id="HERO" value="">
                            <div class="input-group-addon"> PUBLIC ID</div>
                        </div>
                        <div class="input-group">
                            <label>Height</label>
                            <input type="number" class="form-control" name="Hero_Slides[slide{id}][mobile_image][height]" id="HERO" value="">
                        </div>
                    </div>
                    
                    <label>Title</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide{id}][title]" value="">
                    
                    <label>Sub Title</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide{id}][sub_title]" value="">

                    <label>Link href</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide{id}][link][href]" value="">

                    <label>Link Text</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide{id}][link][text]" value="">

                    <label>Slide Banner Link</label>
                    <input type="text" class="form-control" name="Hero_Slides[slide{id}][slide_banner][href]" value="">

                    
                    <label>Slide Banner Image</label><br />
                    <div class="portlet-content">
                        <div class="input-group">
                            <div class="input-group-addon">Cloudinary ID</div>
                            <input type="text" class="form-control" name="Hero_Slides[slide{id}][slide_banner][src]" value="">
                            <div class="input-group-addon"> PUBLIC ID</div>
                        </div>
                        <div class="input-group">
                            <label>Alt Tag</label>
                            <input type="text" class="form-control" name="Hero_Slides[slide{id}][slide_banner][alt]" value="">
                        </div>
                    </div>

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
