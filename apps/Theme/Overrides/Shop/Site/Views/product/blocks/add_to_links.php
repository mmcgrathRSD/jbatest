<div class="add-to-box">
<form action="/shop/cart/add" method="post" class="addToCartForm" autocomplete="off">
<?php if (!empty($item->attributes) || $item->product_type == 'matrix') : ?>
    <input type="hidden" name="model_number" value="" class="variant_id">
    <div class="product-options" id="product-options-wrapper">
        <a href="#" onclick="javascript: spConfig.clearConfig(); return false;">Reset Configuration</a>
        <dl class="last">
        <?php 
        foreach($item->attributes as $key => $attribute) : 
        $options = '';
        ?>
            <dt><label class="required"><em>*</em><?php echo $attribute['title']; ?><span class="amconf-label" data-id='<?php echo $attribute['id']; ?>'></span></label></dt>
            <dd data-id='<?php echo $attribute['id']; ?>'>
            <div class="input-box">
                <div class="amconf-images-container" id="amconf-images-92">
                    <?php if($key == 0) : ?>
                        <?php foreach((array) $attribute['options'] as $option_key => $option) : ?>
                        <?php if(!empty($option['swatch'])) : ?>
                            <div class="amconf-image-container" id="" style="float: left; width: 31px; cursor: pointer;">
                                <img 
                                    id="amconf-image-<?php echo $option['id']; ?>" 
                                    data-number="<?php echo $option_key; ?>"
                                    src="<?php echo \cloudinary_url($option['swatch'], array("height"=> 31, "width"=> 31, "crop"=>"limit", "sign_url"=>true)); ?>" 
                                    class="amconf-image amconf-image-<?php echo $option_key; ?>" 
                                    alt="<?php echo $option['value']; ?>" 
                                    title="<?php echo $option['value']; ?>" 
                                    style="margin-bottom: 7px;"
                                    data-option="<?php echo $option['id']; ?>"
                                />
                            </div>
                        <?php endif; ?>
                        <?php $options .= '<option data-number="' . $option_key . '"value="' . $option['id'] . '">' . $option['value'] . '</option>'; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <select 
                    name="<?php echo $attribute['id']; ?>" 
                    id="<?php echo $attribute['id']; ?>" 
                    data-name="<?php echo $attribute['id']; ?>" 
                    data-number="<?php echo $key; ?>"
                    class="required-entry super-attribute-select super-attribute-select-order-<?php echo $key; ?>" 
                    <?php echo ($key > 0) ? 'disabled' : ''; ?>
                >
                    <option selected disabled>Choose an Option...</option>
                    <?php echo $options; ?>
                </select>
            </div>
            </dd>
        <?php endforeach; ?>
        </dl>
    </div>
<?php else : ?>
    <input type="hidden" name="model_number" value="<?php echo $item->tracking['model_number']; ?>" class="variant_id" />
<?php endif; ?>
    <script>
    $('.super-attribute-select').change(function() {
        console.log('wtf');;
        

        var value_selected = this.value;
        var select_id = $(this).attr('id');
        var next_number = Number($(this).attr('data-number')) + 1;

        $(this).closest('dd').nextAll().find('.super-attribute-select').html('<option selected disabled>Choose an Option...</option>');

        var options_selected = [];
        
        $('.super-attribute-select option:selected:enabled').each(function() {
            options_selected.push($(this).val());
        });

        // $('.product-options dd[data-id="' + select_id + '"] .amconf-image').removeClass('amconf-image-selected');
        // $('.product-options dd[data-id="' + select_id + '"] .amconf-image#' + select_id).addClass('amconf-image-selected');
        
        if($('.product-options select[data-number="' + next_number + '"]').length) {
            var next_item_id = $('.product-options select[data-number="' + next_number + '"]').attr('id');
            var next_item = $('#'+ next_item_id);

            $.post( "/shop/matrix/<?php echo $item->tracking['model_number']; ?>/options", { 
            attribute_id: next_item_id,
            option_ids: options_selected
            }, function(data) {
            
            next_item.prop('disabled', null);
            next_item.prev('.amconf-images-container').html('');
            //next_item.html('<option selected disabled>Choose an Option...</option>');

            $.each(data.result, function(key, option) {
                variant = 'variant' in option ? ' data-model="' + option.variant.model + '"' : ''; 
                next_item.append('<option data-number="' + key + '"value="' + option.id + '"' + variant + '>' + option.value + '</option>');

                if('swatch' in option) {
                    next_item.prev('.amconf-images-container').append(cl.imageTag(option.swatch, { secure: true, sign_url: true, type: "upload", transformation: '<?php echo \Base::instance()->get('cloudinary.swatch'); ?>', class: "amconf-image amconf-image-' + key + '", alt: option.value, title: option.value, id: 'amconf-image-"' + option.id + '"', style: "margin-bottom: 7px;" }).toHtml());
                }
            });
            });
        } else {
            //cart is ready to go 
            var model = $('.super-attribute-select option:selected:enabled').last().attr('data-model')
            $('.variant_id').val(model);

            $.get( "/shop/product/" + model + "/info", function(data) {
            console.log(data)
            if('image' in data.result) {
                //TODO: once image modal is fixed, auto switch to selected variant
            }

            if('price' in data.result) {
                $('.price_actual ').html(currency_format.format(data.result.price));
            }
            });

        }
        
        
    });

    $('.amconf-image-container').click(function() {
        console.log($(this).closest('.input-box').find('select'));
        $(this).closest('.input-box').find('select').val($(this).find('img').attr('data-option')).trigger('change');
        $(this).closest('.input-box').find('select').trigger('change');
    });
    </script>
    <div class="add-to-cart">
        <div class="qty-container">
            <div class="f-right">
            <a class="qty-math qty-inc icon-white" href="#"></a>
            <a class="qty-math qty-dec icon-white" href="#"></a>
            </div>
            <input type="text" name="quantity" id="qty" maxlength="12" value="0" title="Qty" class="input-text qty">
            <label for="qty">Quantity</label>
        </div>
        <button type="submit" title="Add to Cart" class="button btn-cart icon-black addToCartButton" product_name="<?php echo sprintf("%s%s", $item->title, !empty($item->title_suffix) ? " - $item->title_suffix" : ""); ?>" onclick=""><span><span>Add to Cart</span></span></button>
    </div>
</form>
</div>
<ul class="add-to-links">
<li><a class="link-wishlist  icon-black" href="https://www.subispeed.com/wishlist/index/add/product/13193/form_key/PTT3lpSgzitk4OCG/" onclick=""><span class="link_i"></span>Add to Wishlist</a></li>
</ul>