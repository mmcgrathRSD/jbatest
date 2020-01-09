<?php if(empty($update)) : ?>
<div id="checkoutWrapper">
<?php endif;?>
<?php if(!empty($checkout->complete_shipping_form))  : ?>
	
	<div class="alert alert-success">
  <strong>Shipping Complete!</strong> Customer has filled out all shipping fields
</div>
<?php else : ?>
<div class="alert alert-danger">
    <strong>Shipping incomplete!</strong> Customer not filled out shipping fields
</div>
<?php endif; ?>


<?php if(!empty($checkout->complete_billing_form))  : ?>
	
	<div class="alert alert-success">
  <strong>Billing Complete!</strong> Customer has filled out all billing fields
</div>
<?php else : ?>
<div class="alert alert-info">
    <strong>Billing incomplete!</strong> Customer not filled out billing fields
</div>
<?php endif; ?>

<?php if(!empty($checkout->complete_shipping_method))  : ?>
<div class="alert alert-success">
  <strong>Shipping Method Selected !</strong> Customer has  shipping method
</div>
<?php else : ?>
<div class="alert alert-info">
   <strong>Shipping Method NOT Selected !</strong> Customer has  not clicked a shipping method (they had the cheapest auto selected)
</div>
<?php endif; ?>

<?php if(!empty($checkout->complete_checkout_confirmation))  : ?>
<div class="alert alert-success">
  <strong>Checkout Completed</strong> Customer has  completed checkout and landed on confirmation
</div>
<?php else : ?>
<div class="alert alert-info">
   <strong>Checkout Not Complete</strong> customer has not completed checkout
</div>
<?php endif; ?>




<h1> Actions </h1>



<?php foreach($actions as $event ) :?>
 <div class="list-group-item clearfix">
            <b class="text-success"><?php echo $event['action']; ?></b>
            <?php if(!empty($event['properties']['value'])) : ?>
            To  <?php echo $event['properties']['value']; ?>
            <?php endif; ?>
            <span class="pull-right"><?php echo date( 'Y-m-d g:i a', $event['created']);?></span>
        </div>
<?php endforeach;?>


<?php if(empty($update)) : ?>
</div>
<button id="update"> Update
</button>

<script>

$('#update').click(function() {
		$( "#checkoutWrapper" ).load( "/admin/shop/checkouttracking/update/<?php echo $checkout->id; ?>", function() {
		 // alert( "Load was performed." );
		});
	
});



</script>
<?php endif;?>