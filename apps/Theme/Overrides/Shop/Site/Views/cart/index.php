<div class="main row">
    <div class="cart">
    <div class="page-title title-buttons">
        <h1>Shopping Cart</h1>
        <ul class="checkout-types">
            <li>   <button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout icon-white btn-checkout" onclick="window.location='/shop/checkout';"><span><span>Proceed to Checkout</span></span></button></li>
        </ul>
    </div>
    <form action="https://www.subispeed.com/checkout/cart/updatePost/" method="post">
        <input name="form_key" type="hidden" value="fd6cOZl3Q1sf6OcQ">
        <fieldset>
            <table id="shopping-cart-table" class="data-table cart-table">
                <colgroup>
                <col width="1">
                <col>
                <col width="1">
                <col width="1">
                <col width="1">
                <col width="1">
                </colgroup>
                <thead>
                <tr class="first last">
                    <th rowspan="1">&nbsp;</th>
                    <th rowspan="1"><span class="nobr">Product Name</span></th>
                    <th class="a-center" colspan="1"><span class="nobr">Unit Price</span></th>
                    <th rowspan="1" class="a-center">Qty</th>
                    <th class="a-center" colspan="1">Subtotal</th>
                    <th rowspan="1" class="a-center">&nbsp;</th>
                </tr>
                </thead>
                <tfoot>
                <tr class="first last">
                    <td colspan="50" class="a-right last">
                        <button type="button" title="Continue Shopping" class="button inverted btn-continue " onclick="setLocation('https://www.subispeed.com/')"><span><span>Continue Shopping</span></span></button>
                        <button type="submit" name="update_cart_action" value="update_qty" title="Update Shopping Cart" class="button inverted btn-update"><span><span>Update Shopping Cart</span></span></button>
                        <button type="submit" name="update_cart_action" value="empty_cart" title="Clear Shopping Cart" class="button inverted btn-empty" id="empty_cart_button"><span><span>Clear Shopping Cart</span></span></button>
                        <!--[if lt IE 8]>
                        <input type="hidden" id="update_cart_action_container" />
                        <script type="text/javascript">
                            //<![CDATA[
                                Event.observe(window, 'load', function()
                                {
                                    // Internet Explorer (lt 8) does not support value attribute in button elements
                                    $emptyCartButton = $('empty_cart_button');
                                    $cartActionContainer = $('update_cart_action_container');
                                    if ($emptyCartButton && $cartActionContainer) {
                                        Event.observe($emptyCartButton, 'click', function()
                                        {
                                            $emptyCartButton.setAttribute('name', 'update_cart_action_temp');
                                            $cartActionContainer.setAttribute('name', 'update_cart_action');
                                            $cartActionContainer.setValue('empty_cart');
                                        });
                                    }
                            
                                });
                            //]]>
                        </script>
                        <![endif]-->
                    </td>
                </tr>
                </tfoot>
                <tbody>
                <tr class="first last odd">
                    <td>
                        <a href="https://www.subispeed.com/tein-mechanic-gloves" title="Tein Mechanic Gloves" class="product-image">		<img src="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/300x300/85e4522595efc69f496374d01ef2bf13/t/e/teitn023-003_-_tein_mechanic_gloves-1.jpg" data-srcx2="https://www.subispeed.com/media/catalog/product/cache/1/thumbnail/600x600/85e4522595efc69f496374d01ef2bf13/t/e/teitn023-003_-_tein_mechanic_gloves-1.jpg" width="300" height="300" alt="Tein Mechanic Gloves">
                        </a>    
                    </td>
                    <td>
                        <h2 class="product-name">
                            <a href="https://www.subispeed.com/tein-mechanic-gloves">Tein Mechanic Gloves</a>
                        </h2>
                        <dl class="item-options">
                            <dt>Size</dt>
                            <dd>Large                            </dd>
                        </dl>
                    </td>
                    <td class="a-center td-price">
                        <span class="td-title">Unit Price</span>
                        <span class="cart-price">
                        <span class="price">$21.85</span>                
                        </span>
                    </td>
                    <td class="a-center td-qty">
                        <div class="qty-container">
                            <div class="f-right">
                            <a class="qty-math qty-inc  icon-white" href="#"></a>
                            <a class="qty-math qty-dec  icon-white" href="#"></a>
                            </div>
                            <input name="cart[7733751][qty]" value="1" title="Qty" class="input-text qty" maxlength="12">
                            <label for="qty" class="td-title">Quantity</label>
                        </div>
                    </td>
                    <td class="a-center td-price">
                        <span class="td-title">Subtotal</span>
                        <span class="cart-price">
                        <span class="price">$21.85</span>                            
                        </span>
                    </td>
                    <td class="a-center last">
                        <span class="td-title v-top">Actions</span>
                        <span class="nobr">
                        <a href="https://www.subispeed.com/checkout/cart/delete/id/7733751/uenc/aHR0cHM6Ly93d3cuc3ViaXNwZWVkLmNvbS9jaGVja291dC9jYXJ0Lw,,/form_key/fd6cOZl3Q1sf6OcQ/" title="Remove item" class="btn-remove btn-remove2 icon-white">Remove item</a>
                        <a href="https://www.subispeed.com/checkout/cart/configure/id/7733751/" title="Edit item parameters" class="btn-edit btn-edit2 icon-white">Edit</a>
                        </span>
                    </td>
                </tr>
                </tbody>
            </table>
            <script type="text/javascript">decorateTable('shopping-cart-table')</script>
        </fieldset>
    </form>
    <div class="row cart-collaterals">
        <div class="grid_18 top-line"></div>
        <div class="grid_6">
            <div class="block block_shipping">
                <div class="shipping">
                <h2>Estimate Shipping and Tax</h2>
                <div class="shipping-form">
                    <form action="https://www.subispeed.com/checkout/cart/estimatePost/" method="post" id="shipping-zip-form">
                        <p>Enter your destination to get a shipping estimate.</p>
                        <ul class="form-list">
                            <li>
                            <label for="country" class="required"><em>*</em>Country</label>
                            <div class="input-box">
                                <select name="country_id" id="country" class="validate-select" title="Country">
                                    <option value=""> </option>
                                    <option value="AL">Albania</option>
                                    <option value="DZ">Algeria</option>
                                    <option value="AS">American Samoa</option>
                                    <option value="AD">Andorra</option>
                                    <option value="AO">Angola</option>
                                    <option value="AI">Anguilla</option>
                                    <option value="AQ">Antarctica</option>
                                    <option value="AG">Antigua and Barbuda</option>
                                    <option value="AR">Argentina</option>
                                    <option value="AM">Armenia</option>
                                    <option value="AW">Aruba</option>
                                    <option value="AU">Australia</option>
                                    <option value="AT">Austria</option>
                                    <option value="AZ">Azerbaijan</option>
                                    <option value="BS">Bahamas</option>
                                    <option value="BH">Bahrain</option>
                                    <option value="BD">Bangladesh</option>
                                    <option value="BB">Barbados</option>
                                    <option value="BY">Belarus</option>
                                    <option value="BE">Belgium</option>
                                    <option value="BZ">Belize</option>
                                    <option value="BJ">Benin</option>
                                    <option value="BM">Bermuda</option>
                                    <option value="BT">Bhutan</option>
                                    <option value="BO">Bolivia</option>
                                    <option value="BA">Bosnia and Herzegovina</option>
                                    <option value="BW">Botswana</option>
                                    <option value="BV">Bouvet Island</option>
                                    <option value="BR">Brazil</option>
                                    <option value="IO">British Indian Ocean Territory</option>
                                    <option value="VG">British Virgin Islands</option>
                                    <option value="BN">Brunei</option>
                                    <option value="BG">Bulgaria</option>
                                    <option value="BF">Burkina Faso</option>
                                    <option value="BI">Burundi</option>
                                    <option value="KH">Cambodia</option>
                                    <option value="CM">Cameroon</option>
                                    <option value="CA">Canada</option>
                                    <option value="CV">Cape Verde</option>
                                    <option value="KY">Cayman Islands</option>
                                    <option value="CF">Central African Republic</option>
                                    <option value="TD">Chad</option>
                                    <option value="CL">Chile</option>
                                    <option value="CN">China</option>
                                    <option value="CX">Christmas Island</option>
                                    <option value="CC">Cocos [Keeling] Islands</option>
                                    <option value="CO">Colombia</option>
                                    <option value="KM">Comoros</option>
                                    <option value="CG">Congo - Brazzaville</option>
                                    <option value="CD">Congo - Kinshasa</option>
                                    <option value="CK">Cook Islands</option>
                                    <option value="CR">Costa Rica</option>
                                    <option value="CI">Côte d’Ivoire</option>
                                    <option value="HR">Croatia</option>
                                    <option value="CY">Cyprus</option>
                                    <option value="CZ">Czech Republic</option>
                                    <option value="DK">Denmark</option>
                                    <option value="DJ">Djibouti</option>
                                    <option value="DM">Dominica</option>
                                    <option value="DO">Dominican Republic</option>
                                    <option value="EC">Ecuador</option>
                                    <option value="EG">Egypt</option>
                                    <option value="SV">El Salvador</option>
                                    <option value="GQ">Equatorial Guinea</option>
                                    <option value="ER">Eritrea</option>
                                    <option value="EE">Estonia</option>
                                    <option value="ET">Ethiopia</option>
                                    <option value="FK">Falkland Islands</option>
                                    <option value="FO">Faroe Islands</option>
                                    <option value="FJ">Fiji</option>
                                    <option value="FI">Finland</option>
                                    <option value="FR">France</option>
                                    <option value="GF">French Guiana</option>
                                    <option value="PF">French Polynesia</option>
                                    <option value="TF">French Southern Territories</option>
                                    <option value="GA">Gabon</option>
                                    <option value="GM">Gambia</option>
                                    <option value="GE">Georgia</option>
                                    <option value="DE">Germany</option>
                                    <option value="GH">Ghana</option>
                                    <option value="GI">Gibraltar</option>
                                    <option value="GR">Greece</option>
                                    <option value="GL">Greenland</option>
                                    <option value="GD">Grenada</option>
                                    <option value="GP">Guadeloupe</option>
                                    <option value="GU">Guam</option>
                                    <option value="GT">Guatemala</option>
                                    <option value="GG">Guernsey</option>
                                    <option value="GN">Guinea</option>
                                    <option value="GW">Guinea-Bissau</option>
                                    <option value="GY">Guyana</option>
                                    <option value="HT">Haiti</option>
                                    <option value="HM">Heard Island and McDonald Islands</option>
                                    <option value="HN">Honduras</option>
                                    <option value="HK">Hong Kong SAR China</option>
                                    <option value="HU">Hungary</option>
                                    <option value="IS">Iceland</option>
                                    <option value="IN">India</option>
                                    <option value="ID">Indonesia</option>
                                    <option value="IE">Ireland</option>
                                    <option value="IM">Isle of Man</option>
                                    <option value="IL">Israel</option>
                                    <option value="IT">Italy</option>
                                    <option value="JM">Jamaica</option>
                                    <option value="JP">Japan</option>
                                    <option value="JE">Jersey</option>
                                    <option value="JO">Jordan</option>
                                    <option value="KZ">Kazakhstan</option>
                                    <option value="KE">Kenya</option>
                                    <option value="KI">Kiribati</option>
                                    <option value="KW">Kuwait</option>
                                    <option value="LV">Latvia</option>
                                    <option value="LB">Lebanon</option>
                                    <option value="LS">Lesotho</option>
                                    <option value="LR">Liberia</option>
                                    <option value="LY">Libya</option>
                                    <option value="LI">Liechtenstein</option>
                                    <option value="LT">Lithuania</option>
                                    <option value="LU">Luxembourg</option>
                                    <option value="MK">Macedonia</option>
                                    <option value="MG">Madagascar</option>
                                    <option value="MW">Malawi</option>
                                    <option value="MY">Malaysia</option>
                                    <option value="MV">Maldives</option>
                                    <option value="ML">Mali</option>
                                    <option value="MT">Malta</option>
                                    <option value="MH">Marshall Islands</option>
                                    <option value="MQ">Martinique</option>
                                    <option value="MR">Mauritania</option>
                                    <option value="MU">Mauritius</option>
                                    <option value="YT">Mayotte</option>
                                    <option value="MX">Mexico</option>
                                    <option value="FM">Micronesia</option>
                                    <option value="MD">Moldova</option>
                                    <option value="MC">Monaco</option>
                                    <option value="MN">Mongolia</option>
                                    <option value="ME">Montenegro</option>
                                    <option value="MS">Montserrat</option>
                                    <option value="MA">Morocco</option>
                                    <option value="MZ">Mozambique</option>
                                    <option value="MM">Myanmar [Burma]</option>
                                    <option value="NA">Namibia</option>
                                    <option value="NR">Nauru</option>
                                    <option value="NP">Nepal</option>
                                    <option value="NL">Netherlands</option>
                                    <option value="AN">Netherlands Antilles</option>
                                    <option value="NC">New Caledonia</option>
                                    <option value="NZ">New Zealand</option>
                                    <option value="NI">Nicaragua</option>
                                    <option value="NE">Niger</option>
                                    <option value="NG">Nigeria</option>
                                    <option value="NU">Niue</option>
                                    <option value="NF">Norfolk Island</option>
                                    <option value="MP">Northern Mariana Islands</option>
                                    <option value="NO">Norway</option>
                                    <option value="OM">Oman</option>
                                    <option value="PW">Palau</option>
                                    <option value="PS">Palestinian Territories</option>
                                    <option value="PA">Panama</option>
                                    <option value="PG">Papua New Guinea</option>
                                    <option value="PY">Paraguay</option>
                                    <option value="PE">Peru</option>
                                    <option value="PH">Philippines</option>
                                    <option value="PN">Pitcairn Islands</option>
                                    <option value="PL">Poland</option>
                                    <option value="PT">Portugal</option>
                                    <option value="PR">Puerto Rico</option>
                                    <option value="QA">Qatar</option>
                                    <option value="RE">Réunion</option>
                                    <option value="RO">Romania</option>
                                    <option value="RU">Russia</option>
                                    <option value="RW">Rwanda</option>
                                    <option value="BL">Saint Barthélemy</option>
                                    <option value="SH">Saint Helena</option>
                                    <option value="KN">Saint Kitts and Nevis</option>
                                    <option value="LC">Saint Lucia</option>
                                    <option value="MF">Saint Martin</option>
                                    <option value="PM">Saint Pierre and Miquelon</option>
                                    <option value="VC">Saint Vincent and the Grenadines</option>
                                    <option value="WS">Samoa</option>
                                    <option value="SM">San Marino</option>
                                    <option value="ST">São Tomé and Príncipe</option>
                                    <option value="SA">Saudi Arabia</option>
                                    <option value="SN">Senegal</option>
                                    <option value="SC">Seychelles</option>
                                    <option value="SL">Sierra Leone</option>
                                    <option value="SG">Singapore</option>
                                    <option value="SK">Slovakia</option>
                                    <option value="SI">Slovenia</option>
                                    <option value="SB">Solomon Islands</option>
                                    <option value="SO">Somalia</option>
                                    <option value="ZA">South Africa</option>
                                    <option value="GS">South Georgia and the South Sandwich Islands</option>
                                    <option value="KR">South Korea</option>
                                    <option value="ES">Spain</option>
                                    <option value="LK">Sri Lanka</option>
                                    <option value="SD">Sudan</option>
                                    <option value="SR">Suriname</option>
                                    <option value="SJ">Svalbard and Jan Mayen</option>
                                    <option value="SZ">Swaziland</option>
                                    <option value="SE">Sweden</option>
                                    <option value="CH">Switzerland</option>
                                    <option value="SY">Syria</option>
                                    <option value="TW">Taiwan</option>
                                    <option value="TJ">Tajikistan</option>
                                    <option value="TZ">Tanzania</option>
                                    <option value="TH">Thailand</option>
                                    <option value="TL">Timor-Leste</option>
                                    <option value="TG">Togo</option>
                                    <option value="TK">Tokelau</option>
                                    <option value="TO">Tonga</option>
                                    <option value="TT">Trinidad and Tobago</option>
                                    <option value="TN">Tunisia</option>
                                    <option value="TR">Turkey</option>
                                    <option value="TM">Turkmenistan</option>
                                    <option value="TC">Turks and Caicos Islands</option>
                                    <option value="TV">Tuvalu</option>
                                    <option value="UG">Uganda</option>
                                    <option value="UA">Ukraine</option>
                                    <option value="AE">United Arab Emirates</option>
                                    <option value="GB">United Kingdom</option>
                                    <option value="US" selected="selected">United States</option>
                                    <option value="UY">Uruguay</option>
                                    <option value="UM">U.S. Minor Outlying Islands</option>
                                    <option value="VI">U.S. Virgin Islands</option>
                                    <option value="UZ">Uzbekistan</option>
                                    <option value="VU">Vanuatu</option>
                                    <option value="VA">Vatican City</option>
                                    <option value="VE">Venezuela</option>
                                    <option value="VN">Vietnam</option>
                                    <option value="WF">Wallis and Futuna</option>
                                    <option value="EH">Western Sahara</option>
                                    <option value="YE">Yemen</option>
                                    <option value="ZM">Zambia</option>
                                    <option value="ZW">Zimbabwe</option>
                                </select>
                            </div>
                            </li>
                            <li>
                            <label for="region_id">State/Province</label>
                            <div class="input-box">
                                <select id="region_id" name="region_id" title="State/Province" style="" defaultvalue="58" class="required-entry validate-select">
                                    <option value="">Please select region, state or province</option>
                                    <option value="1" title="Alabama">Alabama</option>
                                    <option value="2" title="Alaska">Alaska</option>
                                    <option value="3" title="American Samoa">American Samoa</option>
                                    <option value="4" title="Arizona">Arizona</option>
                                    <option value="5" title="Arkansas">Arkansas</option>
                                    <option value="6" title="Armed Forces Africa">Armed Forces Africa</option>
                                    <option value="7" title="Armed Forces Americas">Armed Forces Americas</option>
                                    <option value="8" title="Armed Forces Canada">Armed Forces Canada</option>
                                    <option value="9" title="Armed Forces Europe">Armed Forces Europe</option>
                                    <option value="10" title="Armed Forces Middle East">Armed Forces Middle East</option>
                                    <option value="11" title="Armed Forces Pacific">Armed Forces Pacific</option>
                                    <option value="12" title="California">California</option>
                                    <option value="13" title="Colorado">Colorado</option>
                                    <option value="14" title="Connecticut">Connecticut</option>
                                    <option value="15" title="Delaware">Delaware</option>
                                    <option value="16" title="District of Columbia">District of Columbia</option>
                                    <option value="17" title="Federated States Of Micronesia">Federated States Of Micronesia</option>
                                    <option value="18" title="Florida">Florida</option>
                                    <option value="19" title="Georgia">Georgia</option>
                                    <option value="20" title="Guam">Guam</option>
                                    <option value="21" title="Hawaii">Hawaii</option>
                                    <option value="22" title="Idaho">Idaho</option>
                                    <option value="23" title="Illinois">Illinois</option>
                                    <option value="24" title="Indiana">Indiana</option>
                                    <option value="25" title="Iowa">Iowa</option>
                                    <option value="26" title="Kansas">Kansas</option>
                                    <option value="27" title="Kentucky">Kentucky</option>
                                    <option value="28" title="Louisiana">Louisiana</option>
                                    <option value="29" title="Maine">Maine</option>
                                    <option value="30" title="Marshall Islands">Marshall Islands</option>
                                    <option value="31" title="Maryland">Maryland</option>
                                    <option value="32" title="Massachusetts">Massachusetts</option>
                                    <option value="33" title="Michigan">Michigan</option>
                                    <option value="34" title="Minnesota">Minnesota</option>
                                    <option value="35" title="Mississippi">Mississippi</option>
                                    <option value="36" title="Missouri">Missouri</option>
                                    <option value="37" title="Montana">Montana</option>
                                    <option value="38" title="Nebraska">Nebraska</option>
                                    <option value="39" title="Nevada">Nevada</option>
                                    <option value="40" title="New Hampshire">New Hampshire</option>
                                    <option value="41" title="New Jersey">New Jersey</option>
                                    <option value="42" title="New Mexico">New Mexico</option>
                                    <option value="43" title="New York">New York</option>
                                    <option value="44" title="North Carolina">North Carolina</option>
                                    <option value="45" title="North Dakota">North Dakota</option>
                                    <option value="46" title="Northern Mariana Islands">Northern Mariana Islands</option>
                                    <option value="47" title="Ohio">Ohio</option>
                                    <option value="48" title="Oklahoma">Oklahoma</option>
                                    <option value="49" title="Oregon">Oregon</option>
                                    <option value="50" title="Palau">Palau</option>
                                    <option value="51" title="Pennsylvania">Pennsylvania</option>
                                    <option value="52" title="Puerto Rico">Puerto Rico</option>
                                    <option value="53" title="Rhode Island">Rhode Island</option>
                                    <option value="54" title="South Carolina">South Carolina</option>
                                    <option value="55" title="South Dakota">South Dakota</option>
                                    <option value="56" title="Tennessee">Tennessee</option>
                                    <option value="57" title="Texas">Texas</option>
                                    <option value="58" title="Utah">Utah</option>
                                    <option value="59" title="Vermont">Vermont</option>
                                    <option value="60" title="Virgin Islands">Virgin Islands</option>
                                    <option value="61" title="Virginia">Virginia</option>
                                    <option value="62" title="Washington">Washington</option>
                                    <option value="63" title="West Virginia">West Virginia</option>
                                    <option value="64" title="Wisconsin">Wisconsin</option>
                                    <option value="65" title="Wyoming">Wyoming</option>
                                </select>
                                <script type="text/javascript">
                                    //<![CDATA[
                                        $('region_id').setAttribute('defaultValue',  "58");
                                    //]]>
                                </script>
                                <input type="text" id="region" name="region" value="Utah" title="State/Province" class="input-text required-entry" style="display:none;">
                            </div>
                            </li>
                            <li>
                            <label for="postcode" class="required"><em>*</em>Zip/Postal Code</label>
                            <div class="input-box">
                                <input class="input-text validate-postcode required-entry" type="text" id="postcode" name="estimate_postcode" value="84047">
                            </div>
                            </li>
                        </ul>
                        <div class="buttons-set">
                            <button type="button" title="Get a Quote" onclick="coShippingMethodForm.submit()" class="button"><span><span>Get a Quote</span></span></button>
                        </div>
                    </form>
                    <script type="text/javascript">
                        //<![CDATA[
                            new RegionUpdater('country', 'region', 'region_id', {"config":{"show_all_regions":true,"regions_required":["AT","CA","EE","FI","FR","DE","LV","LT","RO","ES","CH","US"]},"ES":{"130":{"code":"A Coru\u0441a","name":"A Coru\u00f1a"},"131":{"code":"Alava","name":"Alava"},"132":{"code":"Albacete","name":"Albacete"},"133":{"code":"Alicante","name":"Alicante"},"134":{"code":"Almeria","name":"Almeria"},"135":{"code":"Asturias","name":"Asturias"},"136":{"code":"Avila","name":"Avila"},"137":{"code":"Badajoz","name":"Badajoz"},"138":{"code":"Baleares","name":"Baleares"},"139":{"code":"Barcelona","name":"Barcelona"},"140":{"code":"Burgos","name":"Burgos"},"141":{"code":"Caceres","name":"Caceres"},"142":{"code":"Cadiz","name":"Cadiz"},"143":{"code":"Cantabria","name":"Cantabria"},"144":{"code":"Castellon","name":"Castellon"},"145":{"code":"Ceuta","name":"Ceuta"},"146":{"code":"Ciudad Real","name":"Ciudad Real"},"147":{"code":"Cordoba","name":"Cordoba"},"148":{"code":"Cuenca","name":"Cuenca"},"149":{"code":"Girona","name":"Girona"},"150":{"code":"Granada","name":"Granada"},"151":{"code":"Guadalajara","name":"Guadalajara"},"152":{"code":"Guipuzcoa","name":"Guipuzcoa"},"153":{"code":"Huelva","name":"Huelva"},"154":{"code":"Huesca","name":"Huesca"},"155":{"code":"Jaen","name":"Jaen"},"156":{"code":"La Rioja","name":"La Rioja"},"157":{"code":"Las Palmas","name":"Las Palmas"},"158":{"code":"Leon","name":"Leon"},"159":{"code":"Lleida","name":"Lleida"},"160":{"code":"Lugo","name":"Lugo"},"161":{"code":"Madrid","name":"Madrid"},"162":{"code":"Malaga","name":"Malaga"},"163":{"code":"Melilla","name":"Melilla"},"164":{"code":"Murcia","name":"Murcia"},"165":{"code":"Navarra","name":"Navarra"},"166":{"code":"Ourense","name":"Ourense"},"167":{"code":"Palencia","name":"Palencia"},"168":{"code":"Pontevedra","name":"Pontevedra"},"169":{"code":"Salamanca","name":"Salamanca"},"170":{"code":"Santa Cruz de Tenerife","name":"Santa Cruz de Tenerife"},"171":{"code":"Segovia","name":"Segovia"},"172":{"code":"Sevilla","name":"Sevilla"},"173":{"code":"Soria","name":"Soria"},"174":{"code":"Tarragona","name":"Tarragona"},"175":{"code":"Teruel","name":"Teruel"},"176":{"code":"Toledo","name":"Toledo"},"177":{"code":"Valencia","name":"Valencia"},"178":{"code":"Valladolid","name":"Valladolid"},"179":{"code":"Vizcaya","name":"Vizcaya"},"180":{"code":"Zamora","name":"Zamora"},"181":{"code":"Zaragoza","name":"Zaragoza"}},"CH":{"104":{"code":"AG","name":"Aargau"},"106":{"code":"AR","name":"Appenzell Ausserrhoden"},"105":{"code":"AI","name":"Appenzell Innerrhoden"},"108":{"code":"BL","name":"Basel-Landschaft"},"109":{"code":"BS","name":"Basel-Stadt"},"107":{"code":"BE","name":"Bern"},"110":{"code":"FR","name":"Freiburg"},"111":{"code":"GE","name":"Genf"},"112":{"code":"GL","name":"Glarus"},"113":{"code":"GR","name":"Graub\u00fcnden"},"114":{"code":"JU","name":"Jura"},"115":{"code":"LU","name":"Luzern"},"116":{"code":"NE","name":"Neuenburg"},"117":{"code":"NW","name":"Nidwalden"},"118":{"code":"OW","name":"Obwalden"},"120":{"code":"SH","name":"Schaffhausen"},"122":{"code":"SZ","name":"Schwyz"},"121":{"code":"SO","name":"Solothurn"},"119":{"code":"SG","name":"St. Gallen"},"124":{"code":"TI","name":"Tessin"},"123":{"code":"TG","name":"Thurgau"},"125":{"code":"UR","name":"Uri"},"126":{"code":"VD","name":"Waadt"},"127":{"code":"VS","name":"Wallis"},"128":{"code":"ZG","name":"Zug"},"129":{"code":"ZH","name":"Z\u00fcrich"}},"LV":{"471":{"code":"\u0100da\u017eu novads","name":"\u0100da\u017eu novads"},"366":{"code":"Aglonas novads","name":"Aglonas novads"},"367":{"code":"LV-AI","name":"Aizkraukles novads"},"368":{"code":"Aizputes novads","name":"Aizputes novads"},"369":{"code":"Akn\u012bstes novads","name":"Akn\u012bstes novads"},"370":{"code":"Alojas novads","name":"Alojas novads"},"371":{"code":"Alsungas novads","name":"Alsungas novads"},"372":{"code":"LV-AL","name":"Al\u016bksnes novads"},"373":{"code":"Amatas novads","name":"Amatas novads"},"374":{"code":"Apes novads","name":"Apes novads"},"375":{"code":"Auces novads","name":"Auces novads"},"376":{"code":"Bab\u012btes novads","name":"Bab\u012btes novads"},"377":{"code":"Baldones novads","name":"Baldones novads"},"378":{"code":"Baltinavas novads","name":"Baltinavas novads"},"379":{"code":"LV-BL","name":"Balvu novads"},"380":{"code":"LV-BU","name":"Bauskas novads"},"381":{"code":"Bever\u012bnas novads","name":"Bever\u012bnas novads"},"382":{"code":"Broc\u0113nu novads","name":"Broc\u0113nu novads"},"383":{"code":"Burtnieku novads","name":"Burtnieku novads"},"384":{"code":"Carnikavas novads","name":"Carnikavas novads"},"387":{"code":"LV-CE","name":"C\u0113su novads"},"385":{"code":"Cesvaines novads","name":"Cesvaines novads"},"386":{"code":"Ciblas novads","name":"Ciblas novads"},"388":{"code":"Dagdas novads","name":"Dagdas novads"},"355":{"code":"LV-DGV","name":"Daugavpils"},"389":{"code":"LV-DA","name":"Daugavpils novads"},"390":{"code":"LV-DO","name":"Dobeles novads"},"391":{"code":"Dundagas novads","name":"Dundagas novads"},"392":{"code":"Durbes novads","name":"Durbes novads"},"393":{"code":"Engures novads","name":"Engures novads"},"472":{"code":"\u0112rg\u013cu novads","name":"\u0112rg\u013cu novads"},"394":{"code":"Garkalnes novads","name":"Garkalnes novads"},"395":{"code":"Grobi\u0146as novads","name":"Grobi\u0146as novads"},"396":{"code":"LV-GU","name":"Gulbenes novads"},"397":{"code":"Iecavas novads","name":"Iecavas novads"},"398":{"code":"Ik\u0161\u0137iles novads","name":"Ik\u0161\u0137iles novads"},"399":{"code":"Il\u016bkstes novads","name":"Il\u016bkstes novads"},"400":{"code":"In\u010dukalna novads","name":"In\u010dukalna novads"},"401":{"code":"Jaunjelgavas novads","name":"Jaunjelgavas novads"},"402":{"code":"Jaunpiebalgas novads","name":"Jaunpiebalgas novads"},"403":{"code":"Jaunpils novads","name":"Jaunpils novads"},"357":{"code":"J\u0113kabpils","name":"J\u0113kabpils"},"405":{"code":"LV-JK","name":"J\u0113kabpils novads"},"356":{"code":"LV-JEL","name":"Jelgava"},"404":{"code":"LV-JL","name":"Jelgavas novads"},"358":{"code":"LV-JUR","name":"J\u016brmala"},"406":{"code":"Kandavas novads","name":"Kandavas novads"},"412":{"code":"K\u0101rsavas novads","name":"K\u0101rsavas novads"},"473":{"code":"\u0136eguma novads","name":"\u0136eguma novads"},"474":{"code":"\u0136ekavas novads","name":"\u0136ekavas novads"},"407":{"code":"Kokneses novads","name":"Kokneses novads"},"410":{"code":"LV-KR","name":"Kr\u0101slavas novads"},"408":{"code":"Krimuldas novads","name":"Krimuldas novads"},"409":{"code":"Krustpils novads","name":"Krustpils novads"},"411":{"code":"LV-KU","name":"Kuld\u012bgas novads"},"413":{"code":"Lielv\u0101rdes novads","name":"Lielv\u0101rdes novads"},"359":{"code":"LV-LPX","name":"Liep\u0101ja"},"360":{"code":"LV-LE","name":"Liep\u0101jas novads"},"417":{"code":"L\u012bgatnes novads","name":"L\u012bgatnes novads"},"414":{"code":"LV-LM","name":"Limba\u017eu novads"},"418":{"code":"L\u012bv\u0101nu novads","name":"L\u012bv\u0101nu novads"},"415":{"code":"Lub\u0101nas novads","name":"Lub\u0101nas novads"},"416":{"code":"LV-LU","name":"Ludzas novads"},"419":{"code":"LV-MA","name":"Madonas novads"},"421":{"code":"M\u0101lpils novads","name":"M\u0101lpils novads"},"422":{"code":"M\u0101rupes novads","name":"M\u0101rupes novads"},"420":{"code":"Mazsalacas novads","name":"Mazsalacas novads"},"423":{"code":"Nauk\u0161\u0113nu novads","name":"Nauk\u0161\u0113nu novads"},"424":{"code":"Neretas novads","name":"Neretas novads"},"425":{"code":"N\u012bcas novads","name":"N\u012bcas novads"},"426":{"code":"LV-OG","name":"Ogres novads"},"427":{"code":"Olaines novads","name":"Olaines novads"},"428":{"code":"Ozolnieku novads","name":"Ozolnieku novads"},"432":{"code":"P\u0101rgaujas novads","name":"P\u0101rgaujas novads"},"433":{"code":"P\u0101vilostas novads","name":"P\u0101vilostas novads"},"434":{"code":"P\u013cavi\u0146u novads","name":"P\u013cavi\u0146u novads"},"429":{"code":"LV-PR","name":"Prei\u013cu novads"},"430":{"code":"Priekules novads","name":"Priekules novads"},"431":{"code":"Prieku\u013cu novads","name":"Prieku\u013cu novads"},"435":{"code":"Raunas novads","name":"Raunas novads"},"361":{"code":"LV-REZ","name":"R\u0113zekne"},"442":{"code":"LV-RE","name":"R\u0113zeknes novads"},"436":{"code":"Riebi\u0146u novads","name":"Riebi\u0146u novads"},"362":{"code":"LV-RIX","name":"R\u012bga"},"363":{"code":"LV-RI","name":"R\u012bgas novads"},"437":{"code":"Rojas novads","name":"Rojas novads"},"438":{"code":"Ropa\u017eu novads","name":"Ropa\u017eu novads"},"439":{"code":"Rucavas novads","name":"Rucavas novads"},"440":{"code":"Rug\u0101ju novads","name":"Rug\u0101ju novads"},"443":{"code":"R\u016bjienas novads","name":"R\u016bjienas novads"},"441":{"code":"Rund\u0101les novads","name":"Rund\u0101les novads"},"444":{"code":"Salacgr\u012bvas novads","name":"Salacgr\u012bvas novads"},"445":{"code":"Salas novads","name":"Salas novads"},"446":{"code":"Salaspils novads","name":"Salaspils novads"},"447":{"code":"LV-SA","name":"Saldus novads"},"448":{"code":"Saulkrastu novads","name":"Saulkrastu novads"},"455":{"code":"S\u0113jas novads","name":"S\u0113jas novads"},"449":{"code":"Siguldas novads","name":"Siguldas novads"},"451":{"code":"Skr\u012bveru novads","name":"Skr\u012bveru novads"},"450":{"code":"Skrundas novads","name":"Skrundas novads"},"452":{"code":"Smiltenes novads","name":"Smiltenes novads"},"453":{"code":"Stopi\u0146u novads","name":"Stopi\u0146u novads"},"454":{"code":"Stren\u010du novads","name":"Stren\u010du novads"},"456":{"code":"LV-TA","name":"Talsu novads"},"458":{"code":"T\u0113rvetes novads","name":"T\u0113rvetes novads"},"457":{"code":"LV-TU","name":"Tukuma novads"},"459":{"code":"Vai\u0146odes novads","name":"Vai\u0146odes novads"},"460":{"code":"LV-VK","name":"Valkas novads"},"364":{"code":"Valmiera","name":"Valmiera"},"461":{"code":"LV-VM","name":"Valmieras novads"},"462":{"code":"Varak\u013c\u0101nu novads","name":"Varak\u013c\u0101nu novads"},"469":{"code":"V\u0101rkavas novads","name":"V\u0101rkavas novads"},"463":{"code":"Vecpiebalgas novads","name":"Vecpiebalgas novads"},"464":{"code":"Vecumnieku novads","name":"Vecumnieku novads"},"365":{"code":"LV-VEN","name":"Ventspils"},"465":{"code":"LV-VE","name":"Ventspils novads"},"466":{"code":"Vies\u012btes novads","name":"Vies\u012btes novads"},"467":{"code":"Vi\u013cakas novads","name":"Vi\u013cakas novads"},"468":{"code":"Vi\u013c\u0101nu novads","name":"Vi\u013c\u0101nu novads"},"470":{"code":"Zilupes novads","name":"Zilupes novads"}},"FI":{"339":{"code":"Ahvenanmaa","name":"Ahvenanmaa"},"333":{"code":"Etel\u00e4-Karjala","name":"Etel\u00e4-Karjala"},"326":{"code":"Etel\u00e4-Pohjanmaa","name":"Etel\u00e4-Pohjanmaa"},"325":{"code":"Etel\u00e4-Savo","name":"Etel\u00e4-Savo"},"337":{"code":"It\u00e4-Uusimaa","name":"It\u00e4-Uusimaa"},"322":{"code":"Kainuu","name":"Kainuu"},"335":{"code":"Kanta-H\u00e4me","name":"Kanta-H\u00e4me"},"330":{"code":"Keski-Pohjanmaa","name":"Keski-Pohjanmaa"},"331":{"code":"Keski-Suomi","name":"Keski-Suomi"},"338":{"code":"Kymenlaakso","name":"Kymenlaakso"},"320":{"code":"Lappi","name":"Lappi"},"334":{"code":"P\u00e4ij\u00e4t-H\u00e4me","name":"P\u00e4ij\u00e4t-H\u00e4me"},"328":{"code":"Pirkanmaa","name":"Pirkanmaa"},"327":{"code":"Pohjanmaa","name":"Pohjanmaa"},"323":{"code":"Pohjois-Karjala","name":"Pohjois-Karjala"},"321":{"code":"Pohjois-Pohjanmaa","name":"Pohjois-Pohjanmaa"},"324":{"code":"Pohjois-Savo","name":"Pohjois-Savo"},"329":{"code":"Satakunta","name":"Satakunta"},"336":{"code":"Uusimaa","name":"Uusimaa"},"332":{"code":"Varsinais-Suomi","name":"Varsinais-Suomi"}},"FR":{"182":{"code":"1","name":"Ain"},"183":{"code":"2","name":"Aisne"},"184":{"code":"3","name":"Allier"},"185":{"code":"4","name":"Alpes-de-Haute-Provence"},"187":{"code":"6","name":"Alpes-Maritimes"},"188":{"code":"7","name":"Ard\u00e8che"},"189":{"code":"8","name":"Ardennes"},"190":{"code":"9","name":"Ari\u00e8ge"},"191":{"code":"10","name":"Aube"},"192":{"code":"11","name":"Aude"},"193":{"code":"12","name":"Aveyron"},"249":{"code":"67","name":"Bas-Rhin"},"194":{"code":"13","name":"Bouches-du-Rh\u00f4ne"},"195":{"code":"14","name":"Calvados"},"196":{"code":"15","name":"Cantal"},"197":{"code":"16","name":"Charente"},"198":{"code":"17","name":"Charente-Maritime"},"199":{"code":"18","name":"Cher"},"200":{"code":"19","name":"Corr\u00e8ze"},"201":{"code":"2A","name":"Corse-du-Sud"},"203":{"code":"21","name":"C\u00f4te-d'Or"},"204":{"code":"22","name":"C\u00f4tes-d'Armor"},"205":{"code":"23","name":"Creuse"},"261":{"code":"79","name":"Deux-S\u00e8vres"},"206":{"code":"24","name":"Dordogne"},"207":{"code":"25","name":"Doubs"},"208":{"code":"26","name":"Dr\u00f4me"},"273":{"code":"91","name":"Essonne"},"209":{"code":"27","name":"Eure"},"210":{"code":"28","name":"Eure-et-Loir"},"211":{"code":"29","name":"Finist\u00e8re"},"212":{"code":"30","name":"Gard"},"214":{"code":"32","name":"Gers"},"215":{"code":"33","name":"Gironde"},"250":{"code":"68","name":"Haut-Rhin"},"202":{"code":"2B","name":"Haute-Corse"},"213":{"code":"31","name":"Haute-Garonne"},"225":{"code":"43","name":"Haute-Loire"},"234":{"code":"52","name":"Haute-Marne"},"252":{"code":"70","name":"Haute-Sa\u00f4ne"},"256":{"code":"74","name":"Haute-Savoie"},"269":{"code":"87","name":"Haute-Vienne"},"186":{"code":"5","name":"Hautes-Alpes"},"247":{"code":"65","name":"Hautes-Pyr\u00e9n\u00e9es"},"274":{"code":"92","name":"Hauts-de-Seine"},"216":{"code":"34","name":"H\u00e9rault"},"217":{"code":"35","name":"Ille-et-Vilaine"},"218":{"code":"36","name":"Indre"},"219":{"code":"37","name":"Indre-et-Loire"},"220":{"code":"38","name":"Is\u00e8re"},"221":{"code":"39","name":"Jura"},"222":{"code":"40","name":"Landes"},"223":{"code":"41","name":"Loir-et-Cher"},"224":{"code":"42","name":"Loire"},"226":{"code":"44","name":"Loire-Atlantique"},"227":{"code":"45","name":"Loiret"},"228":{"code":"46","name":"Lot"},"229":{"code":"47","name":"Lot-et-Garonne"},"230":{"code":"48","name":"Loz\u00e8re"},"231":{"code":"49","name":"Maine-et-Loire"},"232":{"code":"50","name":"Manche"},"233":{"code":"51","name":"Marne"},"235":{"code":"53","name":"Mayenne"},"236":{"code":"54","name":"Meurthe-et-Moselle"},"237":{"code":"55","name":"Meuse"},"238":{"code":"56","name":"Morbihan"},"239":{"code":"57","name":"Moselle"},"240":{"code":"58","name":"Ni\u00e8vre"},"241":{"code":"59","name":"Nord"},"242":{"code":"60","name":"Oise"},"243":{"code":"61","name":"Orne"},"257":{"code":"75","name":"Paris"},"244":{"code":"62","name":"Pas-de-Calais"},"245":{"code":"63","name":"Puy-de-D\u00f4me"},"246":{"code":"64","name":"Pyr\u00e9n\u00e9es-Atlantiques"},"248":{"code":"66","name":"Pyr\u00e9n\u00e9es-Orientales"},"251":{"code":"69","name":"Rh\u00f4ne"},"253":{"code":"71","name":"Sa\u00f4ne-et-Loire"},"254":{"code":"72","name":"Sarthe"},"255":{"code":"73","name":"Savoie"},"259":{"code":"77","name":"Seine-et-Marne"},"258":{"code":"76","name":"Seine-Maritime"},"275":{"code":"93","name":"Seine-Saint-Denis"},"262":{"code":"80","name":"Somme"},"263":{"code":"81","name":"Tarn"},"264":{"code":"82","name":"Tarn-et-Garonne"},"272":{"code":"90","name":"Territoire-de-Belfort"},"277":{"code":"95","name":"Val-d'Oise"},"276":{"code":"94","name":"Val-de-Marne"},"265":{"code":"83","name":"Var"},"266":{"code":"84","name":"Vaucluse"},"267":{"code":"85","name":"Vend\u00e9e"},"268":{"code":"86","name":"Vienne"},"270":{"code":"88","name":"Vosges"},"271":{"code":"89","name":"Yonne"},"260":{"code":"78","name":"Yvelines"}},"US":{"1":{"code":"AL","name":"Alabama"},"2":{"code":"AK","name":"Alaska"},"3":{"code":"AS","name":"American Samoa"},"4":{"code":"AZ","name":"Arizona"},"5":{"code":"AR","name":"Arkansas"},"6":{"code":"AF","name":"Armed Forces Africa"},"7":{"code":"AA","name":"Armed Forces Americas"},"8":{"code":"AC","name":"Armed Forces Canada"},"9":{"code":"AE","name":"Armed Forces Europe"},"10":{"code":"AM","name":"Armed Forces Middle East"},"11":{"code":"AP","name":"Armed Forces Pacific"},"12":{"code":"CA","name":"California"},"13":{"code":"CO","name":"Colorado"},"14":{"code":"CT","name":"Connecticut"},"15":{"code":"DE","name":"Delaware"},"16":{"code":"DC","name":"District of Columbia"},"17":{"code":"FM","name":"Federated States Of Micronesia"},"18":{"code":"FL","name":"Florida"},"19":{"code":"GA","name":"Georgia"},"20":{"code":"GU","name":"Guam"},"21":{"code":"HI","name":"Hawaii"},"22":{"code":"ID","name":"Idaho"},"23":{"code":"IL","name":"Illinois"},"24":{"code":"IN","name":"Indiana"},"25":{"code":"IA","name":"Iowa"},"26":{"code":"KS","name":"Kansas"},"27":{"code":"KY","name":"Kentucky"},"28":{"code":"LA","name":"Louisiana"},"29":{"code":"ME","name":"Maine"},"30":{"code":"MH","name":"Marshall Islands"},"31":{"code":"MD","name":"Maryland"},"32":{"code":"MA","name":"Massachusetts"},"33":{"code":"MI","name":"Michigan"},"34":{"code":"MN","name":"Minnesota"},"35":{"code":"MS","name":"Mississippi"},"36":{"code":"MO","name":"Missouri"},"37":{"code":"MT","name":"Montana"},"38":{"code":"NE","name":"Nebraska"},"39":{"code":"NV","name":"Nevada"},"40":{"code":"NH","name":"New Hampshire"},"41":{"code":"NJ","name":"New Jersey"},"42":{"code":"NM","name":"New Mexico"},"43":{"code":"NY","name":"New York"},"44":{"code":"NC","name":"North Carolina"},"45":{"code":"ND","name":"North Dakota"},"46":{"code":"MP","name":"Northern Mariana Islands"},"47":{"code":"OH","name":"Ohio"},"48":{"code":"OK","name":"Oklahoma"},"49":{"code":"OR","name":"Oregon"},"50":{"code":"PW","name":"Palau"},"51":{"code":"PA","name":"Pennsylvania"},"52":{"code":"PR","name":"Puerto Rico"},"53":{"code":"RI","name":"Rhode Island"},"54":{"code":"SC","name":"South Carolina"},"55":{"code":"SD","name":"South Dakota"},"56":{"code":"TN","name":"Tennessee"},"57":{"code":"TX","name":"Texas"},"58":{"code":"UT","name":"Utah"},"59":{"code":"VT","name":"Vermont"},"60":{"code":"VI","name":"Virgin Islands"},"61":{"code":"VA","name":"Virginia"},"62":{"code":"WA","name":"Washington"},"63":{"code":"WV","name":"West Virginia"},"64":{"code":"WI","name":"Wisconsin"},"65":{"code":"WY","name":"Wyoming"}},"RO":{"278":{"code":"AB","name":"Alba"},"279":{"code":"AR","name":"Arad"},"280":{"code":"AG","name":"Arge\u015f"},"281":{"code":"BC","name":"Bac\u0103u"},"282":{"code":"BH","name":"Bihor"},"283":{"code":"BN","name":"Bistri\u0163a-N\u0103s\u0103ud"},"284":{"code":"BT","name":"Boto\u015fani"},"286":{"code":"BR","name":"Br\u0103ila"},"285":{"code":"BV","name":"Bra\u015fov"},"287":{"code":"B","name":"Bucure\u015fti"},"288":{"code":"BZ","name":"Buz\u0103u"},"290":{"code":"CL","name":"C\u0103l\u0103ra\u015fi"},"289":{"code":"CS","name":"Cara\u015f-Severin"},"291":{"code":"CJ","name":"Cluj"},"292":{"code":"CT","name":"Constan\u0163a"},"293":{"code":"CV","name":"Covasna"},"294":{"code":"DB","name":"D\u00e2mbovi\u0163a"},"295":{"code":"DJ","name":"Dolj"},"296":{"code":"GL","name":"Gala\u0163i"},"297":{"code":"GR","name":"Giurgiu"},"298":{"code":"GJ","name":"Gorj"},"299":{"code":"HR","name":"Harghita"},"300":{"code":"HD","name":"Hunedoara"},"301":{"code":"IL","name":"Ialomi\u0163a"},"302":{"code":"IS","name":"Ia\u015fi"},"303":{"code":"IF","name":"Ilfov"},"304":{"code":"MM","name":"Maramure\u015f"},"305":{"code":"MH","name":"Mehedin\u0163i"},"306":{"code":"MS","name":"Mure\u015f"},"307":{"code":"NT","name":"Neam\u0163"},"308":{"code":"OT","name":"Olt"},"309":{"code":"PH","name":"Prahova"},"311":{"code":"SJ","name":"S\u0103laj"},"310":{"code":"SM","name":"Satu-Mare"},"312":{"code":"SB","name":"Sibiu"},"313":{"code":"SV","name":"Suceava"},"314":{"code":"TR","name":"Teleorman"},"315":{"code":"TM","name":"Timi\u015f"},"316":{"code":"TL","name":"Tulcea"},"318":{"code":"VL","name":"V\u00e2lcea"},"317":{"code":"VS","name":"Vaslui"},"319":{"code":"VN","name":"Vrancea"}},"CA":{"66":{"code":"AB","name":"Alberta"},"67":{"code":"BC","name":"British Columbia"},"68":{"code":"MB","name":"Manitoba"},"70":{"code":"NB","name":"New Brunswick"},"69":{"code":"NL","name":"Newfoundland and Labrador"},"72":{"code":"NT","name":"Northwest Territories"},"71":{"code":"NS","name":"Nova Scotia"},"73":{"code":"NU","name":"Nunavut"},"74":{"code":"ON","name":"Ontario"},"75":{"code":"PE","name":"Prince Edward Island"},"76":{"code":"QC","name":"Quebec"},"77":{"code":"SK","name":"Saskatchewan"},"78":{"code":"YT","name":"Yukon Territory"}},"LT":{"475":{"code":"LT-AL","name":"Alytaus Apskritis"},"476":{"code":"LT-KU","name":"Kauno Apskritis"},"477":{"code":"LT-KL","name":"Klaip\u0117dos Apskritis"},"478":{"code":"LT-MR","name":"Marijampol\u0117s Apskritis"},"479":{"code":"LT-PN","name":"Panev\u0117\u017eio Apskritis"},"480":{"code":"LT-SA","name":"\u0160iauli\u0173 Apskritis"},"481":{"code":"LT-TA","name":"Taurag\u0117s Apskritis"},"482":{"code":"LT-TE","name":"Tel\u0161i\u0173 Apskritis"},"483":{"code":"LT-UT","name":"Utenos Apskritis"},"484":{"code":"LT-VL","name":"Vilniaus Apskritis"}},"DE":{"80":{"code":"BAW","name":"Baden-W\u00fcrttemberg"},"81":{"code":"BAY","name":"Bayern"},"82":{"code":"BER","name":"Berlin"},"83":{"code":"BRG","name":"Brandenburg"},"84":{"code":"BRE","name":"Bremen"},"85":{"code":"HAM","name":"Hamburg"},"86":{"code":"HES","name":"Hessen"},"87":{"code":"MEC","name":"Mecklenburg-Vorpommern"},"79":{"code":"NDS","name":"Niedersachsen"},"88":{"code":"NRW","name":"Nordrhein-Westfalen"},"89":{"code":"RHE","name":"Rheinland-Pfalz"},"90":{"code":"SAR","name":"Saarland"},"91":{"code":"SAS","name":"Sachsen"},"92":{"code":"SAC","name":"Sachsen-Anhalt"},"93":{"code":"SCN","name":"Schleswig-Holstein"},"94":{"code":"THE","name":"Th\u00fcringen"}},"AT":{"102":{"code":"BL","name":"Burgenland"},"99":{"code":"KN","name":"K\u00e4rnten"},"96":{"code":"NO","name":"Nieder\u00f6sterreich"},"97":{"code":"OO","name":"Ober\u00f6sterreich"},"98":{"code":"SB","name":"Salzburg"},"100":{"code":"ST","name":"Steiermark"},"101":{"code":"TI","name":"Tirol"},"103":{"code":"VB","name":"Voralberg"},"95":{"code":"WI","name":"Wien"}},"EE":{"340":{"code":"EE-37","name":"Harjumaa"},"341":{"code":"EE-39","name":"Hiiumaa"},"342":{"code":"EE-44","name":"Ida-Virumaa"},"344":{"code":"EE-51","name":"J\u00e4rvamaa"},"343":{"code":"EE-49","name":"J\u00f5gevamaa"},"346":{"code":"EE-59","name":"L\u00e4\u00e4ne-Virumaa"},"345":{"code":"EE-57","name":"L\u00e4\u00e4nemaa"},"348":{"code":"EE-67","name":"P\u00e4rnumaa"},"347":{"code":"EE-65","name":"P\u00f5lvamaa"},"349":{"code":"EE-70","name":"Raplamaa"},"350":{"code":"EE-74","name":"Saaremaa"},"351":{"code":"EE-78","name":"Tartumaa"},"352":{"code":"EE-82","name":"Valgamaa"},"353":{"code":"EE-84","name":"Viljandimaa"},"354":{"code":"EE-86","name":"V\u00f5rumaa"}}});
                        //]]>
                    </script>
                    <form id="co-shipping-method-form" action="https://www.subispeed.com/checkout/cart/estimateUpdatePost/">
                        <dl class="sp-methods">
                            <br>
                            <dt> </dt>
                            <dd>
                            <ul>
                                <li>
                                    <input name="estimate_method" type="radio" value="usps_1" id="s_method_usps_1" class="radio">
                                    <label for="s_method_usps_1">
                                    Priority Mail                                                                                                            <span class="price">$12.43</span>                                                                        </label>
                                </li>
                            </ul>
                            </dd>
                            <br>
                            <dt> </dt>
                            <dd>
                            <ul>
                                <li>
                                    <input name="estimate_method" type="radio" value="ups_GND" id="s_method_ups_GND" class="radio">
                                    <label for="s_method_ups_GND">
                                    Ground                                                                                                            <span class="price">$11.65</span>                                                                        </label>
                                </li>
                                <li>
                                    <input name="estimate_method" type="radio" value="ups_2DA" id="s_method_ups_2DA" class="radio">
                                    <label for="s_method_ups_2DA">
                                    2nd Day Air                                                                                                            <span class="price">$27.58</span>                                                                        </label>
                                </li>
                                <li>
                                    <input name="estimate_method" type="radio" value="ups_1DA" id="s_method_ups_1DA" class="radio">
                                    <label for="s_method_ups_1DA">
                                    Next Day Air                                                                                                            <span class="price">$70.21</span>                                                                        </label>
                                </li>
                            </ul>
                            </dd>
                        </dl>
                        <div class="buttons-set">
                            <button type="submit" title="Update Total" class="button" name="do" value="Update Total"><span><span>Update Total</span></span></button>
                        </div>
                        <input name="form_key" type="hidden" value="fd6cOZl3Q1sf6OcQ">
                    </form>
                    <script type="text/javascript">
                        //<![CDATA[
                            var coShippingMethodForm = new VarienForm('shipping-zip-form');
                            var countriesWithOptionalZip = ["HK","IE","MO","PA"];
                        
                            coShippingMethodForm.submit = function () {
                                var country = $F('country');
                                var optionalZip = false;
                        
                                for (i=0; i < countriesWithOptionalZip.length; i++) {
                                    if (countriesWithOptionalZip[i] == country) {
                                        optionalZip = true;
                                    }
                                }
                                if (optionalZip) {
                                    $('postcode').removeClassName('required-entry');
                                }
                                else {
                                    $('postcode').addClassName('required-entry');
                                }
                                return VarienForm.prototype.submit.bind(coShippingMethodForm)();
                            }
                        //]]>
                    </script>
                </div>
                </div>
            </div>
        </div>
        <div class="grid_6">
            <div class="block block_coupon">
                <form id="discount-coupon-form" action="https://www.subispeed.com/checkout/cart/couponPost/" method="post">
                <div class="discount">
                    <h2>Discount Codes</h2>
                    <div class="discount-form">
                        <label for="coupon_code">Enter your coupon code if you have one.</label>
                        <input type="hidden" name="remove" id="remove-coupone" value="0">
                        <div class="input-box">
                            <input class="input-text" id="coupon_code" name="coupon_code" value="">
                        </div>
                        <div class="buttons-set">
                            <button type="button" title="Apply Coupon" class="button" onclick="discountForm.submit(false)" value="Apply Coupon"><span><span>Apply Coupon</span></span></button>
                        </div>
                    </div>
                </div>
                </form>
                <script type="text/javascript">
                //<![CDATA[
                var discountForm = new VarienForm('discount-coupon-form');
                discountForm.submit = function (isRemove) {
                    if (isRemove) {
                        $('coupon_code').removeClassName('required-entry');
                        $('remove-coupone').value = "1";
                    } else {
                        $('coupon_code').addClassName('required-entry');
                        $('remove-coupone').value = "0";
                    }
                    return VarienForm.prototype.submit.bind(discountForm)();
                }
                //]]>
                </script>
                <form id="discount-giftcard-form" class="gift-card" action="https://www.subispeed.com/giftvoucher/checkout/giftcardPost/" method="post">
                <div class="discount">
                    <h2>Gift Card</h2>
                    <div class="discount-form">
                        <dl id="giftvoucher_container" class="form-group" style="">
                            <dt class="giftvoucher">
                            <input type="checkbox" name="giftvoucher" id="giftvoucher" value="1" onclick="showCartGiftCardInput(this)">
                            <label for="giftvoucher" style="font-weight: bold; color: #666;">Use Gift Card to check out</label>
                            </dt>
                            <dd class="giftvoucher" style="display: none;">
                            <ul id="payment_form_giftvoucher">
                                <li id="giftvoucher_message">
                                </li>
                                <li id="giftvoucher-custom-code" class="form-group">
                                    <label for="giftvoucher_code">Enter a Gift Card code</label>
                                    <input type="text" title="Gift Card Code" class="input-text form-control" id="giftvoucher_code" name="giftvoucher_code">
                                </li>
                                <li style="color: #eb340a;padding-left: 5px;font-size: 11px;font-weight: bold;line-height: 13px;" id="giftcard_notice"></li>
                                <li>
                                    To check your Gift card information, please click <a target="_blank" href="https://www.subispeed.com/giftvoucher/index/check/">here</a>.                                                    
                                </li>
                            </ul>
                            </dd>
                            <dt style="display: none;"></dt>
                            <dd id="giftcard_shoppingcart_apply" style="display: none;">
                            <ul>
                                <li>
                                    <div class="input-box">
                                        <!--<button type="submit" class="button">-->
                                        <button type="button" class="button validation-passed" onclick="check_giftcode()">
                                        <span><span>Apply Gift Card</span></span>
                                        </button>
                                    </div>
                                </li>
                                <p></p>
                            </ul>
                            </dd>
                            <dt style="display: none;"></dt>
                            <dd class="form-group" id="giftcard_shoppingcart_reloading" style="display:none;">
                            <p style="font-weight: normal; font-style: italic;">
                                <img src="https://www.subispeed.com/skin/frontend/athlete/default/images/opc-ajax-loader.gif" alt="Loading..." title="Refreshing cart, please wait..." class="v-middle">
                                Refreshing cart, please wait...
                            </p>
                            </dd>
                        </dl>
                    </div>
                </div>
                </form>
                <script type="text/javascript">
                //<![CDATA[
                    var giftcardForm = new VarienForm('discount-giftcard-form');
                    var noGiftCardTicked = 1;
                    if ($('giftvoucher_credit'))
                        $('giftvoucher_credit').observe('click', function() {
                            if ($('giftvoucher_credit').checked || $('giftvoucher').checked) {
                                $('giftcard_shoppingcart_apply').show();
                            } else if (noGiftCardTicked) {
                                $('giftcard_shoppingcart_apply').hide();
                            } else {
                                $('giftcard_shoppingcart_apply').hide();
                                $('giftcard_shoppingcart_reloading').show();
                                giftcardForm.submit();
                            }
                        });
                    $('giftvoucher').observe('click', function() {
                        var creditChecked = false;
                        if ($('giftvoucher_credit'))
                            creditChecked = $('giftvoucher_credit').checked;
                        if (creditChecked || $('giftvoucher').checked) {
                            $('giftcard_shoppingcart_apply').show();
                        } else if (noGiftCardTicked) {
                            $('giftcard_shoppingcart_apply').hide();
                        } else {
                            $('giftcard_shoppingcart_apply').hide();
                            $('giftcard_shoppingcart_reloading').show();
                            giftcardForm.submit();
                        }
                    });
                    function check_giftcode()
                    {
                        var giftcredit_checked = false;
                        if($('giftvoucher_credit')) giftcredit_checked = $('giftvoucher_credit').checked;
                        
                        if(giftcredit_checked)
                        {
                            if($$('dd.giftvoucher_credit')[0].down('.input-text').value==0)
                                {
                                    if($('giftcredit_notice')) $('giftcredit_notice').style.display="block";
                                    if($$('dd.giftvoucher_credit')[0]!=null)$$('dd.giftvoucher_credit')[0].down('.input-text').addClassName('validation-failed');
                                } 
                            else
                            {
                                if($('giftcredit_notice')) $('giftcredit_notice').style.display="none";
                                if($$('dd.giftvoucher_credit')[0]!=null)$$('dd.giftvoucher_credit')[0].down('.input-text').removeClassName('validation-failed');
                                if($('giftcard_notice'))$('giftcard_notice').innerHTML = "";
                                giftcardForm.submit();             
                            }
                        }
                        if($('giftvoucher').checked)
                        { 
                            if($$('.giftvoucher-discount-code')[0]!=null||(giftcredit_checked && $$('dd.giftvoucher_credit')[0]!=null && $$('dd.giftvoucher_credit')[0].down('.input-text').value!=0))
                            {
                                if($('giftcredit_notice')) $('giftcredit_notice').style.display="none";
                                giftcardForm.submit(); 
                            }
                            else
                            {    
                                if($('giftvoucher_code')&& $('giftvoucher_code').value=="")
                                {
                                    if($('giftvoucher_existed_code')==null)
                                    {
                                        if($('giftvoucher_code'))  $('giftvoucher_code').addClassName("validation-failed");
                                        if($('giftcard_notice'))$('giftcard_notice').innerHTML = "Please enter your code";
                                    }
                
                                    if($('giftvoucher_existed_code')&& $('giftvoucher_existed_code').value=="")
                                    {   
                                        if($('giftvoucher_code'))  $('giftvoucher_code').addClassName("validation-failed");
                                        if($('giftvoucher_existed_code'))  $('giftvoucher_existed_code').addClassName("validation-failed");
                                        if($('giftcard_notice'))$('giftcard_notice').innerHTML = "Please enter or chose your code";
                                    }
                                    if($('giftvoucher_existed_code')&& $('giftvoucher_existed_code').value!="") 
                                    {
                                        if($('giftcredit_notice')) $('giftcredit_notice').style.display="none";
                                        if($('giftcard_notice'))$('giftcard_notice').innerHTML = "";
                                        giftcardForm.submit();
                                    }
                                }
                                else
                                {
                                    if($('giftcredit_notice')) $('giftcredit_notice').style.display="none";
                                    if($('giftcard_notice'))$('giftcard_notice').innerHTML = "";
                                    giftcardForm.submit();
                                }
                            }
                        }
                    }
                //]]>
                </script>
            </div>
        </div>
        <div class="grid_6">
            <div class="block block_totals clearfix">
                <div class="block_totals_indent">
                <table id="shopping-cart-totals-table">
                    <colgroup>
                        <col>
                        <col width="1">
                    </colgroup>
                    <tfoot>
                        <tr>
                            <td style="" class="a-right" colspan="1">
                            <strong>Grand Total Excl. Tax</strong>
                            </td>
                            <td style="" class="a-right">
                            <strong><span class="price">$21.85</span></strong>
                            </td>
                        </tr>
                        <tr>
                            <td style="" class="a-right" colspan="1">
                            <strong>Grand Total Incl. Tax</strong>
                            </td>
                            <td style="" class="a-right">
                            <strong><span class="price">$23.43</span></strong>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody>
                        <tr>
                            <td style="" class="a-right" colspan="1">
                            Subtotal    
                            </td>
                            <td style="" class="a-right">
                            <span class="price">$21.85</span>    
                            </td>
                        </tr>
                        <tr class="summary-details-1 summary-details summary-details-first" style="display:none;">
                            <td class="a-right" style="" colspan="1">
                            UT STATE TAX                                    (4.85%)
                            <br>
                            </td>
                            <td style="" class="a-right" rowspan="1">
                            <span class="price">$1.06</span>                
                            </td>
                        </tr>
                        <tr class="summary-details-1 summary-details" style="display:none;">
                            <td class="a-right" style="" colspan="1">
                            UT COUNTY TAX                                    (1.35%)
                            <br>
                            </td>
                            <td style="" class="a-right" rowspan="1">
                            <span class="price">$0.29</span>                
                            </td>
                        </tr>
                        <tr class="summary-details-1 summary-details" style="display:none;">
                            <td class="a-right" style="" colspan="1">
                            UT CITY TAX                                    (0%)
                            <br>
                            </td>
                            <td style="" class="a-right" rowspan="1">
                            <span class="price">$0.00</span>                
                            </td>
                        </tr>
                        <tr class="summary-details-1 summary-details" style="display:none;">
                            <td class="a-right" style="" colspan="1">
                            UT SPECIAL TAX                                    (1.05%)
                            <br>
                            </td>
                            <td style="" class="a-right" rowspan="1">
                            <span class="price">$0.23</span>                
                            </td>
                        </tr>
                        <tr class="summary-total" onclick="expandDetails(this, '.summary-details-1')">
                            <td style="" class="a-right" colspan="1">
                            <div class="summary-collapse">Tax</div>
                            </td>
                            <td style="" class="a-right"><span class="price">$1.58</span></td>
                        </tr>
                    </tbody>
                </table>
                </div>
                <ul class="checkout-types">
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                <li>   <button type="button" title="Proceed to Checkout" class="button btn-proceed-checkout icon-white btn-checkout" onclick="window.location='https://www.subispeed.com/checkout/onepage/';"><span><span>Proceed to Checkout</span></span></button></li>
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                <!-- Display Affirm Link -->
                <div id="affirm-cart">
                    <a id="learn-more" style="visibility: none; margin: auto;width: 100%;margin: 40px" href="#"></a>
                </div>
                </ul>
            </div>
        </div>
    </div>
    <div class="cart-collaterals">
    </div>
    </div>
</div>

<?php //temp adding old cart to compare html ?>
<?php echo $this->renderView('Shop/Site/Views::cart/cart_tracking.php'); ?>
<div class="">
	<div id="cartView">
		<div id="loaderFull" style="display: none;"><i class="fa fa-circle-o-notch fa-spin"></i></div>
		<?php if (empty($cart->items)) : ?>
			<?php echo $this->renderView('Shop/Site/Views::cart/blocks/empty_cart.php')?>	
		<?php else : ?>
			<?php echo $this->renderView('Shop/Site/Views::cart/blocks/cart_items.php')?>
	</div>
</div>
		<?php endif; ?>
<?php echo $this->renderView('Shop/Site/Views::cart/blocks/mobile_checkout_buttons.php')?>
<div style="clear:both;"></div>

<tmpl type="modules" name="cart-end" />


