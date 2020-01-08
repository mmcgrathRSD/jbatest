<?php 
$path = '/shop/checkout';
$query = [
    'timestamp' => ['$gt' => time() - (15 * 60)],
    'device_type' => 'desktop-tablet'
];
$checkout_users = \Shop\Models\CheckoutGoals::collection()->find($query, [
    'sort' => [
        'timestamp' => -1
    ]
]);

$checkout_users_count = \Shop\Models\CheckoutGoals::collection()->count($query);
$query = [
    'timestamp' => ['$gt' => time() - (15 * 60)],
    'device_type' => 'mobile'
];
$checkout_users_mobile = \Shop\Models\CheckoutGoals::collection()->find($query, [
    'sort' => [
            'timestamp' => -1
    ]
]);

$checkout_users_count_mobile = \Shop\Models\CheckoutGoals::collection()->count($query);


?>

<div class="row">
    <div class="col-md-6">
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="panel-title"><i class="fa fa-users"></i> Active Desktop CheckOuts ( Last 15 Minutes )</h3>
                    </div>
                    <div class="pull-right">
                        <?php echo (int) $checkout_users_count;  ?> Total 
                    </div>
                </div>
            </div>
            
    
            <table class='table table-striped table-responsive '>
            <thead><td>User</td><td>Last Event</td><td>State</td></thead>
            <?php foreach ($checkout_users as $user) { ?>
               <tr>

                    <td class="text-success"><a href="/admin/shop/checkouttracking/<?php echo $user['_id']; ?>">
                    <?php if(!empty($user['paypal_checkout'])) : ?>
                    <i class="fa fa-paypal"></i>
                    <?php else : ?>
                    <?php endif; ?>
                     <?php echo ($user['user_email'] ? substr($user['user_email'],0,15) : 'Guest Checkout'); //harsh! echo $user['user_email']; ?></a></td>
                                  
                    <td><?php echo \Dsc\Mongo\Collections\Sessions::ago( $user['timestamp'] ); ?> </td>
                         <td><?php if (!empty($user['complete_checkout_confirmation'])) : ?>
                                    <span class="label label-success">Completed (<?php echo @$user['order_id']; ?> )</span>
                                    <?php else :?>
                                    <?php endif;?>
                                    <?php if (!empty($user['paymentFailed'])) : ?>
                                    <span class="label label-info">Payment Failed (<?php echo  count($user['paymentFailed']);?>)</span>
                                    <?php else :?>
                                   
                                    <?php endif;?>
                                    </td>
				</tr>
				
             <?php } ?>
             </table>
           
        </div>
            
    </div>
    <div class="col-md-6">
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="panel-title"><i class="fa fa-users"></i> Active Mobile CheckOuts ( Last 15 Minutes )</h3>
                    </div>
                    <div class="pull-right">
                        <?php echo (int) $checkout_users_count_mobile;  ?> Total 
                    </div>
                </div>
            </div>
            
    
            <table class='table table-striped table-responsive '>
            <thead><td>User</td><td>Last Event</td> <td>State</td></thead>
            <?php foreach ($checkout_users_mobile as $user) { ?>
               <tr>

                    <td class="text-success">
                    <a href="/admin/shop/checkouttracking/<?php echo $user['_id']; ?>">
                    <?php if(!empty($user['paypal_checkout'])) : ?>
                    <i class="fa fa-paypal"></i>
                    <?php else : ?>
                    <?php endif; ?>
                     
                    <?php echo ($user['user_email'] ? $user['user_email'] : 'Guest Checkout');  ?></a></td>
                                  
                    <td><?php echo \Dsc\Mongo\Collections\Sessions::ago( $user['timestamp'] ); ?> </td>
                    <td><?php if (!empty($user['complete_checkout_confirmation'])) : ?>
                                    <span class="label label-success">Completed (<?php echo @$user['order_id']; ?> )</span>
                                    <?php else :?>
                                    <?php endif;?>
                                    <?php if (!empty($user['paymentFailed'])) : ?>
                                    <span class="label label-info">Payment Failed (<?php echo  count($user['paymentFailed']);?>)</span>
                                    <?php else :?>
                                   
                                    <?php endif;?>
                                    </td>
				</tr>
				
             <?php } ?>
             </table>
           
        </div>
            
    </div>
  
</div>
<div>

</div>

<?php 

$sec = time() - (60 * 60);
?>
<h1>Checkout Stats For Last Hour</h1>
<div class="row">
    <div class="col-md-3">
    <div class ="well text-center">
    <h1> Total Started <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count(array(
    'timestamp' => array( '$gt' =>$sec ),
    'device_type' => ['$exists' => true]
)); ?></h1>
      </div>      
    </div>
   
     <div class="col-md-3">
    <div class ="well text-center">
    <h1>Shipping Method  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_shipping_method' => ['$exists' => true], 'device_type' => ['$exists' => true]]); ?></h1>
         </div>   
    </div>
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>Desktop  Complete  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true],'device_type' => 'desktop-tablet']); ?></h1>
       </div>     
    </div>
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>Mobile  Complete  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true], 'device_type' => 'mobile']); ?></h1>
       </div>     
    </div>
</div>

<?php 

$sec = time() - (date('G') * 3600 + date('i') * 60);
?>
<h1>Checkout Stats For Today</h1>
<div class="row">
    <div class="col-md-3">
    <div class ="well text-center">
    <h1> Total Started <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count(array(
    'timestamp' => array( '$gt' =>$sec ), 'device_type' => ['$exists' => true]
)); ?></h1>
      </div>      
    </div>
    
    
     <div class="col-md-3">
    <div class ="well text-center">
    <h1>Shipping Method  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_shipping_method' => ['$exists' => true], 'device_type' => ['$exists' => true]]); ?></h1>
         </div>   
    </div>
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>Desktop  Complete  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true],'device_type' => 'desktop-tablet']); ?></h1>
       </div>     
    </div>
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>Mobile  Complete  <br><br><?php echo \Shop\Models\CheckoutGoals::collection()->count([ 'timestamp' => array( '$gt' =>$sec ), 'complete_payment_form' => ['$exists' => true], 'device_type' => 'mobile']); ?></h1>
       </div>     
    </div>
</div>



