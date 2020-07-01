<div class="add-to-box <?php if($item->{'product_type'} == 'dynamic_group') : ?>dynamic_kit_master_lockup<?php endif; ?>">
<form action="/shop/cart/add" method="post" class="addToCartForm" autocomplete="off" <?php if(\Dsc\ArrayHelper::get($item->policies, 'requires_assembly')) : ?>data-assembled="1"<?php endif; ?>>
    <?php if($item->{'product_type'} == 'dynamic_group') : ?>
        <div class="row">
            <div class="col-xs-12">
                <?php
                if (\Audit::instance()->isMobile()) {
                    echo $this->renderView ('Shop/Site/Views::product/blocks/mobile/dynamic_group.php', ['cache'=> 'dynamic_options_mobile.'.$item->id]);
                } else {
                    echo $this->renderView ('Shop/Site/Views::product/blocks/desktop/dynamic_group.php', ['cache'=> 'dynamic_options.'.$item->id]);
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
    <?php if (!empty($item->attributes) || $item->product_type == 'matrix') : ?>
    <input type="hidden" name="model_number" value="" class="variant_id">
    <div class="product-options" id="product-options-wrapper">
        <a href="javascript:history.go(0)">Reset Configuration</a>
        <dl class="last">
        <?php foreach(array_values($item->attributes) as $key => $attribute):
            $options = '';
        ?>
            <dt><label class="required"><em>*</em><?php echo $attribute['title']; ?><span class="amconf-label" data-id='<?php echo $attribute['id']; ?>'></span></label></dt>
            <dd data-id='<?php echo $attribute['id']; ?>'>
            <div class="input-box">
                <div class="amconf-images-container" id="amconf-images-92">
                    <?php
                        if ($key === 0):
                            foreach ($item->getPossibleAttributeOptionsBySelection($attribute['id']) as $option_key => $option): ?>
                                <?php if(!empty($option['swatch'])) : ?>
                                    <div class="amconf-image-container" id="" style="float: left; width: 31px; cursor: pointer;">
                                        <img 
                                            id="amconf-image-<?php echo $option['id']; ?>" 
                                            data="<?php echo $option_key; ?>"
                                            src="<?php echo \cloudinary_url($option['swatch'], array(
                                                "height"=> 31,
                                                "width"=> 31,
                                                "crop"=>"limit",
                                                "sign_url"=>true,
                                                'secure' => true,
                                                'type' => strpos($option['swatch'], 'product_images') === 0 ? 'private' : 'upload'
                                            )); ?>" 
                                            class="amconf-image amconf-image-<?php echo $option_key; ?>" 
                                            alt="<?php echo $option['value']; ?>" 
                                            title="<?php echo $option['value']; ?>" 
                                            style="margin-bottom: 7px;"
                                            data-option="<?php echo $option['id']; ?>"
                                        />
                                    </div>
                                <?php endif; ?>
                    <?php
                                $options .= '<option data="' . $option_key . '"value="' . $option['id'] . '" ' . (!empty($option['variant']['model']) ? 'data-model="' . $option['variant']['model'] . '"' : '') . '>' . $option['value'] . '</option>';
                            endforeach;
                        endif;
                    ?>
                </div>
                <select 
                    name="<?php echo $attribute['id']; ?>" 
                    id="<?php echo $attribute['id']; ?>" 
                    data-name="<?php echo $attribute['title']; ?>" 
                    data="<?php echo $key; ?>"
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
<?php

        if (\Dsc\ArrayHelper::get($item->policies, 'ships_email')) {
            echo '<input type="email" name="email" class="input-text form-control error" placeholder="Recipient\'s Email Address" required><br />';
        }
        
        if (\Dsc\ArrayHelper::get($item->policies, 'ships_email')) {
            echo '<input type="recipientName" name="recipientName" class="input-text form-control error" placeholder="Recipient\'s Name" required><br />';
        }
        
        if (\Dsc\ArrayHelper::get($item->policies, 'ships_email')) {
            echo '<input type="message" name="message" class="input-text form-control error" placeholder="Message" required><br />';
        }
