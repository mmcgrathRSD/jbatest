<?php
if(!empty($metaDataOverride['topheadercopy'])) {
    $description = $metaDataOverride['description'];
} else {
    $description = $this->app->get('meta.description');
    
    if(!empty($shop['freeshipping'])) {
        $description .= " | Free shipping over " . \Shop\Models\Currency::format( $shop['freeshipping'] ) . "!";
    }
}

if(!empty($metaDataOverride['title'])) {
    $title = $metaDataOverride['title'];
} else {
    $title = $this->app->get('meta.title');
    
    if(!empty($shop['freeshipping'])) {
        $title .= "|" . $this->app->get('meta.retailer');
    }
}

$canonical = !empty($metaDataOverride['canonical']) ? $metaDataOverride['canonical'] : $canonical;
$keywords = !empty($metaDataOverride['keywords']) ? $metaDataOverride['keywords'] : $this->app->get('meta.keywords');
?>
<base href="<?php echo $SCHEME . "://" . $HOST . $BASE . "/"; ?>" />
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="keywords" content="<?php echo $keywords; ?>" />
<meta name="description" content="<?php echo $description; ?>" />
<?php if(!empty($canonical)) :?>
<link rel="canonical" href="<?php echo $canonical;?>" />
<?php endif; ?>
<?php if(!empty($noindex)) :?>
<meta name="robots" content="noindex">
<?php endif; ?>
<link rel="icon" type="image/png" href="/favicon.ico">
<title><?php echo $title  ?></title>
<?php  $openGraph = $this->app->get('og');?>
<?php foreach ($openGraph as $key => $value) : ?>
<?php if(!empty($key) && !empty($value) && is_string($value)) : ?>
<meta property="og:<?php echo $key;?>" content="<?php echo (string) $value;?>" />
<?php endif;?>
<?php endforeach;?>
<?php if(!empty($metaproduct) && !empty($item)) : ?>

<meta property="product:mfr_part_no"       content="<?php echo $item->{'tracking.oem_model_number'}?>" />
<meta property="product:retailer_title"       content="<?php echo $this->app->get('meta.retailer'); ?>" />
<meta property="product:retailer_item_id"       content="<?php echo $item->{'tracking.model_number'}?>" />
<meta property="product:price:amount"           content="<?php echo number_format($item->price(), 2);?>" />
<meta property="product:price:currency"         content="USD" />
<meta property="product:shipping_weight:value"  content="<?php echo $item->{'shipping.weight'};?>" />
<meta property="product:shipping_weight:units"  content="lb" />
<meta property="product:age_group"  content="adult" />
<meta property="product:target_gender"  content="male" />

<?php if($item->inventory_count):?>
<meta property="product:availability" content="in stock" />
<?php  else :?>
<meta property="product:availability" content="oos" />
<?php endif;?>
<meta property="product:condition" content="new" />

<?php if($item->{'manufacturer.title'}):?>
<meta property="product:brand" content="<?php echo $item->{'manufacturer.title'}?>" />
<?php endif;?>

<?php if($item->{'categories.0.title'}):?>
<!-- <meta property="product:category" content="<?php //echo $item->{'categories.0.title'}?>" /> -->
<!-- <meta property="product:retailer_category" content="<?php //echo $item->{'categories.0.title'}?>" /> -->
<?php endif;?>

<?php endif;?>
<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<link rel="manifest" href="/manifest.json">
<meta name="msapplication-TileColor" content="#000000">
<meta name="theme-color" content="#000000">
