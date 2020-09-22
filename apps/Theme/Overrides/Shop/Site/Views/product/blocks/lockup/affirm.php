<?php if ($item->affirmAvailable()) : ?>
<script>
var magPrice = <?php echo $price = $item->price($item->get('variants.0.id')); ?>;
affirm.ui.ready( function() { updateAffirmAsLowAs( magPrice*100 ) } ); // change to your template value for product or cart price
  function updateAffirmAsLowAs( amount ){
    if ( ( amount == null ) || ( amount < 10000 ) ) { return; } // Only display as low as for items over $10 CHANGE FOR A DIFFERENT LIMIT
    // payment estimate options
    var options = {
      apr: "0.10", // percentage assumed APR for loan
      months: 12, // can be 3, 6, or 12
      amount: amount // USD cents
    };
    try {
      typeof affirm.ui.payments.get_estimate; /* try and access the function */
    }
    catch (e) {
      return; /* stops this function from going any further - affirm functions are not loaded and will throw an error */
    }
    // use the payment estimate response
    function handleEstimateResponse (payment_estimate) {
      // the payment comes back in USD cents
      var dollars = ( ( payment_estimate.payment + 99 ) / 100 ) | 0; // get dollars, round up, and convert to int
      // Set affirm payment text
      var a = document.getElementById('learn-more');
      var iText = ('innerText' in a)? 'innerText' : 'textContent';
      a[iText] = "Starting at $" + dollars + " a month. Learn More";
      // open the customized Affirm learn more modal
      a.onclick = payment_estimate.open_modal;
      a.style.visibility = "visible";
    };
    // request a payment estimate
    affirm.ui.payments.get_estimate(options, handleEstimateResponse);
  }
</script>
<div><a id="learn-more" style="visibility: none;position: relative"  href="#"></a></div>
<?php endif; ?>