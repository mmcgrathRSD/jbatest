

<div class="media paymentMethod paypal">
  <a class="media-left" href="#">
    <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg" alt="paypal">
  </a>
  <div class="media-body">
    <h4 class="media-heading">Paypal</h4>
    <div>
		<label>TOKEN:</label> <?php echo $payment['token']; ?>
    </div>
    <div>
		<label>EMAIL:</label> <?php echo $payment['email']; ?>
    </div>
    
  </div>
</div>
