<head  <?php if(!empty($metaproduct) && !empty($item)) : ?> prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#" <?php endif; ?>>
  <?php echo $this->renderLayout('Assets/Site/Views::common/head_meta.php')?>
  <?php echo $this->renderLayout('Assets/Site/Views::common/head_tags.php')?>
  <?php //echo $this->renderLayout('Assets/Site/Views::common/head_local.php')?>
  <script type="text/javascript">
    //<![CDATA[
    var Athlete = {};
    Athlete.url = 'https://www.subispeed.com/';
    Athlete.store = 'default';
    Athlete.header_search = 1;
    Athlete.button_icons = 'white';
    Athlete.text = {};
    Athlete.text.out_of = '%s out of 5';
      Athlete.login_bg = '';
      Athlete.sliders = {};
    Athlete.sliders.banner = {};
    Athlete.sliders.banner.auto = 8;
    Athlete.sliders.banner.scroll = 1;
    Athlete.sliders.banner.wrap = "circular";
    Athlete.sliders.product = {};
    Athlete.sliders.product.auto = 0;
    Athlete.sliders.product.scroll = 1;
    Athlete.sliders.product.wrap = "last";
    Athlete.sliders.brand = {};
    Athlete.sliders.brand.auto = 6;
    Athlete.sliders.brand.scroll = 4;
    Athlete.sliders.brand.wrap = "circular";

    //]]>
  </script>

  <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">

  <?php if($DEBUG) :  ?>
  <link href="/minify/css" rel="stylesheet">
  <?php else :?>
  <link href="/theme/css/styles.min.css?<?php echo $cachebuster; ?>" rel="stylesheet">
  <?php endif;?>


  <script src="/shop-assets/js/vendor/cloudinary.core.min.js"></script>
  <?php echo $this->renderView('Assets/Site/Views::common/script_variables.php'); ?>
  <script src="/shop-assets/js/vendor/instantsearch.min.js?<?php echo $cachebuster; ?>" ></script>
  
  <?php if($checkoutmode) : ?>
    <link href="/theme/css/bootstrap.css" rel="stylesheet">
  <?php endif; ?>
  <script src="/shop-assets/js/vendor/jquery-1.11.2.min.js"></script>
 
  <?php if($DEBUG) :  ?>
  <script src="/minify/js"></script>
  <?php else :?>
  <script src="/theme/js/scripts.min.js?<?php echo $cachebuster; ?>"></script>
  <?php endif;?>
  <?php echo $this->renderView('Assets/Site/Views::common/script_variables_post_min.php'); ?>
  <script src="/shop-assets/js/generic_algolia_functions.js?<?php echo $cachebuster; ?>" ></script>
  <?php echo $this->renderView('Search/Site/Views::search/functions/search.php'); ?>


</head>