?>
    <script>

        function matrixLogic($select) {
            var value_selected = this.value;
            var select_id = $select.attr('id');
            var next_number = Number($select.attr('data')) + 1;

            $select.closest('dd').nextAll().find('.super-attribute-select').html('<option selected disabled>Choose an Option...</option>');

            var options_selected = [];
            
            $('.super-attribute-select option:selected:enabled').each(function(key, option) {
                options_selected.push($(option).val());
            });

            if($('.product-options select[data="' + next_number + '"]').length) {
                var next_item_id = $('.product-options select[data="' + next_number + '"]').attr('id');
                var next_item = $('#'+ next_item_id);

                $.post( "/shop/matrix/<?php echo $item->tracking['model_number']; ?>/options", { 
                attribute_id: next_item_id,
                option_ids: options_selected
                }, function(data) {
                if(!data.error) {
                        next_item.prop('disabled', null);
                        next_item.prev('.amconf-images-container').html('');
                        //next_item.html('<option selected disabled>Choose an Option...</option>');

                        $.each(data.result, function(key, option) {
                            variant = 'variant' in option ? ' data-model="' + option.variant.model + '"' : ''; 
                            next_item.append('<option data="' + key + '"value="' + option.id + '"' + variant + '>' + option.value + '</option>');

                            if('swatch' in option) {
                                next_item.prev('.amconf-images-container').append('<div class="amconf-image-container" id="" style="float: left; width: 31px; cursor: pointer;">' + cl.imageTag(option.swatch, { secure: true, sign_url: true, type: "upload", transformation: '<?php echo \Base::instance()->get('cloudinary.swatch'); ?>', class: "amconf-image amconf-image-" + key, alt: option.value, title: option.value, 'data-option': option.id, data: key, id: 'amconf-image-' + option.id, style: "margin-bottom: 7px;" }).toHtml() + '</div>');
                            }
                        });
                    
                }
            });
                
            } else {
                //cart is ready to go 
                $('.product-image').each(function() {
                    $(this).css('height', $(this).height());
                });

                var model = $('.super-attribute-select option:selected:enabled').last().attr('data-model');
                $('.variant_id').val(model);

                $.get( "/shop/product/" + model + "/info", function(data) {
                    if(!data.error) {
                        if('image' in data.result && data.result.image) {
                            //TODO: once image modal is fixed, auto switch to selected variant

                            $('.product-image > a, #data-image-modal-main').html(cl.imageTag(data.result.image, {secure: true, sign_url: true, type: "private", transformation: '<?php echo \Base::instance()->get('cloudinary.product'); ?>', alt: '', title: '', class: "additional_img"}).toHtml());

                            
                        }

                        if('price' in data.result) {
                            $('.price_actual ').html(currency_format.format(data.result.price));
                        }

                        if ('stock' in data.result) {
                            $('.amstockstatus').html(data.result.stock);
                        } else {
                            $('.amstockstatus').html('');
                        }
                    }
                });
            }
        }
    
    $('.super-attribute-select').change(function() {
        matrixLogic($(this));
    });

    $(document).on('click', '.amconf-image-container', function() {
        $select = $(this).closest('.input-box').find('select');
        $(this).closest('.input-box').find('select').val($(this).find('img').attr('data-option'));
        
        matrixLogic($select);
    });
    </script>
    <div class="add-to-cart">
        <div class="qty-container">
            <div class="f-right">
            <a class="qty-math qty-inc icon-white" href="#"></a>
            <a class="qty-math qty-dec icon-white" href="#"></a>
            </div>
            <input type="text" name="quantity" id="qty" maxlength="12" value="1" title="Qty" class="input-text qty">
            <label for="qty">Quantity</label>
        </div>
        <button type="submit" title="Add to Cart" class="button btn-cart icon-black addToCartButton" product_name="<?php echo sprintf("%s%s", $item->title, !empty($item->title_suffix) ? " - $item->title_suffix" : ""); ?>" onclick=""><span><span>Add to Cart</span></span></button>
    </div>
</form>
</div>

<script type="text/javascript">
    $('.addToCartForm').submit(function(e) {
        // stop submitting form
        e.preventDefault();


        var $form = $(this);

        // add data
        $('.matrix-option').remove();
        $('.super-attribute-select').each(function(i) {
            var attribute = $(this).data('name');
            var value = $(this).find('option:selected').text();

            $("<input />")
                .addClass('matrix-option')
                .attr('type', 'hidden')
                .attr('name', 'options[' + i + ']')
                .attr('value', JSON.stringify({
                    attribute: attribute,
                    value: value
                }))
                .appendTo($form);
        });

        // continue submitting form
        return true;
    });
</script>