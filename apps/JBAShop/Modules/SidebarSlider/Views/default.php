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
                    $imgUrl = \cloudinary_url(\Audit::instance()->isMobile() ? $slide['mobile_image']['src'] : $slide['desktop_image']['src'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
                        'secure' => true
                    ]);
                    echo <<<HTML
                    <li>
                        <a href="{$slide['link']['href']}">
                            <span class="text-container top-left">
                                <span class="text">{$slide['line1']}</span>
                                <br/>
                                <span class="text">{$slide['line2']}</span>
                                <br/>
                                <span class="text">{$slide['line3']}</span>
                                <br/>
                                <em class="link">{$slide['link']['text']}</em> 
                            </span>
                            <img src="{$imgUrl}" alt="{$slide['image.alt']}" width="{$slide['image.width']}" height="{$slide['image.height']}">
                         </a>
                     </li>
                    HTML;
                }
            ?>
            </ul>
        </div>
    </div>
</div>
<!-- banner slider EOF -->