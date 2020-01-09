<div class="row">
	<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
		<h1 class="page-title txt-color-blueDark">
			<i class="fa fa-table fa-fw "></i> 
				Checkouts 
			<span> > 
				List
			</span>
		</h1>
	</div>
	<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        
	</div>
</div>

<?php 
$path = '/shop/checkout';
$checkout_users = \JBAShop\Models\CheckoutGoals::collection()->find([
    'timestamp' => array( '$gt' => time() - 1000 ),
], [
    'sort' => [
        'timestamp' => -1
    ]
]);

$checkout_users_count = $checkout_users->count();



?>

<div class="row">
    <div class="col-md-12">
    
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left">
                        <h3 class="panel-title"><i class="fa fa-users"></i> Active CheckOuts </h3>
                    </div>
                    <div class="pull-right">
                        <?php echo (int) $checkout_users_count;  ?> Total 
                    </div>
                </div>
            </div>
            
    
            <table class='table table-striped'>
            <thead><td>User</td><td>Time Ago</td></thead>
            <?php foreach ($checkout_users as $user) { ?>
               <tr>

                    <td class="text-success"><a href="/admin/shop/checkouttracking/<?php echo $user['_id']; ?>"><?php echo $user['user_email']; ?></a></td>
                                  
                    <td><?php echo \Dsc\Mongo\Collections\Sessions::ago( $user['timestamp'] ); ?></td>
				</tr>
             <?php } ?>
             </table>
           
        </div>
            
    </div>
  
</div>
<div>

</div>
<div class="row">
    <div class="col-md-3">
    <div class ="well text-center">
    <h1> Total Started <br><br><?php echo \JBAShop\Models\CheckoutGoals::collection()->count(); ?></h1>
      </div>      
    </div>
    
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>Shipping Form  <br><br><?php echo \JBAShop\Models\CheckoutGoals::collection()->count(['complete_shipping_form' => ['$exists' => true]]); ?></h1>
    </div>        
    </div>
     <div class="col-md-3">
    <div class ="well text-center">
    <h1>Shipping Method  <br><br><?php echo \JBAShop\Models\CheckoutGoals::collection()->count(['complete_shipping_method' => ['$exists' => true]]); ?></h1>
         </div>   
    </div>
    <div class="col-md-3">
    <div class ="well text-center">
    <h1>  Completed  <br><br><?php echo \JBAShop\Models\CheckoutGoals::collection()->count(['complete_payment_form' => ['$exists' => true]]); ?></h1>
       </div>     
    </div>
</div>







<form class="searchForm" method="post" action="./admin/shop/checkouttracking">

    <div class="no-padding">
        
     
        
        <div class="row">
            <div class="col-xs-12 col-sm-6">
               <div class="form-group">
                    <div class="input-group">
                        <input class="form-control" type="text" name="filter[keyword]" placeholder="Search..." maxlength="200" value="<?php echo $state->get('filter.keyword'); ?>"> 
                        <span class="input-group-btn">
                            <input class="btn btn-primary" type="submit" onclick="this.form.submit();" value="Search" />
                            <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset</button>
                        </span>
                    </div>
                </div>          
            </div>
            
            <div class="col-xs-12 col-sm-6">
                <div class="text-align-right">
                <ul class="list-filters list-unstyled list-inline">
                    <li>
                        <?php if (!empty($paginated->items)) { ?>
                        <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                        <?php } ?>
                    </li>                
                </ul>    
                </div>
            </div>
        </div>
        
      
                
        <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
        <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />
   
        <?php if (!empty($paginated->items)) { ?>
            <div class="list-group-item">
                <div>
                    Sort by:
                    <a class="btn btn-link" data-sortable="user_email">Email</a>
                    
                </div>
            </div>
        
            <?php foreach($paginated->items as $country) { ?>
            <div class="list-group-item">
                
                    <div class="row">
                 
                    
                        <div class="col-xs-11 col-sm-11 col-md-11">
                        	<div class="row">
                                <div class="col-xs- col-sm-11 col-md-11">
	                            	<legend>
	                            	  <a href="/admin/shop/checkouttracking/<?php echo $country->id; ?>"><?php echo  ($country->user_email ? $country->user_email : 'Guest User'); ?></a>
                                    </legend>
                                    <?php if (!empty($country->loggedin) && $country->loggedin == true) : ?>
                                    <span class="label label-success">Logged In</span>
                                    <?php else :?>
                                    <span class="label label-danger">Logged OUT</span>
                                    <?php endif;?>
                                    <?php if (!empty($country->login_register)) : ?>
                                    <span class="label label-info">Shown Login - Register Page</span>
                   
                                    <?php endif;?>
                                    <?php if (!empty($country->showcheckoutapage)) : ?>
                                    <span class="label label-success">Checkout Form Displayed</span>
                                    <?php endif;?>
                                    
                                     <?php if (!empty($country->complete_shipping_form)) : ?>
                                    <span class="label label-success">Shipping Form Completed </span>
                                    <?php else :?>
                                    <span class="label label-danger">Shipping Form incompleted</span>
                                    <?php endif;?>
	                            
                                    <?php if (!empty($country->complete_shipping_method)) : ?>
                                    <span class="label label-success">Shipping Method Selected</span>
                                    <?php else :?>
                                    <span class="label label-danger">Shipping Method incompleted</span>
                                    <?php endif;?>
                                    
                                    <?php if (!empty($country->complete_payment_form)) : ?>
                                    <span class="label label-success">Payment Method Accepted</span>
                                    <?php else :?>
                                    <span class="label label-danger">Payment Method incompleted</span>
                                    <?php endif;?>
                                    
                                    
                                    
                                    
                                    
                                    
                                    <?php if (!empty($country->complete_checkout_confirmation)) : ?>
                                    <span class="label label-success">Completed Checkout</span>
                                    <?php else :?>
                                    <span class="label label-danger">No Checkout</span>
                                    <?php endif;?>
	                           	
	                           		 <?php if (!empty($country->paymentFailed)) : ?>
                                    <span class="label label-info">Payment Failed (<?php echo  count($country->paymentFailed)?>)</span>
                                    <?php else :?>
                                   
                                    <?php endif;?>
	                            
	                            	<?php if(!empty($country->created) && !empty($country->complete_checkout_confirmation)) : ?>
	                            	<span class="badge">Checkout Took <?php echo ($country->complete_checkout_confirmation - $country->created)/60 ; ?> Minutes)</span>
	                            	
	                            	<?php endif;?>
	                            </div>
		                                             
		                                           	
		                    	
                        	</div>
                        	
                        </div>
                    </div>
                
            </div>
            <?php } ?>
            
            <?php } else { ?>
                <div class="">No items found.</div>
            <?php } ?>
        
        <div class="dt-row dt-bottom-row">
            <div class="row">
                <div class="col-sm-10">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class="datatable-results-count pull-right">
                        <span class="pagination">
                            <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</form>

