<!-- banner slider BOF -->
<div id="banners_slider_sidebar" class="banners-slider-container icon-white">
    <div id="banners_slider_sidebar_list_nav" class="nav">
        <a class="prev" href="#">&nbsp;</a>
        <a class="next" href="#">&nbsp;</a>
    </div>
    <div class="jcarousel-container jcarousel-container-horizontal">
        <div class="jcarousel-clip jcarousel-clip-horizontal">
            <ul id="banners_slider_sidebar_list" class="banners jcarousel-list jcarousel-list-horizontal">
            <?php 
                $slides = $module->model->get('Hero_Slides');
                foreach($slides as $key => $slide){
                    $imgUrl = \cloudinary_url(\Audit::instance()->isMobile() ? $slide['moblile_image']['src'] : $slide['desktop_image']['src'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
                        'secure' => true
                    ]);
                    echo <<<HTML
                    <li class="slide{$key} jcarousel-item jcarousel-item-horizontal jcarousel-item-{$key} jcarousel-item-{$key}-horizontal" jcarouselindex="{$key}">
                        <a href="{$slide['link']['href']}">
                            <span class="text-container top-left" style="display: inline;">
                                <div class="animation-wrapper animation-text" data-width="100%">
                                    <span class="text" style="">{$slide['line1']}</span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="100%">
                                    <span class="text" style="">{$slide['line2']}</span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-text" data-width="100%">
                                    <span class="text" style="">{$slide['line3']}</span>
                                </div>
                                <br style="display: none;">
                                <div class="animation-wrapper animation-link" data-width="100%">
                                    <em class="link" style="">{$slide['link.text']}</em>
                                </div>
                            </span>
                            <img src="{$imgUrl}" alt="{$slide['image.alt']}" width="{$slide['image.width']}" height="{$slide['image.height']}">
                        </a>
                    </li>
                    HTML;
                }
        
            ?>
                <!-- <li class="slide37 jcarousel-item jcarousel-item-horizontal jcarousel-item-1 jcarousel-item-1-horizontal" jcarouselindex="1" style="float: left; list-style: none;">
                    <a href="/subispeed-gift-card-gift-certificate">
                        <span class="text-container top-left" style="display: inline;">
                            <div class="animation-wrapper animation-text" data-width="123" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">The Gift
                                </span>
                            </div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-text" data-width="162" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">That Keeps
                                </span>
                            </div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-text" data-width="108" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Giving</span></div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-link" data-width="86" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Check it out</em></div>
                        </span>
                        <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/Athlete-Banners-232x368-Subi-GC.jpg" alt="" width="232" height="368">
                    </a>
                </li>
                <li class="slide33 jcarousel-item jcarousel-item-horizontal jcarousel-item-2 jcarousel-item-2-horizontal" jcarouselindex="2" style="float: left; list-style: none;">
                    <a href="/subispeed-sequential-full-led-headlights-2015-wrx-2015-sti#.WRnaDPkrKUk">
                        <span class="text-container top-left" style="display: inline;">
                            <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">SubiSpeed 
                                </span>
                            </div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-text" data-width="160" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Headlight</span></div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-link" data-width="79" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Order now</em></div>
                        </span>
                        <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/subispeedheadlight.jpg" alt="" width="232" height="368">
                    </a>
                </li>
                <li class="slide30 jcarousel-item jcarousel-item-horizontal jcarousel-item-3 jcarousel-item-3-horizontal" jcarouselindex="3" style="float: left; list-style: none;">
                    <a href="/2015-wrx-sti-top-modifications">
                        <span class="text-container top-left" style="display: inline;">
                            <div class="animation-wrapper animation-text" data-width="78" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">WRX 
                                </span>
                            </div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-text" data-width="150" data-height="38" style="width: 0px; height: 38px;"><span class="text" style="">Top Mods</span></div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-link" data-width="76" data-height="22" style="width: 0px; height: 22px;"><em class="link" style="">Click here</em></div>
                        </span>
                        <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/youtubechannel.jpg" alt="" width="232" height="368">
                    </a>
                </li>
                <li class="slide33 jcarousel-item jcarousel-item-horizontal jcarousel-item-5 jcarousel-item-5-horizontal" jcarouselindex="5" style="float: left; list-style: none;">
                    <a href="/subispeed-sequential-full-led-headlights-2015-wrx-2015-sti#.WRnaDPkrKUk">
                        <span class="text-container top-left" style="display: inline;">
                            <div class="animation-wrapper animation-text" data-width="149" data-height="38" style="width: 149px; height: 38px;"><span class="text" style="">SubiSpeed 
                                </span>
                            </div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-text" data-width="160" data-height="38" style="width: 160px; height: 38px;"><span class="text" style="">Headlight</span></div>
                            <br style="display: none;">
                            <div class="animation-wrapper animation-link" data-width="79" data-height="22" style="width: 79px; height: 22px;"><em class="link" style="">Order now</em></div>
                        </span>
                        <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/subispeedheadlight.jpg" alt="" width="232" height="368">
                    </a>
                </li> -->
            </ul>
        </div>
    </div>
</div>
<!-- banner slider EOF -->