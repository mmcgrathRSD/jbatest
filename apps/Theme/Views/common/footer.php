<style>
	.disrupt_bg_promotion {
		position: fixed;
		display: none;
		top: 0;
		left: 0;
		height: 100vh;
		width: 100vw;
		overflow: hidden;
		background-color: black;
		opacity: 0.6;
		z-index: 9999999998;
	}

	.disrupt_fg_promotion {
		background-color: white;
		display: none;
		position: fixed;
		left: 50%;
		transform: translate(-50%, -50%);
		top: 50%;
		padding: 20px;
		-moz-box-shadow: 4px 4px 7px #555;
		-webkit-box-shadow: 4px 4px 7px #555;
		box-shadow: 4px 4px 7px #555;
		font-family: eurostile;
		font-weight: 900;
    	font-size: 40px;
		line-height: 40px;
		z-index: 9999999999;
	}

	.disrupt_close_promotion {
		font-weight: 100;
		font-size: 20px;
		position: absolute;
		right: 5px;
		top: 0;
		cursor: pointer;
	}

	.disrupt_inner_promotion {
		text-align: center;
	}
</style>
<div class="disrupt_bg_promotion">

</div>
<div class="disrupt_fg_promotion">
	<i class="fa fa-times disrupt_close_promotion" aria-hidden="true"></i>
	<div class="disrupt_inner_promotion">
		<?php echo @(new \Pages\Models\Pages)->setState('filter.slug', 'promotion')->getItem()->copy; ?>
	</div>
</div>

<script>
$('.disrupt_bg_promotion, .disrupt_close_promotion').click(function() {
	$('.disrupt_bg_promotion').hide();
	$('.disrupt_fg_promotion').hide();
});
</script>

<div class="modal fade" id="site-modal" role="dialog">
<div class="modal-dialog">

  <!-- Modal content-->
  <div class="modal-content container">
	<div class="modal-header">
	  <button type="button" class="close" data-dismiss="modal">&times;</button>
	  <h4 class="modal-title"></h4>
	</div>
	<div class="modal-body">
	</div>
	<div class="modal-footer">
	  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      <a href="#" class="confirmation_link btn btn-default"></a>
	</div>
  </div>

</div>
</div>

<div class="modal fade" id="ssModal" tabindex="-1" role="dialog" aria-labelledby="ssModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="container">
				<div class="modal-header">
					<div style="text-align:center">
						<strong><h4>
							Shipping Policy
							</h4></strong>
					</div>
					<button type="button" class="bootstrap_modal_close" data-dismiss="modal" aria-label="Close"><i class="glyphicon x1 glyphicon-remove"></i></button>
				</div>
				<div class="modal-body">
					<h4>
						TURNAROUND TIME
					</h4>

Items listed as "24 hours" or "Ships same day" will ship the same day if placed on a weekday before 2PM MST / 4PM EST when shipping via UPS.
Items listed differently typically ship by the estimated ship date listed on the product page. These estimated dates are not guaranteed and are subject to change periodically. Our customer service representatives monitor backorders on a daily basis to ensure your order ships as soon as possible.  Orders requiring additional verification (security concerns, incorrect information, etc.) may need additional processing time.

<h4>
	FREE SHIPPING
					</h4>

Free shipping is available within the lower 48 states for orders totaling $<?php echo $shop['freeshipping']; ?> or more. Simply select the free shipping option during checkout. Even though expedited shipping is not free we will still discount the free shipping option from the expedited shipping cost for you. This offer excludes shipments going to Alaska, Hawaii, Military Boxes, and items too large to ship via UPS or USPS. These items are usually large freight or bulky items such as engine blocks, etc.<br />&nbsp;<br />
					<span class="modal-btn" style="width:170px;">
									<a href="/pages/shipping" target="_blank">View Shipping Policy</a>
								</span>
				</div>
			</div>
		</div>
	</div>
</div>
<button id="chat-button" class="live_chat_prompt"></button>

<tmpl type="modules" name="disruptor-engine" />
<?php
$mobile = \Audit::instance()->isMobile();
if($mobile) : ?>
	<?php echo $this->renderView('Theme/Views::common/mobile/footer.php'); ?>
<?php else : ?>
	<?php echo $this->renderView('Theme/Views::common/desktop/footer.php'); ?>
<?php endif; ?>

<script type="text/javascript">
    <?php if(!empty($SiteVersion)): ?>
    ga('set', 'dimension1', '<?php echo $SiteVersion; ?>');
	<?php endif; ?>
	<?php if($ymm = $this->session->get('activeVehicle')): ?>
	ga('set', 'dimension2', '<?php echo $ymm['slug']?>');
	<?php else : ?>
	<?php endif; ?>
	ga('send', 'pageview');
</script>

<!-- BEGIN: Google Trusted Stores -->
<script type="text/javascript">

    var gts = gts || [];

    gts.push(["id", "580647"]);
    gts.push(["badge_position", "BOTTOM_RIGHT"]);
    gts.push(["locale", "en_US"]);
    <?php if(!empty($part_number)) : ?>
    gts.push(["google_base_offer_id", "<?php echo $part_number; ?>"]);
    gts.push(["google_base_subaccount_id", "465965"]);
    gts.push(["google_base_country", "US"]);
    gts.push(["google_base_language", "en"]);
    <?php endif;  ?>
    (function() {
		$( window ).load(function() {
			var gts = document.createElement("script");
			gts.type = "text/javascript";
			gts.async = true;
			gts.src = "https://www.googlecommerce.com/trustedstores/api/js";
			var s = document.getElementsByTagName("script")[0];
			s.parentNode.insertBefore(gts, s);
		});
    })();

</script>
<!-- END: Google Trusted Stores -->

<!-- Event definition to be included in the body before the Strands js library -->
