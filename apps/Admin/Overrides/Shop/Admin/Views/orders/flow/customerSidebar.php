<div class="customerStep">

<?php if(!empty($customer)) : ?>
<ul class="list-group">
<li class="list-group-item"><strong><?php  echo  $customer->fullName(); ?></strong></li>
<li class="list-group-item">Total Spent: <?php echo  $customer->totalSpent(); ?></li>
<li class="list-group-item">Customer Since: <?php echo  $customer->customerSince(); ?></li>
</ul>
<?php endif; ?>


<?php if(!empty($cart)) : ?>
<h3>Cart</h3>
<?php foreach($cart->items as $item) :?>
<table class="table responsive-table table-condensed table-border">
<tr ><td colspan="3"><strong><?php  echo  $item['product']['title']; ?></strong></td></tr>
<tr style="border:none;">
<td><img class="thumbnail img-responsive" src="/asset/<?php echo  $item['image']; ?>"></td>
<td><?php echo  $item['product']['prices']['default']; ?></td>
<td><button>Add</button></td>
</tr>
</table>
<?php endforeach; ?>


<?php endif; ?>

</div>