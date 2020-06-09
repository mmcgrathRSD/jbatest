<div class="customer-account-login">
    <div class="content-container">
        <div class="main row clearfix">
            <div class="col-main grid_18">
                <div class="account-login">
                    <div class="block block-login">
                        <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
                        <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
                            <ul class="slides jcarousel-list jcarousel-list-horizontal" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 640px;">
                                <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                                    <div class="block-title">
                                        <strong><span>Sign In</span></strong>
                                    </div>
                                    <div class="block-content">
                                        <form action="./login" method="post" id="login-form">
                                            <input name="form_key" type="hidden" value="QMOkWbNbwt6gn3Nm">
                                            <ul class="form-list">
                                                <li>
                                                    <input type="hidden" name="form_key" value="QMOkWbNbwt6gn3Nm">
                                                    <label for="email" class="required">Email Address</label>
                                                    <div class="input-box">
                                                        <input type="text" name="login-username" value="" id="email" class="input-text required-entry validate-email" title="Email Address">
                                                    </div>
                                                </li>
                                                <li>
                                                    <label for="pass" class="required">Password</label>
                                                    <div class="input-box">
                                                        <input type="password" name="login-password" class="input-text required-entry" id="pass" title="Password">
                                                    </div>
                                                </li>
                                                <li id="remember-me-box" class="control">
                                                    <div class="input-box">
                                                        <input type="checkbox" name="remember" class="checkbox" id="remember_mevnuFZgqp3g" checked="checked" title="Remember Me">
                                                    </div>
                                                    <label for="remember_mevnuFZgqp3g">Remember Me</label>
                                                </li>
                                            </ul>
                                            <button type="submit" class="button" title="Login" name="send" id="send2"><span><span>Login</span></span></button>
                                        </form>

                                        <a href="./user/forgot-password" class="forgot-password" id="forgot-password">Forgot Your Password?</a><br>
                                        <a href="/login/validate-email" class="forgot-password">Verify Account<a>
                                    </div>
                                </li>
                                <li class="jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" jcarouselindex="2" style="float: left; list-style: none;">
                                    <div class="block-title">
                                    <strong><span>Forgot Your Password?</span></strong>
                                    </div>
                                    <div class="block-content">
                                    <form action="/user/forgot-password" method="post" id="form-validate">
                                        <ul class="form-list">
                                            <input type="hidden" name="form_key" value="QMOkWbNbwt6gn3Nm">
                                            <li>
                                                <label for="email" class="required">Email Address</label>
                                                <div class="input-box">
                                                <input type="text" name="email" alt="email" id="email_address" class="input-text required-entry validate-email" value="">
                                                </div>
                                            </li>
                                        </ul>
                                        <button type="submit" class="button" title="Submit"><span><span>Submit</span></span></button>
                                        <a href="/sign-in" class="forgot-password" id="back-login">Back to Login?</a>
                                    </form>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        </div>
                        <div class="new-users">
                        <button type="button" title="Register" class="button invert" onclick="window.location='./register';"><span><span>Register</span></span></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>