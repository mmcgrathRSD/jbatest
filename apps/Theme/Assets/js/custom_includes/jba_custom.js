// check balance
var $formBalance = $('form#check-balance');
if ($formBalance.size()) {
    var $checkBalanceButton = $('button[data-task="check-balance"]', $formBalance);
    $checkBalanceButton.on('click', function(e) {
        var $this = $(this);
        e.preventDefault();

        var digits20 = $('input#gift-card-16-digits', $formBalance).val().trim();
        var digits4 = $('input#gift-card-4-digits', $formBalance).val().trim();

        $.ajax({
            type: 'POST',
            data: {
                dig20: digits20,
                dig4: digits4
            },
            url: './shop/giftcards/check-balance'
        }).done(function(response) {
            var rowDiv = $('div[data-object-type="balance-message"]', $formBalance).removeClass('hidden');
            if (response.result) {
                var $alertDiv = $('div.alert', rowDiv)
                    .removeClass('alert-danger')
                    .addClass('alert-info')
                    .html(response.balance_msg);
            } else {
                $('div.alert', rowDiv)
                    .removeClass('alert-info')
                    .addClass('alert-danger')
                    .html(response.error);
            }
        }).always(function() {

        });
    });
}