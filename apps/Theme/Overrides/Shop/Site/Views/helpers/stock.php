
<?php

$img_out_of_stock = "data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD4AAAA+CAYAAABzwahEAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAABmJLR0QA/wD/AP+gvaeTAAAAB3RJTUUH4gcbBQA7FaA3aAAACmZJREFUaN7tm3uQllUdxz+77LIYEKIoXkh0KoUs0kCYEUXHikbQgpTUAW+jg+JJrUY9OmOKF7RjjoPWCUIQUkEHJcTLWJJKpU5qKaGiZiq2XvPGKsKy3Prjex73vOd93t19Yd93sfrO7MDznMtzvufyO7/zPb+3hirCOdcLGA9MAo4A/gjcCiyy1n5czbbUVIFsN+DbwInA94CeQAvwBHAQ0AA0A3cBtwBLrbUbPrPEnXND0cieAPQPrx9BI7zQWvuhc64PMCHkGxXa8y5wGzDfWvvEZ4K4c24gMDEQGRxe/xvYCZgKTLfWflKi7F6h7IlR2X+gWTDfWvvqdkU8jNoPAtlDKRy1W621Tzrn9qN1qj+NRv1Ba+2mEnUeCJwEHA/sFl4/GjphobX2wy4l7pybEwh3B9ahdXor8IC1dmNO/hpgZOiEQ4DfAbdYa5eXqL8b8K2Qfxyt9uEOa+2kriS+JXp8DpgD3GatfbsDZRuAMWhkBwITrLUv5+TbFdmJ04CvRUl9rbWru5L4n4EXkJHaEdgMLAXmWWtvB/BwoNEUL1VPT2vtJ3E+59wE4FS0I9QBHwF3AnsC39lW4rXbQjzgFWvtZLQWjwXuRha6WyB9IfCU14jlIpCeCPzVw8/C6+5or78fOA7ob609LXTyNqOuMyoJjV8PLAIWOed6WGubA+mrQ5ZZHpoNzE/LBtI3o4GwHjDWXuicW2Stbe6sNsbojBHP64Rmr7oPTr51cyBZinSG4R66V4p0xYgDGK31Y4F7SpH3Sk9JPwwcZWS9K4aKEQ/kWwL5h3LITwcWlCC9tpLtqjjxiPzRgVT83XOB+q4gXRXigfxa4KiEfIyqkq4a8Yj8rBLJs6tJuqrEPXwXGbI8zAvp/13EvbyvOylc0/FWVQ/cWU3yFSfu5X0tSUg/Aewb/u0S8hUlHkjfC+yQkB5toBEY3VXkK0Y8eG7TE9JPAqPXOLfOOXfZGuc2lCA/3ctX366JH+ycG+ecK/D7g+c2BsiOms8CYw00AUcCl6CRb0rIvxHeF3huzrluzrmjgW9uD8RnA3sDi4FVzrlLnXN7RORfBw5HgsMRRsoMJMfhiPxi4HAD/4wI7+acuxh4BZ38BiHxYvW2NLwzpKf+wOnAZGAvYBMyZjOQvLQlp8y4QHK8tfaunPQa4DDgLKS81KNOvBGYba19c1vb3Wlio3OuFhgLnImmcg3wEjATiRIftEfcObcjcApwBhrZLUjUmAHcmydndTnxpBP2QTPgNGAXYD1wOzDDWvt4Stw5NwyYgiSmHYD3gbnAzDw5arslHnVAd+CYQOrQ8Ho5sAJpbfOQjjY0pD2GZsjCIGxUDBW/SYk6Yf/QAScBvaOkNUiZnWmt/Xu12lM14lEH9ERCxBjg90h7r+q92f/xv4iCqe6luo5Ftxd7I4m4EQkFS4xuS/C6BTk9KvqUgRuiek5HecqG0XZWBK/7tLHAfkAf4B3gceCe4ADh5Udc3oHPnFUTVTwKuAn4YonMbwJTDNzt1bi5UdoSI0cjq2secPJWEk8HYyfg10i7y0MTcBk6F3ydNi4uIvStC5WPQfde9W1k3gNY4nW7URV4+QCPAV9qI1sf4Drgy5RWeIpQ56EfEvlT0quADaHCGLOAa8rk0ASsDv/vhy7/8tJSzM0hvQ74AA1EPDumoK0xxiZ0J59iYx1wNrrzyrAaCX+PAng5GPcBXwjp9cA5ZRKfbnQ/nrcMPk2L4RUtMTZ5PRW42kCL1x3aArREM5yd5F9jdEgqQh1SP2NMy0gDGHjGwwXovjtDbyqP8cnzn4zWctauN7yuqFfResrskZSp95HtCXjYQFMdkoAKPpDTiGVVIJoiXWIPpBkMNHpFTQwqUcfn0JkgxoHA8lqKlY51ORVU1G8ugZ7Jcyn5eau8vlp0zo0xJCffkHbqaUie087cGs08bddX0gxe9mbfjlVXTPyh5N0FHj4fVd4duDTJ80HyPNyHde/VCQcn6Y1b0bZlyfMJvnj6n422s1L4GNgn+VsJMm43oHNztjUMAZ7zUkdb0B6fbilnoECczJjsBDzuFR0xHIV2ZNhE6aujtrAYeAvYPTz3BJ70Osm9AwyjWI19LOn0zUbGrwi1Bp4BpiXvByAl5Zwc0r8xuhz4efJ+MBIfDkjezzDQbkxMiuAeT0YqTIY+SuLyHNKrkIDZIWTbwCXAlclH8nBjaAzh43e0k/9+4PxySUfk70VbVnsBAs8j9fX9sogb2GLgp8hZmQ28hqboFjTd5gMjDUzOZF8DG1FsygnITmRe01q0JZ4KHG2KG/1eqD/7W90O+QXoYHJDyJ9hM5KkzwUOMFJhW5K6/1V2b3uo8SGAp4wyZeXfGnho8LCz30ZpvEiB8dAL+dMtwFum/enf0Qb3QPaiDmg0ZUxLD7siZ+Tjcsp1iLiXNbya1rBM0LT8LTJ+K2h764hxvYEfhXqPRP8/gsIoq5XoGOxzlgPBF78Ixc/tGiW9jHaU6cgLi3eMJhOdO7yM8zQ0mBnON3BtTcgwDlnqUlO1CW0nHQ0Pux6Fet2EbEBbeAnZghejBo9FcnSvNsq9hg4tsS7wKfGgLzxM4ZK4L3xrS62Xjj2LttfnenRELQcdIQ1ySh7wIbTbwwg0y3q1U24gJUST4ICl0VRvAKdkS7cOTfFdogyNwBXIy5kM7IxOZ7vT6rAMQhb9055GUy9DDbK2MV5D299adD10WJS2F+CCyDGTQpd3U+iIlaGTjkODNAd4ELg4h/svKHaijjdaumTE+yaFNqOtYU7ogP6p9xOWRkx8dXym9sWe2tPAKBMJBSF000Z5JiFv7YCk7MlxNKRXm4aEvCNzRvsYpN3HmGoSQaI2kIwxEPgDOiRMo0ztPVjvVGicZorVkcspPAl2Q75EjJfSEFADLxhYaPKXXm/k0sZYClyVZqwNo/GXnEp2B34MPO/h+2Vw35NiI/hUmilEOT2fvP5G8twR4TDlk4oRF4a7+sKMYbFPQA5+HhpQJGK/Dn58c867UrMmdUJSn6EzHKJZPmc3ylzW143WyyjAIyk5Rk+ktXcEb1E8DYemmYKjlConf0ueh5Xpoa2nWHEZCpyXS9zDCK+z7YsGfoisbCr19KUDCM7IsuT1pb7Y+bmSwmm5ieJT4kC03OIOG+zhfF+4E2VoRr+PSTtwqk86uS4c7peFRlzhZc3fpljxKOee+joU25Zhf+AFr1COj5DyOSwpcxNKX5l8+1rfGiMzAP1Apwey3KkxxMDGcOHxNK1TvAEFEY406mBqkfOS9Xwf4CdINx8Q1ddIGYKjUczL3OT1bsgvOC+H9KvARcHenEFoXITRaL8+JWrrV5FmkPf9ZymePSOI5Oda9KuCtryyZuTxlBs/fiZyRtrDChQY9H5o9CNoT29P4FzRTv3TgPS+/apMvqo18Es0tX6F3LoM61AQz3BTrMu1CwMtRrcbh6BzwEdR8mak3U8BDkodJCM/fRC6M3snqXo58iRH0MZZPuzzE9FvWLLdYgdgjoeavGNpb2Rx3zPl++dtwusA0QN41xRP57bK9UZ/73ZWm/4DONIPLHeOYxcAAAAASUVORK5CYII=";
$img_in_stock = "data:img/png;base64,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";
$img_on_order = "data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAD4AAAA+CAMAAABEH1h2AAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAACylBMVEUAAAB3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKl3kKn///88diToAAAA7HRSTlMAO/rern5PH5bvv5BgMAXj+DJZibnp+9ChcUERL7YZSHio2P3isoJSIwF7ago3Z5fH9PLDbMgdAiZWhuXmF/5KqoWErTjOB/N1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";
$img_ships_direct = "data:img/png;base64,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";

