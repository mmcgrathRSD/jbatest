<div class="customer-account-create">
    <div class="content-container">
        <div class="main row clearfix">
        <div class="col-main grid_18">
            <div class="account-create">
                <div class="block block-login">
                    <div class="block-title">
                    <strong><span>Create an Account</span></strong>
                    </div>
                    <div class="block-content">
                    <form action="./register" method="post" id="form-validate">
                        <div class="registration-info personal-info">
                            <input type="hidden" name="success_url" value="">
                            <input type="hidden" name="error_url" value="">
                            <input type="hidden" name="form_key" value="QMOkWbNbwt6gn3Nm">
                            <h2>Personal Information</h2>
                            <ul class="form-list">
                                <li class="fields">
                                <div class="customer-name">
                                    <div class="field name-firstname">
                                        <label for="firstname" class="required"><em>*</em>First Name</label>
                                        <div class="input-box">
                                            <input type="text" id="first_name" name="first_name" value="" title="First Name" maxlength="255" class="input-text required-entry">
                                        </div>
                                    </div>
                                    <div class="field name-lastname">
                                        <label for="lastname" class="required"><em>*</em>Last Name</label>
                                        <div class="input-box">
                                            <input type="text" id="last_name" name="last_name" value="" title="Last Name" maxlength="255" class="input-text required-entry">
                                        </div>
                                    </div>
                                </div>
                                </li>
                                <li>
                                <label for="email_address" class="required"><em>*</em>Email Address</label>
                                <div class="input-box">
                                    <input type="text" name="email" id="email_address" value="" title="Email Address" class="input-text validate-email required-entry">
                                </div>
                                </li>
                            </ul>
                        </div>
                        <div class="registration-info login-info">
                            <h2>Login Information</h2>
                            <ul class="form-list">
                                <li class="fields">
                                <div class="field">
                                    <label for="password" class="required"><em>*</em>Password</label>
                                    <div class="input-box">
                                        <input type="password" name="new_password" id="password" title="Password" class="input-text required-entry validate-password">
                                    </div>
                                </div>
                                <div class="field">
                                    <label for="confirmation" class="required"><em>*</em>Confirm Password</label>
                                    <div class="input-box">
                                        <input type="password" name="confirm_new_password" title="Confirm Password" id="confirmation" class="input-text required-entry validate-cpassword">
                                    </div>
                                </div>
                                </li>
                            </ul>
                        </div>
                        <div class="buttons-set clearfix">
                            <p class="required">* Required Fields</p>
                            <div class="registration-info">
                                <button type="submit" title="Submit" class="button"><span><span>Submit</span></span></button>
                            </div>
                            <div class="registration-info login-info">
                                <button onclick="window.location='/sign-in'; " type="button" title="Back" class="button inverted"><span><span><small>« </small>Back</span></span></button>
                            </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
   </div>
</div>


