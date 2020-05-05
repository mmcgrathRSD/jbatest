<?php if ($cart->shippingRequired()): ?>
    <div class="grid_6">
        <div class="block block_shipping">
            <div class="shipping">
            <h2>Estimate Shipping</h2>
            <div class="shipping-form">
                <form method="post" id="estimateShippingForm">
                    <p>Enter your destination to get a shipping estimate.</p>
                    <ul class="form-list">
                        <li>
                        <label for="country" class="required"><em>*</em>Country</label>
                        <div class="input-box">
                            <select name="country_id" id="estimateShippingCountry" class="validate-select" title="Country">
                                <option disabled <?php if (empty($cart->{'checkout.shipping_address.country'})) { echo 'selected'; } ?>> <strong>Select Country</strong> </option>
                                <?php $countries = \Shop\Models\Countries::defaultList(); ?>

                                <?php if(count($countries) == 1) : ?>
                                <option data-regions_disabled="<?php echo (int) !empty($countries[0]->regions_disabled); ?>" data-requires_postal_code="<?php echo $countries[0]->requires_postal_code; ?>" value="<?php echo $countries[0]->isocode_2; ?>" selected><?php echo $countries[0]->name; ?></option>
                                <?php else : ?>
                                <?php foreach ($countries as $country) : ?>
                                <option data-regions_disabled="<?php echo (int) !empty($country->regions_disabled); ?>" data-requires_postal_code="<?php echo $country->requires_postal_code; ?>" value="<?php echo $country->isocode_2; ?>" <?php if ($cart->{'checkout.shipping_address.country'} == $country->isocode_2) { echo "selected"; } ?>><?php echo $country->name; ?></option>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        </li>
                        <li>
                            <div class="regionWrapper estimate_shipping_column" <?php if ($cart->{'checkout.shipping_address.country'} == 'US' || empty($cart->{'checkout.shipping_address.country'})) { echo 'style="display: none;"'; } ?>>
                                <label for="region_id">State/Province</label>
                                <div class="input-box">
                                    <select id="estimateShippingRegion" name="region" title="State/Province" style="" class=" region" required="required" autocomplete="region" required no-validation="true">
                                        <option disabled <?php if (empty($cart->{'checkout.shipping_address.region'})) { echo 'selected'; } ?>> <strong>Select Region</strong> </option>
                                        <?php foreach (\Shop\Models\Regions::byCountry(count($countries) == 1 ? $countries[0]->isocode_2 : $cart->shippingCountry()) as $region) { ?>
                                            <option value="<?php echo $region->code; ?>" <?php if ($cart->{'checkout.shipping_address.region'} == $region->code) { echo "selected"; } ?>><?php echo $region->name; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li>
                        <label for="postcode" class="required"><em>*</em>Zip/Postal Code</label>
                        <div class="input-box">
                            <input type="text" class="input-text form-control" id="estimateShippingZip" autocomplete="off" name="postal_code" value="<?php echo $cart->{'checkout.shipping_address.postal_code'}; ?>" placeholder="Zip Code" no-validation="true">
                        </div>
                        </li>
                    </ul>
                    <div class="buttons-set">
                        <button style="margin-bottom: 10px;" type="submit" title="Get a Quote" class="no_animation button form-control estimateSubmit secondary_button"><span><span>Get a Quote</span></span></button>
                        <div id="estimateResults"></div>
                    </div>
                </form>
            </div>
            </div>
        </div>
    </div>
<?php endif; ?>