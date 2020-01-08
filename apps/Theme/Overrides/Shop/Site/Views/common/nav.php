<?php
$topNav = (new \Admin\Models\Navigation)->setCondition('title', 'Top Nav')->getItem();
$menuId = (string) $topNav->id;
$url = \Base::instance()->get('PARAMS.0');
$cache = \Cache::instance();
$time_start = microtime(true);
if (!$cache->exists('mobile_display_menu.' . $menuId, $menu ) || !$cache->exists('tops_display_menu.' . $menuId, $topsMenu )) {
    $topsMenu = '<div class="navTops"><div class="container"><ul class="topNav">';
    $menu = '';
    $tops = (new \Admin\Models\Navigation)->setState('filter.parent', $menuId)->setState('order_clause', array( 'tree'=> 1, 'lft' => 1 ))->getList();
    foreach($tops as $top):
        $secondary = $top->getChildren();
        $topsMenu .= "<a data-id=\"slave-".urlencode($top->slug)."\" href=\"".$top->get('details.url')."\"><li data-id=\"slave-".urlencode($top->slug)."\">".$top->title."<i class='visible-xs pull-right glyphicon glyphicon-chevron-right'></i></li></a>\n<div class='divider_pipe'></div>\n";
        $menu .= '<div data-id="slave-'.urlencode($top->slug).'" class="navSlave"><div class="container"><div>';
		$newRow = 0;
        foreach($secondary as $key => $subcat) :
			if(substr($subcat->get('details.url'), 5, 13) == 'package-deals') {
				if($subcat->get('published')) {
					if(!empty($subcat->get('image.slug'))) {
						$menu .= '<div class="packageDealsDiv"><a href="'.$subcat->get('details.url').'" class="hidden-xs packageDeals"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="'.cloudinary_url($subcat->get('image.slug')).'"></img></a></div>';
					} else {
						$menu .= '<a href="'.$subcat->get('details.url').'" class="hidden-xs packageDeals "><div style="background: url('.cloudinary_url('package_deals_banner_d5klmn.jpg').') no-repeat center;"></div></a>';
					}
					$menu .= '<ul class="visible-xs"><li class="packageDeals"><a href="'.$subcat->get('details.url').'">Package Deals<i class="visible-xs pull-right glyphicon glyphicon-chevron-right"></i></a></li>';
					$menu .= '<li><a href="'.$subcat->get('details.url').'">Shop All</a></li></ul>';
				}
				$newRow--;
			} else {
				$third = $subcat->getChildren();
				if($newRow % 5 == 0) {
					$menu .= '</div><div class="row">';
				}
				$menu .= '<ul>';
				if(!empty($subcat->get('image.slug'))) :
					$menu .= '<a href="'.$subcat->get('details.url').'"><img src="data:image/png;base64,R0lGODlhAQABAAD/ACwAAAAAAQABAAACADs=" data-src="'.\Shop\Models\Products::product_thumb($subcat->get('image.slug')).'" class="nav-img hidden-xs"></a>';
				endif;
				$menu .= '<li><a href="'.$subcat->get('details.url').'">'.ucwords(strtolower($subcat->get('title'))).'<i class="visible-xs pull-right glyphicon glyphicon-chevron-right"></i></a></li>';
				foreach($third as $key => $last) :
					if($key < 5) {
						$menu .= '<li><a href="'.$last->get('details.url').'">'.ucwords(strtolower($last->title)).'</a></li>';
					}
				endforeach;
				$menu .= '<li><a href="'.$subcat->get('details.url').'">Shop All</a></li></ul>';
			}
			$newRow++;
        endforeach;
        $menu .= '</div></div></div>';
    endforeach;

    $cache->set('mobile_display_menu.' . $menuId, $menu, 1800);
	$cache->set('tops_display_menu.' . $menuId, $topsMenu, 1800);
}

if($car = $this->session->get('activeVehicle')) {
	$topsMenu .= '<li class="toggle-ymm hidden-xs"><div>'.$car['vehicle_year'].' '.$car['vehicle_make'].'<br />'.$car['vehicle_model'];
	$topsMenu .= ' ';
	$topsMenu .= $car['vehicle_sub_model'];
	$topsMenu .= '</div></li>';
}
else {
	$topsMenu .= '<li class="toggle-ymm hidden-xs"><div>';
	$topsMenu .= '<i class="glyphicon glyphicon-car"></i>&nbsp;Set Your Car</div></li>';
}
$topsMenu .= '<span class="stretcher"></span><div class="mobilePhone visible-xs"><button type="submit" class="btn btn-md navPhone">';
$topsMenu .= '<a href="tel:+18884572559" id="navPhone">1-888-45-RALLY</a></button></div></ul></div></div>';

$menu = $topsMenu.$menu;

$time_end = microtime(true);
$time = $time_end - $time_start; ?>

<div class="topMaster">
	<?php echo $this->renderView('Theme/Views::common/desktop/nav.php'); ?>
	<?php echo $this->renderView('Theme/Views::common/mobile/nav.php'); ?>
</div>
<?php if($checkoutmode == 0) : ?>
	<?php if($car = $this->session->get('activeVehicle')) : ?>
		<div class="toggle-ymm visible-xs">
			<small class="xsYmm"><strong><?php echo $car['vehicle_year'];?> <?php  echo $car['vehicle_make'];?> <?php echo $car['vehicle_model'];?></strong>
				<?php  echo $car['vehicle_sub_model'];?> <?php  echo $car['vehicle_engine_size'];?></small>

		</div>
	<?php else :?>
        <div class="toggle-ymm visible-xs">
            <i class="glyphicon glyphicon-car"></i>&nbsp;SET YOUR CAR
        </div>
	<?php endif;?>
	<div class="header-promo-wrapper">
		<tmpl type="modules" name="header-promo" />
	</div>
	<div class="navMaster">
		<?php echo $menu; ?>
	</div>
<?php endif; ?>
<?php echo $this->renderView ( 'Shop/Site/Views::common/navymm.php' ); ?>
<div class="mobileNavColumn">
	<div class="mobileNavColumn2"></div>
</div>
