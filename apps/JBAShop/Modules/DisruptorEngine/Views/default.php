<?php
$title = $module->model->{'cookie_title'};
$timer = $module->model->{'time_to_show'};
$html = $module->model->{'copy'};

$exp_date = new DateTime($module->model->{'publication.end_date'}.' '.$module->model->{'publication.end_time'}, new DateTimeZone('America/Denver'));

$set_terminate = false;

if(isset($_COOKIE[$title."_disruptor"])) {
	$cookie = $_COOKIE[$title."_disruptor"];
	if($cookie == 'terminated') {
	} else if($cookie < time()) {
		$set_terminate = 4000;
		//setcookie($title."_disruptor", 'terminate', $exp_date->getTimestamp());
	} else {
		$set_terminate = 1000 * ($cookie - time());
		
	}
} else {
	setcookie($title."_disruptor", time() + $timer, $exp_date->getTimestamp(), "/");
	$set_terminate = $timer * 1000;
}
?>
<style>
	.disrupt_bg {
		position: fixed;
		top: 0;
		left: 0;
		height: 100vh;
		width: 100vw;
		overflow: hidden;
		background-color: black;
		opacity: 0.6;
		display: none;
		z-index: 9999999998;
	}
	
	.disrupt_fg {
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
	
	.disrupt_close {
		font-weight: 100;
		font-size: 20px;
		position: absolute;
		right: 5px;
		top: 0;
		cursor: pointer;
	}
	
	.disrupt_inner {
		text-align: center;
	}
	
	.disrupt_inner button {
		display: inline-block;
		width: 45%;
		margin-top: 20px;
		padding: 10px 20px;
		font-size: 13px;
		background-color: white;
		font-weight: normal;
		border: 1px solid #999;
	}
	
	.disrupt_inner button:first-of-type {
		margin-right: 5%;
		color: #fff;
		background-color: #0155ab;
		border-color: #0155ab;
	}
	
	.disrupt_inner button:last-of-type {
		margin-left: 5%;
	}
	
	.disrupt_inner button:hover {
		color: #fff;
		background-color: #1287FF;
	}
</style>
<div class="disrupt_bg">
	
</div>
<div class="disrupt_fg">
	<i class="fa fa-times disrupt_close" aria-hidden="true"></i>
	<div class="disrupt_inner">
		<form role="form" action="./shop/cart/addCoupon" method="post" class="form-inline" id="disrupt_form">
			<div class="input-group">
				<input type="hidden" name="coupon_code" id="disrupt_coupon">
			</div>
		</form>
		<?php echo $html; ?>
	</div>
</div>

<script>
var mouse_event = false;
$('.disrupt_bg, .disrupt_close, .disrupt_inner button:last-of-type').click(function() {
	$('.disrupt_bg').hide();
	$('.disrupt_fg').hide();
});
	
$('.disrupt_inner button:first-of-type').click(function() {
	$('#disrupt_coupon').val($(this).attr('code'));
	$('#disrupt_form').submit();
});
	
<?php if($module->model->get('use_mouse_override') == 'yes') : ?>
$("body").on("mousemove",function(event) {
    if (event.pageY < 5 && mouse_event == true) {
		run_ad();
    }
});
<?php endif; ?>
var timer = 0;
function run_ad () {
	mouse_event = false;
	clearTimeout(timer);
	$('.disrupt_bg, .disrupt_fg').show();
	timer = setTimeout(function() {
		document.cookie = '<?php echo $title; ?>_disruptor=terminated;expires=' + new Date(<?php echo $exp_date->getTimestamp(); ?> * 1000).toUTCString() + ';path=/';
	}, 2500);
}


<?php if($set_terminate !== false) : ?>
	mouse_event = true;
	timer = setTimeout(function() {
		run_ad();
	}, <?php echo $set_terminate; ?>);
<?php endif; ?>
	
window.onbeforeunload = function(){ clearTimeout(timer); }
</script>
