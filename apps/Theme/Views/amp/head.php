<!--
				www.rallysportdirect.com/pages/careers

                       `.-//oossyyyyyyssoo//-.`                       
                  `.:+syyyyyyyyyyyyyyyyyyyyyyyys+:.`                  
               `-+syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyys+-`               
            `-oyyyyyyyyyys+/:-..``````..-:/+syyyyyyyyyyo-`            
          `:syyyyyyyyo/.`         `.``       `./oyyyyyyyys:`          
        `/syyyyyyy+-`           ./ooo+-          `-+yyyyyyys/`        
       -syyyyyys:`            ./oooooo+`            `:syyyyyys-       
     `+yyyyyys-             ./ooooooo+-  ``            -syyyyyy+`     
    .syyyyyy:`            ./ooooooo+- `:+oo+:           `:yyyyyys.    
   .syyyyyo.            ./ooooooo+- `:oooooo+.            .oyyyyys.   
  .syyyyy+`           ./ooooooo+-``/oooooooo:`             `+yyyyys.  
 `oyyyyy+`          .+ooooooo+-``/oooooooo:`                `+yyyyys` 
 /yyyyyo`         .+ooooooo+-``/oooooooo:.    `-::::-.`      `oyyyyy/ 
.syyyyy.        -+ooooooo+- `/oooooooo/`    ./oooooooo+/.     .yyyyys.
/yyyyyo       -+ooooooo+- ./ooooooo+/`    ./ooooooooooooo/`    oyyyyy/
oyyyyy:     -+ooooooo+- `/ooooooo+/`    ./ooooooo+//+ooooo+.   :yyyyyo
syyyyy.    :ooooooo+-  `+oooooo+:`    ./ooooooo+:`  `/ooooo/`  .syyyys
yyyyys.    /oooooo/`   -oooooo:`    ./ooooooo+:`    .+ooooo+`  .syyyyy
syyyyy.    .ooooooo+-  .+ooooo-   ./ooooooo+:`    ./ooooooo:   .syyyys
oyyyyy:     ./ooooooo+-`:oooooo+:/ooooooo+:`    .+ooooooo+-    :yyyyyo
/yyyyyo       -+ooooooo+--+oooooooooooo+:`    .+ooooooo+-`     oyyyyy/
.syyyyy.        ./ooooooo+--+oooooooo+:`    -+ooooooo+-       .yyyyys.
 /yyyyyo`         .+ooooooo+-`.-:::-.     -+ooooooo+-`       `oyyyyy/ 
 `oyyyyy+`          .+ooooooo+-         -+ooooooo+-         `+yyyyyo` 
  .syyyyy+`           .+ooooooo+-     -+ooooooo+-          `+yyyyys.  
   .syyyyyo.            -+ooooooo/-`-+ooooooo+-           .oyyyyys.   
    .syyyyyy:`            -+ooooooo+ooooooo+-           `:yyyyyys.    
     `+yyyyyys-             -+ooooooooooo+-            -syyyyyy+`     
       -syyyyyys:`            -+ooooooo+-           `:syyyyyys-       
        `/syyyyyyy+-`           .:/+/:.          `-+yyyyyyys/`        
          `:syyyyyyyyo/.`                    `./oyyyyyyyys:`          
            `-oyyyyyyyyyys+/:-..``````..-:/+syyyyyyyyyyo-`            
               `-+syyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyys+:`               
                  `.:+syyyyyyyyyyyyyyyyyyyyyyyys+:.                   
                       `.-//oossyyyyyyssoo//-.`                       


				www.rallysportdirect.com/pages/careers
