<?php echo $this->renderLayout('Theme/Views::emails/header.php')?>
<div>
Customer - <?php echo $user->{'gp.customer_number'}?> left a <?php echo $usercontent->type?> on <?php echo date('Y-m-d', $usercontent->{'metadata.created.time'}); ?>
                <p>
				We would like to get more information regarding this <?php echo $usercontent->type?>
                <p>
                Part Number -  <?php echo $product->{'tracking.model_number'}; ?><br>
                Title - <?php echo $usercontent->title; ?><br>
                Message - <?php echo $usercontent->copy; ?><br>
				<p>
</div>
<?php echo $this->renderLayout('Theme/Views::emails/footer.php')?>