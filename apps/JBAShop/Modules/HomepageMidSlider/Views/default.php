<!-- banner slider BOF -->
<div id="banners_slider_home_page" class="banners-slider-container icon-white">
    <div id="banners_slider_home_page_list_nav" class="nav">
        <a class="prev" href="#"> </a>
        <a class="next" href="#"> </a>
    </div>
    <ul id="banners_slider_home_page_list" class="banners">
    <?php
        $slides = $module->model->get('Hero_Slides');
        foreach($slides as $key => $slide){
            $imgUrl = \cloudinary_url(\Audit::instance()->isMobile() ? $slide['mobile_image']['src'] : $slide['desktop_image']['src'], [
                'fetch_format' => 'auto',
                'sign_url' => true,
                'secure' => true
            ]);
            echo <<<HTML
            <li class="slide{$key}">
                <a href="{$slide['link']['href']}">
                    <span class="text-container bottom-left">
                        <span class="text" style="">{$slide['link']['text']}</span><br /> </span>
                    <img src="{$imgUrl}" alt="{$slide['alt']}" width="320" height="220" />
                </a>
            </li>   
            HTML; 
        } ?>
        <!-- <li class="slide47">
            <a href="/subispeed-gift-card-gift-certificate">
                <span class="text-container bottom-left">
                    <span class="text" style="">Gift Cards</span><br /> </span>
                <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/320x220-Gift-Card-Widget-Subi.jpg" alt="" width="320" height="220" />
            </a>
        </li>
        <li class="slide1">
            <a href="/scp/15-wrx">
                <span class="text-container bottom-left">
                    <span class="text" style="">2015+ WRX</span><br /> </span>
                <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/2015_Subaru_WRX_320x220_1.jpg" alt="" width="320" height="220" />
            </a>
        </li>
        <li class="slide3">
            <a href="/scp/15-sti">
                <span class="text-container bottom-left">
                    <span class="text" style="">2015+ WRX STI</span><br /> </span>
                <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/2015_Subaru_STI_320x220_1.jpg" alt="" width="320" height="220" />
            </a>
        </li>
        <li class="slide4">
            <a href="/scp/13-brz">
                <span class="text-container bottom-left">
                    <span class="text" style="">2013+ BRZ</span><br /> </span>
                <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/2013_Subaru_BRZ_320x220_1.jpg" alt="" width="320" height="220" />
            </a>
        </li>
        <li class="slide35">
            <a href="/scp/14-18-forester">
                <span class="text-container bottom-left">
                    <span class="text" style="">14+ Forester</span><br /> </span>
                <img src="https://www.subispeed.com/media/resized/olegnax/athlete/bannerslider/2014_Forester_slide_1.jpg" alt="" width="320" height="220" />
            </a>
        </li> -->
    </ul>
</div>
<!-- banner slider EOF -->