<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="dashboard">
                <div class="page-title">
                    <h1>Check Gift card information</h1>
                </div>
                <div class="gift-card">
                    <ul class="messages jba_gift_error_msg" style="display: none;"><li class="error-msg"><ul><li><span></span></li></ul></li></ul>
                    <form action="/shop/giftcards/balance" method="post" id="check-balance" class="gift-card jba_gift_form">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-6">
                                    <ul id="giftvoucher-check-balance">
                                        <li class="form-group">
                                            <label for="giftvoucher_code" class="required">Enter your gift code<em>*</em></label>
                                            <input type="text" id="gift-card-16-digits" title="Gift card code" class="input-text required-entry form-control" id="giftvoucher_code" name="card_number" value="">
                                        </li>
                                        <li class="form-group">
                                            <button class="button" data-task="check-balance"><span><span>Check Gift Card</span></span></button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-lg-6" style="display: none;">
                            <div class="form-group gift_card_pin">
                                <label>4 digit PIN</label>
                                <input class="form-control" type="text" id="gift-card-4-digits" name="card_pin" maxlength="4" placeholder="4 digit PIN" no-validation="true"/>
                            </div>
                        </div>

                        <div class="col-xs-12 text-center" style="display: none;">
                            <div class="form-group">
                                <div class="gift_pin_disclaimer">
                                    <small>This gift card requires a pin, which is located on the back of your gift card</small>
                                </div>
                                <br />
                                <button class="btn btn-primary" data-task="check-balance">Check Balance</button>
                            </div>
                        </div>
                        <div class="row hidden" data-object-type="balance-message" style="display: none;">
                            <div class="col-lg-12 col-xs-12">
                                <h2 class="sub-title">Result</h2>
                                <div class="form-group">
                                    <ul id="giftvoucher-result">
                                        <li class="form-group">
                                            <label for="result_giftvoucher_code">Gift card code</label>
                                            <span id="result_giftvoucher_code"><strong>g8GfkbXXX-devtesting</strong></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="balance">Balance: </label>
                                            <span id="balance"><strong><span class="price">$1.00</span></strong></span>
                                        </li>
                                        <li class="form-group">
                                            <label for="status">Status: </label>
                                            <span id="status">Active</span>
                                        </li>
                                    </ul>
                                </div>                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>