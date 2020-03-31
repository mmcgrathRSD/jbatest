<head  <?php if(!empty($metaproduct) && !empty($item)) : ?> prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#" <?php endif; ?>>
  <?php //echo $this->renderLayout('Assets/Site/Views::common/head_meta.php')?>
  <?php //echo $this->renderLayout('Assets/Site/Views::common/head_tags.php')?>
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
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/js/olegnax/fancybox/jquery.fancybox-1.3.4.css" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/styles.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/css/widgets.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/css/mirasvit/searchautocomplete/amazon.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/css/mirasvit_searchindex.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/css/mirasvit/seo/mirasvit_seo.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/css/mirasvit/seositemap/sitemap.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/olegnax/ajaxcart.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/slideshow.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/rs-plugin/css/settings.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/olegnax/megamenu.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/local.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/custom.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/animation.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/grid.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/retina.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/options_base_default.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/override.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/js/amasty/amconf/css/amconf.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/base/default/js/amasty/amconf/css/tooltipster.css" media="all" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/print.css" media="print" />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/magestore/mobilelibrary.css" defer />
  <link rel="stylesheet" type="text/css" href="https://www.subispeed.com/skin/frontend/athlete/default/css/magestore/giftvoucher.css" defer />
  <link href='//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800' rel='stylesheet' type='text/css'>

  <?php if($DEBUG) :  ?>
  <link href="/minify/css" rel="stylesheet">
  <?php else :?>
  <link href="/theme/css/styles.min.css?<?php echo $cachebuster; ?>" rel="stylesheet">
  <?php endif;?>

  <!-- <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script> -->

  <?php echo $this->renderView('Assets/Site/Views::common/script_variables.php'); ?>
  
 <!-- <script src="/shop-assets/js/vendor/jquery-1.11.2.min.js"></script> -->
  <script src="/shop-assets/js/vendor/instantsearch.min.js?<?php echo $cachebuster; ?>" ></script>
  
  <script src="/theme/js/jba2.js?<?php echo $cachebuster; ?>"></script>
  <script src="/shop-assets/js/vendor/jquery-1.11.2.min.js"></script>
 
  <?php if($DEBUG) :  ?>
  <script src="/minify/js"></script>
  <?php else :?>
  <script src="/theme/js/scripts.min.js?<?php echo $cachebuster; ?>"></script>
  <?php endif;?>
  <script src="/shop-assets/js/generic_algolia_functions.js?<?php echo $cachebuster; ?>" ></script>
  <?php echo $this->renderView('Search/Site/Views::search/functions/search.php'); ?>


</head>
