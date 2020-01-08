<h1>Check Gift Card Balance</h1>

<form id="check-balance" action="/giftcards/balance" method="POST">
    <div class="row hidden" data-object-type="balance-message">
        <div class="col-lg-12 col-xs-12">
            <hr />
            <div class="alert alert-info">
            </div>                
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-lg-6">
            <div class="form-group">
                <label>Gift Card Code</label>
                <input class="form-control gift_card_code" type="text" id="gift-card-16-digits" name="card_number" placeholder="Gift Card Code (no spaces or dashes)" no-validation="true"/>
            </div>
        </div>
        <div class="col-xs-12 col-lg-6">
            <div class="form-group gift_card_pin">
                <label>4 digit PIN</label>
                <input class="form-control" type="text" id="gift-card-4-digits" name="card_pin" maxlength="4" placeholder="4 digit PIN" no-validation="true"/>
            </div>
        </div>

        <div class="col-xs-12 text-center">
            <div class="form-group">
                <div class="gift_pin_disclaimer">
                    <small>This gift card requires a pin, which is located on the back of your gift card</small>
                </div>
                <br />
                <button class="btn btn-primary" data-task="check-balance">Check Balance</button>
            </div>
        </div>
    </div>

</form>