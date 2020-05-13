<?php
$display_name = ' '; 
$email = \Dsc\ArrayHelper::get($user, 'email');

if($first_name = \Dsc\ArrayHelper::get($user, 'first_name')) {
    $display_name .= $first_name;

    if($last_name = \Dsc\ArrayHelper::get($user, 'last_name')) {
        $display_name .= ' ' . $last_name;
    }
} else if(!empty($email)) {
    $display_name .= $email;
} else {
    $display_name = '';
}
?>
<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="dashboard">
                <div class="page-title">
                    <h1>My Dashboard</h1>
                </div>
                <div class="welcome-msg">
                    <p class="hello"><strong>Hello<?php echo $display_name; ?>,</strong></p>
                    <p>From your My Account Dashboard you have the ability to view a snapshot of your recent account activity and update your account information. Select a link below to view or edit information.</p>
                </div>
                <div class="box-account box-info">
                    <div class="box-head">
                        <h2>Account Information</h2>
                    </div>
                    <div class="col2-set">
                        <div class="col-1">
                            <div class="box">
                                <div class="box-title">
                                    <h3>Contact Information</h3>
                                    <a href="/shop/account/information">Edit</a>
                                </div>
                                <div class="box-content">
                                    <p>
                                        <?php if(!empty($first_name)) { echo $display_name . '<br />'; } ?>
                                        <?php echo $email; ?><br>
                                        <a href="/user/change-password">Change Password</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col2-set">
                        <div class="box">
                            <div class="box-title">
                                <h3>Address Book</h3>
                                <?php if ($existing_addresses = \Shop\Models\CustomerAddresses::fetch()) : ?>
                                    <?php foreach ($existing_addresses as $address) { ?>
                                        <?php echo $address->asString(', '); ?> <a href="/shop/account/address/remove/<?php echo $address->id; ?>" style="margin-left: 5px;">Remove</a> <br/>
                                    <?php } ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>