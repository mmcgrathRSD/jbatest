<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="page-title">
                <h1>Edit Account Information</h1>
            </div>
            <form action="/customer/account/edit/" method="post" id="form-validate" autocomplete="off">
                <div class="fieldset">
                    <input name="form_key" type="hidden" value="7HQRJQx8UBmRiMR6">
                    <h2 class="legend">Account Information</h2>
                    <ul class="form-list">
                        <li class="fields">
                            <div class="customer-name">
                                <div class="field name-firstname">
                                    <label for="firstname" class="required"><em>*</em>First Name</label>
                                    <div class="input-box">
                                        <input type="text" id="firstname" name="firstname" value="<?php echo \Dsc\ArrayHelper::get($identity, 'first_name'); ?>" title="First Name" maxlength="255" class="input-text required-entry">
                                    </div>
                                </div>
                                <div class="field name-lastname">
                                    <label for="lastname" class="required"><em>*</em>Last Name</label>
                                    <div class="input-box">
                                        <input type="text" id="lastname" name="lastname" value="<?php echo \Dsc\ArrayHelper::get($identity, 'last_name'); ?>" title="Last Name" maxlength="255" class="input-text required-entry">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <a href="/user/change-password">Change Password</a>
                    </ul>
                </div>
                <div class="buttons-set">
                    <p class="required">* Required Fields</p>
                    <p class="back-link"><a href="/shop/account"><small>« </small>Back</a></p>
                    <button type="submit" title="Save" class="button"><span><span>Save</span></span></button>
                </div>
            </form>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>