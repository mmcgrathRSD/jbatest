<?php $reviews = (new \JBAShop\Models\UserContent())->setCondition('user_id', $user->id)->setCondition('type', 'review')->setCondition('publication.status', 'published')->getList();?>
<div class="main row">
	<div class="col-main grid_13 custom_left">
		<div class="my-account">
			<div class="page-title">
				<h1>My Product Reviews</h1>
			</div>
			<?php if(count($reviews)) : ?>
			<div class="pager">
				<p class="amount">
					<strong><?php echo count($reviews)?> Item(s)</strong>
				</p>
			</div>
			<table class="data-table" id="my-reviews-table">
				<colgroup>
					<col width="1">
					<col width="210">
					<col width="1">
					<col>
					<col width="1">
				</colgroup>
				<tbody>
					<?php foreach($reviews as $review) : ?>
					<tr class="">
						<td><span class="nobr"><?php echo $review->timeSince();?></span></td>
						<td>
							<h2 class="product-name"><a href="/part/<?php echo $review->product_slug ?>"><?php echo $review->product_title ?></a></h2>
						</td>
						<td>
							<div class="ratings">
								<div class="rating-box">
									<div class="rating" style="width:<?php echo $review->rating * 20; ?>%;"></div>
								</div>
							</div>
						</td>
						<td><?php echo $review->title; ?></td>
						<td class=""><a href="/part/<?php echo $review->product_slug ?>" class="nobr">View Product</a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<script type="text/javascript">decorateTable('my-reviews-table')</script>
			<div class="pager">
				<p class="amount">
					<strong><?php echo count($reviews)?> Item(s)</strong>
				</p>
			</div>
			<?php else : ?>
			You have submitted no reviews.
			<?php endif; ?>
			<div class="buttons-set">
				<p class="back-link"><a href="/shop/account"><small>« </small>Back</a></p>
			</div>
		</div>
	</div>
<?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>