if(empty($item)) {
	$item = $stockProduct;
}

if(!empty($cart_stock)) {
    $shipping_callout = '';
} else if(!empty($item)) {
    if (!$shop['free_ltl'] && $item->shipsLtl()) {
        $shipping_callout = "Excluded from free shipping.";
    } else if ($item->price() > $shop['freeshipping']) {
        $shipping_callout = "Qualifies for free shipping!";
    } else {
        $shipping_callout = "Free shipping on all orders over ".\Shop\Models\Currency::format($shop['freeshipping'])."!";
    }
}

/** @var \Shop\Models\ProductStock $stock */
if(empty($stock)) {
    $stock = $stockProduct->stock((int) empty($qty) ? 1 : $qty);
}

$truck_text = '24<br /><span>hours</span>';
$img_final = $img_in_stock;
$schema_org = 'InStock'; //Really could find a better way to do this but at this time I don't want to change the GA labels to match Schema.org as we already have existing data on the GA labels, I don't want to change them.
$top_text = '';
$extra_class = '';
$cart_unit = 'days';
$ga_label = 'Not Set';
$ships_within = '';

switch ($stock->getStatus()) {
    case \Shop\Models\ProductStock::IN_STOCK_MULTIWAREHOUSE:
    case \Shop\Models\ProductStock::IN_STOCK:
        if(
            !empty($stock_timer_cutoff = (int) $this->app->get('shop.stock_timer_cutoff')) 
            && $item->{'product_type'} != 'dynamic_group'
            && date('N') <= 5
            && \Shop\Models\Settings::fetch()->{'site.ships_today_countdown'}
        ) {
            $curr_time = new DateTime("now", new DateTimeZone('America/Denver'));
            $hour = (int) $curr_time->format('H');
            $min = (int) $curr_time->format('i');
            $stock_timer_cutoff = (int) $this->app->get('shop.stock_timer_cutoff');
            
            if($hour < $stock_timer_cutoff) {
                if($hour == ($stock_timer_cutoff - 1)) {
                    $ships_within = '<div class="ships_today_div"><span class="">Ships <span class="ship_same_day">today</span> if ordered in<br /><span class="ships_today">';
                } else {
                    $ships_within = '<div class="ships_today_div"><span class="">Ships <span class="ship_same_day">today</span> if ordered in<br /><span class="ships_today"><span class="ships_today_hour_text"><span class="ships_today_hour">' . (($stock_timer_cutoff - 1) - $curr_time->format('G')) . '</span> hrs </span>';
                }
                
                if($min == 2) {
                    $ships_within .= '<span class="ships_today_minute">1</span> min</span></span></div>';
                } else {
                    $ships_within .= '<span class="ships_today_minute">' . (59 - $min) . '</span> mins</span></span></div>';
                }
            } else {
                $ships_within = '<span class="ships_24_hours">Ships within 24 hours</span><br />';
            }
        }

        $ga_label = 'InStock';
        $top_text = '<p class="availability in-stock"> <span><span class="tt"><img src="/theme/img/in_stock_jba.jpg" class="amstockstatus_icon" alt="" title=""><span class="amtooltip"></span></span><span class="amstockstatus amsts_501"><span style="font-weight: bold; color:#afd500">In Stock</span></span></span></p>';
		$cart_unit = 'hours';
        break;
    case \Shop\Models\ProductStock::LIMITED_STOCK:
        if(
            !empty($stock_timer_cutoff = (int) $this->app->get('shop.stock_timer_cutoff')) 
            && $item->{'product_type'} != 'dynamic_group'
            && date('N') <= 5
            && \Shop\Models\Settings::fetch()->{'site.ships_today_countdown'}
        ) {
            $curr_time = new DateTime("now", new DateTimeZone('America/Denver'));
            $hour = (int) $curr_time->format('H');
            $min = (int) $curr_time->format('i');
            $stock_timer_cutoff = (int) $this->app->get('shop.stock_timer_cutoff');
            
            if($hour < $stock_timer_cutoff) {
                if($hour == ($stock_timer_cutoff - 1)) {
                    $ships_within = '<div class="ships_today_div"><span class="">Ships <span class="ship_same_day">today</span> if ordered in<br /><span class="ships_today">';
                } else {
                    $ships_within = '<div class="ships_today_div"><span class="">Ships <span class="ship_same_day">today</span> if ordered in<br /><span class="ships_today"><span class="ships_today_hour_text"><span class="ships_today_hour">' . (($stock_timer_cutoff - 1) - $curr_time->format('G')) . '</span> hrs </span>';
                }
                
                if($min == 2) {
                    $ships_within .= '<span class="ships_today_minute">1</span> min</span></span></div>';
                } else {
                    $ships_within .= '<span class="ships_today_minute">' . (59 - $min) . '</span> mins</span></span></div>';
                }
            } else {
                $ships_within = '<span class="ships_24_hours">Ships within 24 hours</span><br />';
            }
        }

        $ga_label = 'LimitedStock';
        $top_text = '<link itemprop="availability" href="http://schema.org/LimitedAvailability"/><span class="in_stock_wrapper"><i class="fa fa-clock-o" style="color: red;" aria-hidden="true"></i> Only ' . $stock->getInventory() . ' left!</span>';
		$cart_unit = 'hours';
        break;
    case \Shop\Models\ProductStock::PARTIAL_STOCK:
    case \Shop\Models\ProductStock::OUT_OF_STOCK:

        $stockDates = $stock->getProductLeadDates();
        $actual = [];
        foreach ($stockDates as $stockDate) {
            $actual[] = round((strtotime($stockDate->format('m/d/Y')) - strtotime(date('m/d/Y'))) / 86400 );
        }

        $extra_class = 'ss_truck-text-small';

        if ($actual[0] == $actual[1]){
		    $top_text = 'Ships within '.$actual[0].' '.$cart_unit.'<br />';
            $truck_text = $actual[0].'<br /><span>'.$cart_unit.'</span>';
        } else {
            $top_text = 'Ships within '.$actual[0]. '-'. $actual[1].' '.$cart_unit.'<br />';
            $truck_text = $actual[0]. '-'. $actual[1].'<br /><span>'.$cart_unit.'</span>';
        }

        if($actual[0] < 14 && \Base::instance()->get('sales_channel','') == 'northridge-canada') {
            $expected = '1-2';
            $cart_unit = 'weeks';
            $extra_class = '';
            $top_text = 'Ships within '.$expected.' '.$cart_unit.'<br />';
		    $truck_text = $expected.'<br /><span>'.$cart_unit.'</span>';
        }

        $img_final = $img_out_of_stock;

        $ga_label = 'OutOfStock';
        $schema_org = 'OutOfStock';
        if ($stock->getQuantityOnOrder() > 0) {
            $schema_org = 'OnOrder';
            $img_final = $img_on_order;
        }

        $top_text = '<span style="color: red;">Out of Stock</span>';

        break;
    case \Shop\Models\ProductStock::DROPSHIP_NOTES:
        $top_text = $stock->getDropShipNotes();
        $img_final = $img_ships_direct;

    break;
    case \Shop\Models\ProductStock::DROPSHIP:
        $img_final = $img_ships_direct;
        $stockDates = $stock->getProductLeadDates();

        $actual = [];

        foreach ($stockDates as $stockDate) {
            $actual[] = round((strtotime($stockDate->format('m/d/Y')) - strtotime(date('m/d/Y'))) / 86400 );
        }

		$extra_class = 'ss_truck-text-small';

		if(\Base::instance()->get('shop.disable_dropship_lead_times')) {
		    $top_text = 'On order from MFG';
		} else {
            $dropshipping = true;
    		if ($actual[0] == $actual[1]){
    		    $top_text = 'On order from MFG<br />Average time to ship: '. $actual[0]. ' business days<br />';
    		} else {
    		    $top_text = 'On order from MFG<br />Average time to ship: '. $actual[0]. '-'. $actual[1]. ' business days<br />';
    		}
		}



        $ga_label = 'DropShip';
		$truck_text = 'SHIPS<br /><span>direct</span>';
        break;
}
?>
<script>
    ga('send', 'event', 'Stock Status Tracking', '<?php echo $ga_label; ?>', 'Viewed product <?php echo $ga_label; ?>');
    $('#productLockup').on('click', '.addToCartButton', function() {
        ga('send', 'event', 'Stock Status Tracking', '<?php echo $ga_label; ?>', 'Added product <?php echo $ga_label; ?> to cart');
    });
</script>
<?php if(empty($cart_stock)) : ?>
    <link itemprop="availability" href="http://schema.org/<?php echo $schema_org; ?>"/><div class="stock_status <?php echo $dropshipping ? '' : 'not_dropshipping'; ?>"><div class="stock_message"><?php echo $top_text; ?></div></div><?php echo $ships_within; ?>
<?php else : ?>
<a href="#" data-toggle="modal" data-target="#ssModal" class="ssModal">
    <div class="ss_cart"><i class="fa fa-truck" aria-hidden="true"></i> <?php echo $top_text . ' ' . $ships_within; ?></div>
</a>
<?php

endif;

$this->app->set('stock', null); //WEST: I just almost don't even care that this is a hack AUSTIN: OK sounds good!
?>
