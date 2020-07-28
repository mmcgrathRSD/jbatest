<?php if($module->model->{'listrak-id'}) : ?>
    <div data-ltk-merchandiseblock="<?php echo $module->model->{'listrak-id'}; ?>" class="listrak_master" data-unique-id="product-1" style="display: none;">
        <div class="product-slider-container category-products product-columns-4">
            <div class="clearfix title-container">
                <h3 id="related"><?php echo $module->model->{'listrak-title'}; ?></h3>
                <div class="slider-nav" id="slider__athlete_5f1f2a1bc5275_nav">
                    <ul>
                        <li><a class="prev icon-white disabled" href="#"></a></li>
                        <li><a class="next icon-white" href="#"></a></li>
                    </ul>
                </div>
            </div>
            <div class="slider-container">
                <div class="jcarousel-container jcarousel-container-horizontal" style="position: relative; display: block;">
                    <div class="jcarousel-clip jcarousel-clip-horizontal" style="position: relative;">
                        <ul id="slider__athlete_5f1f2a1bc5275" class="jcarousel-slider-listrak products-grid clearfix jcarousel-list jcarousel-list-horizontal productItem" style="overflow: hidden; position: relative; top: 0px; margin: 0px; padding: 0px; left: 0px; width: 290px;">
                        <script type="text/html">
                            <li class="item  quick-view-container jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                                    <a href="@Recommendation.LinkUrl" title="S@Recommendation.Title" class="product-image">
                                    <!-- <img class="additional_img" src="https://104.207.249.140/media/catalog/product/cache/1/small_image/217x217/85e4522595efc69f496374d01ef2bf13/f/r/frpod_fcs_2_1.png" data-srcx2="https://104.207.249.140/media/catalog/product/cache/1/small_image/434x434/85e4522595efc69f496374d01ef2bf13/f/r/frpod_fcs_2_1.png" width="217" height="217" alt="Scosche MagicMount Fresche Air Freshener Refill Cartridges" style="opacity: 0; display: block;">		 -->
                                        <img class="regular_img" src="@Recommendation.ImageUrl" width="217" height="217" alt="@Recommendation.Title" title="@Recommendation.Title" style="opacity: 1;">						
                                                        </a>
                                    <div class="product-hover">
                                        <h2 class="product-name"><a href="@Recommendation.LinkUrl" title="@Recommendation.Title">@Recommendation.Title</a></h2>
                                        <br>
                                        <div class="price-box">
                                            <span class="regular-price">
                                            <span class="price">$@Recommendation.Price</span>                                    </span>
                                        </div>
                                    </div>
                                    <div class="name">
                                    </div>
                                </li>
                            </script>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>