-->
<head>
    <meta charset="utf-8">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <?php if(\Audit::instance()->ismobile()) {
        \Dsc\System::instance()->get('session')->set('AMP_SESSION', true);
    }?>
    <meta name="viewport" id="theViewport" content="width=device-width,minimum-scale=1,initial-scale=1 user-scalable=no">
    <meta name="keywords" content="<?php echo $this->app->get('meta.keywords'); ?>" />
	<meta name="description" content="<?php echo $this->app->get('meta.description'); ?>" />
	<meta name="generator" content="<?php echo $this->app->get('meta.generator'); ?>" />
	<?php if(!empty($canonical)) :?>
	<link rel="canonical" href="<?php echo $canonical;?>" />
	<?php endif; ?>
	<?php if(!empty($noindex)) :?>
	<meta name="robots" content="noindex">
	<?php endif; ?>
	<meta name="author" content="rallysportdirect.com">
	<link rel="icon" type="image/png" href="/favicon.ico">
    <title><?php echo $this->app->get('meta.title'); ?></title>
    <meta property="fb:app_id" content="1485845135043370"/>
    <?php  $openGraph = $this->app->get('og');?>
	<?php foreach ($openGraph as $key => $value) : ?>
		<?php if(!empty($key) && !empty($value) && is_string($value)) : ?>
		<meta property="og:<?php echo $key;?>" content="<?php echo (string) $value;?>" />
		<?php endif;?>
	<?php endforeach;?>
	<?php if(!empty($metaproduct) && !empty($item)) : ?>
	<meta property="product:retailer_item_id"       content="<?php echo $item->{'tracking.model_number'}?>" />
  	<meta property="product:price:amount"           content="<?php echo number_format($item->price(), 2);?>" />
  	<meta property="product:price:currency"         content="USD" />
  	<meta property="product:shipping_weight:value"  content="<?php echo $item->{'shipping.weight'};?>" />
  	<meta property="product:shipping_weight:units"  content="lb" />
  	<meta property="product:age_group"  content="adult" />
  	<?php /*?>
  	<meta property="product:sale_price:amount"      content="Sample Sale Price: " />
 	<meta property="product:sale_price:currency"    content="Sample Sale Price: " />
  	<meta property="product:sale_price_dates:start" content="Sample Sale Price Dates: Start" />
  	<meta property="product:sale_price_dates:end"   content="Sample Sale Price Dates: End" />
  	<?php */ ?>
  	<?php if($item->inventory_count):?>
  	<meta property="product:availability"           content="in stock" />
  	<?php  else :?>
  	<meta property="product:availability"           content="available for order" />
  	<?php endif;?>
  	<meta property="product:condition"              content="new" />

	<?php if($item->{'manufacturer.title'}):?>
	<meta property="product:brand"              content="<?php echo $item->{'manufacturer.title'}?>" />
	<?php endif;?>

	<?php if($item->{'categories.0.title'}):?>
	<meta property="product:category"              content="<?php echo $item->{'categories.0.title'}?>" />
	<meta property="product:retailer_category"     content="<?php echo $item->{'categories.0.title'}?>" />

	<?php endif;?>

	<?php endif;?>
	<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
	<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
	<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
	<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#000000">
	<meta name="theme-color" content="#000000">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:400,700" rel="stylesheet">
    <!-- Everything else AMP below -->
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-0.1.js"></script>
    <script async custom-element="amp-accordion" src="https://cdn.ampproject.org/v0/amp-accordion-0.1.js"></script>
    <script async custom-element="amp-carousel" src="https://cdn.ampproject.org/v0/amp-carousel-0.1.js"></script>
    <script async custom-element="amp-youtube" src="https://cdn.ampproject.org/v0/amp-youtube-0.1.js"></script>
    <script async custom-element="amp-lightbox" src="https://cdn.ampproject.org/v0/amp-lightbox-0.1.js"></script>
    <script async custom-element="amp-form" src="https://cdn.ampproject.org/v0/amp-form-0.1.js"></script>
    <style amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes -amp-start{from{visibility:hidden}to{visibility:visible}}</style><noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <?php echo $this->renderLayout('Theme/Views::amp/styles/amp.php')?>
</head>
