<?php 
if($this->auth->getIdentity()->id) {
    $fixName = $this->auth->getIdentity()->fullName();
    if(strlen($fixName) > 10) {
        $fixName = $this->auth->getIdentity()->first_name;
    }
    if(strlen($fixName) > 10) {
        $fixName = 'Account';
    }
} ?>
<amp-sidebar id="sidebar" layout="nodisplay" side="left">
    <div class="aamp_sidebar_mask_shadow"></div>
    <div class="aamp_sidebar_top">
        <?php if($this->auth->getIdentity()->id) : ?>
        <div class="aamp_account"><?php echo $fixName; ?></div>
        <div class="aamp_account_dropdown">
            <div>
                <a href="/shop/account">MY ACCOUNT <i class="fa fa-chevron-right"></i></a><br />
                <a href="/shop/wishlist">WISHLIST <i class="fa fa-chevron-right"></i></a><br />
                <a href="/logout"><button>LOG OUT</button></a>
            </div>
        </div>
        <?php else : ?>
        <div class="aamp_account">
            <a href="/shop/account">Sign In</a>
        </div>
        <?php endif; ?>
    </div>
<?php
$topNav = (new \Admin\Models\Navigation)->setCondition('title', 'Top Nav')->getItem();
$menuId = (string) $topNav->id;
$url = \Base::instance()->get('PARAMS.0');
$cache = \Cache::instance();
$time_start = microtime(true);
if (!$cache->exists('amp_display_menu.' . $menuId, $menu )) {
    $tops = (new \Admin\Models\Navigation)->setState('filter.parent', $menuId)->setState('order_clause', array( 'tree'=> 1, 'lft' => 1 ))->getList();
    $menu .= '<amp-accordion>';
    foreach($tops as $top) {
        $secondary = $top->getChildren();
        $menu .= '<section>';
        $menu .= '<h2 class="aamp_nav_h2">'.$top->title.'<i class="fa fa-chevron-right x2"></i></h2>';
        $menu .= '<amp-accordion class="nested-accordion">';
        foreach($secondary as $key => $subcat) {
            if(substr($subcat->get('details.url'), 5, 13) == 'package-deals') {
                if($subcat->get('published')) {
                    $menu .= '<section>';
                    $menu .= '<h3 class="aamp_nav_h3">Package Deals</h3>';
                    $menu .= '<div class="aamp_sidebar_level3"><ul><li><a href="'.$subcat->get('details.url').'">Shop All</a></li></ul></div>';
                    $menu .= '</section>';
                }
            } else {
                $menu .= '<section>';
                $menu .= '<h3 class="aamp_nav_h3">'.$subcat->get('title').'</h3><div class="aamp_sidebar_level3"><ul>';
                $third = $subcat->getChildren();
                foreach($third as $key => $last) {
                    $menu .= '<li><a href="'.$last->get('details.url').'">'.ucwords(strtolower($last->title)).'</a></li>';
                }
                $menu .= '<li><a href='.$subcat->get('details.url').'>Shop All</a></li></ul>';
                $menu .= '</div></section>';
            }
        }
        $menu .= '</amp-accordion>';
        $menu .= '</section>';
        
    }
    $menu .= '</amp-accordion>';
    $cache->set('amp_display_menu.' . $menuId, $menu, 1800);
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo $menu; 
?>
    <div class="aamp_sidebar_phone">
        <button type="submit"><a href="tel:+18884572559" id="navPhone" class="sliden">1-888-45-RALLY</a></button>
    </div>
</amp-sidebar>