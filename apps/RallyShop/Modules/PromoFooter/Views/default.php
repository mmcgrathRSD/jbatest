<?php
$link = $module->model->{'promo_link'};
$text = $module->model->{'promo_text'};
$timer_countdown = $module->model->{'promo_count_down_time'};
$timer_countdown_format = $module->model->{'promo_count_down_format'};
$timerset = false;
?>



<?php 

$timerHtml = "<span class='getting-started'></span>";

if (strpos($text,'{timer}') !== false) {
    $text = str_replace('{timer}', $timerHtml, $text);
if(!empty($timer_countdown) && !empty($timer_countdown_format))
    $timerset = true;
}

?>

<div class="footerSale">
<?php if( $link == '' ){?>
     <?php echo strip_tags($text, '<strong><i><span>'); ?>
<?php } else {?>
<a href="<?php echo $link; ?>" class="footer-promo-link">
    <?php echo strip_tags($text, '<strong><i><span>'); ?>
</a>
<?php } ?>
</div>
<?php if($timerset) :?>
<!-- JAVASCRIPTS -->
<script>
	function calcTime(date, offset) {
		countDownDate = new Date("<?php echo $timer_countdown; ?>").getTime();
    d = date;
    utc = d.getTime() + (d.getTimezoneOffset() * 60000);
    nd = new Date(utc + (3600000*offset)).getTime();
		var distance = countDownDate - nd;
		var days = Math.floor(distance / (1000 * 60 * 60 * 24));
		var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
		var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
		var seconds = Math.floor((distance % (1000 * 60)) / 1000);
		if(distance < 0) {
			return false;
		} else {
			return (days == 1 ? days + ' day, ' : '') + (days > 1 ? days + ' days, ' : '') + (hours < 10 ? '0' : '') + hours + ":"
+ (minutes < 10 ? '0' : '') + minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
		}
}

first = calcTime(new Date(), '-7');
if (!first) {
	clearInterval(x);
	$(".getting-started").html("EXPIRED");
} else {
	$(".getting-started").html(first);

	var x = setInterval(function() {
		current = calcTime(new Date(), '-7');
		
		if(current) {
			$(".getting-started").html(current);
		} else {
			$(".getting-started").html("EXPIRED");
				clearInterval(x);
		}
	}, 1000);
}
</script>
<?php endif; ?>
<style>
	.footerSale {
		width:100%;
		font-size:130%;
		font-weight:bold;
		color:#fff;
		text-align:center;
		padding: 1px;
	}
	
	.footerSale{
		-webkit-animation-name: silde_in;
		-webkit-animation-duration: 1.8s;
		-webkit-animation-timing-function: linear;
		-webkit-animation-iteration-count: 1;
		-webkit-animation-direction: normal;
		-webkit-animation-delay: 16;
		-webkit-animation-play-state: running;
		-webkit-animation-fill-mode: forwards;
	}
	
	div.footerSale a.footer-promo-link{
		color : white !important;
	}
	
	a.footer-promo-link:hover {
		text-decoration: underline;
	}
	
	.header-promo-wrapper #footerSale {
		min-height: auto;
		padding: 1px;
		box-shadow: none;
		border: none;
	}
	
	.footer-promo-wrapper .footerSale {
		background: <?php echo ($module->model->{'promo_background_hex_code'} ?  $module->model->{'promo_background_hex_code'} :  '#0155ab');  ?>;
		color: <?php echo ($module->model->{'promo_text_color_hex_code'} ?  $module->model->{'promo_text_color_hex_code'} :  '#FFFFFF');  ?>;
		box-shadow: 0px -2px 5px 0px rgba(0,0,0,0.2);
		border-top: 2px solid rgba(0,0,0,0.2);
		padding:10px;
	}
	
	.header-promo-wrapper {
		background-color: #282828;
	}
	
	.header-promo-wrapper .footerSale {
		background: <?php echo ($module->model->{'promo_background_hex_code'} ?  $module->model->{'promo_background_hex_code'} :  '#282828');  ?>;
		color: <?php echo ($module->model->{'promo_text_color_hex_code'} ?  $module->model->{'promo_text_color_hex_code'} :  '#FFFFFF');  ?>;
	}
	
	div.footerSale a.footer-promo-link {
		color: <?php echo ($module->model->{'promo_text_color_hex_code'} ?  $module->model->{'promo_text_color_hex_code'} :  '#FFFFFF');  ?> !important;
	}
	
	.footerSale strong {
			color: <?php echo ($module->model->{'promo_text_highlight_color_hex_code'} ?  $module->model->{'promo_text_highlight_color_hex_code'} :  '#333333');  ?> !important;
	}
	
	div.footerSale a.footer-promo-link strong {
		color: <?php echo ($module->model->{'promo_text_highlight_color_hex_code'} ?  $module->model->{'promo_text_highlight_color_hex_code'} :  '#333333');  ?> !important;
	}
	
</style>