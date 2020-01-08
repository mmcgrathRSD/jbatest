<?php echo $this->renderLayout('Theme/Views::emails/header.php')?>
    <div>
        <p><?php echo $product->{'tracking.model_number'} ?> is not longer available or will not be re-stocked.</p>
        <p>Please follow up with the customer to special order this item.  Alternatively look into similar items if we are no longer bringing this on or it is discontinued.</p>
        <p>
            <label>Customer Number: </label><?php echo $user ? $user->{'gp.customer_number'} : 'Guest' ?><br>
            <label>Customer Name: </label><?php echo $user ? $user->fullName() : 'Guest' ?><br>
            <label>Customer Email: </label><?php echo $notification->email ?><br>
            <label>YMM: </label><?php echo !empty($notification['ymm']['title']) ? $notification['ymm']['title'] : 'None Selected' ?><br>
        </p>
    </div>
<?php echo $this->renderLayout('Theme/Views::emails/footer.php')?>
