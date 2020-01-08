<div class="media paymentMethod card">

  <div class="media-body">
    <h4 class="media-heading"><?php echo $payment['cardType']; ?> Card</h4>
    <div>
		<label>TOKEN:</label> <?php echo $payment['token']; ?>
    </div>
    <div>
		<label>CARD:</label> <?php echo $payment['maskedNumber']; ?>
    </div>
    <div>
		<label>RISK:</label> <?php echo @$payment['riskData']['decision']; ?>
    </div>
    
  </div>
</div>
