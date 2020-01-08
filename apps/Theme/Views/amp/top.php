<div class="aamp_top">
    <label for="sidebar_toggle_button">
        <div class="aamp_open_icon aamp_top_icon">
            <i class="fa fa-bars" aria-hidden="true"></i>
            <button on='tap:sidebar.toggle' id="sidebar_toggle_button">
                Open menu
            </button>
        </div>
    </label>
    <div class="aamp_logo">
        <div class="aamp_logo_holder">
            <a href="/"><amp-img src="/theme/img/logo.png" width="293" height="44" layout="responsive" ></amp-img></a>
        </div>
    </div>
    <a href="/shop/cart">
        <div class="aamp_cart_icon aamp_top_icon">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
        </div>
    </a>
</div>
<?php if($car = $this->session->get('activeVehicle')) : ?>
<amp-accordion>
    <section>
        <h6 class="aamp_clear_styles">
            <div class="aamp_ymm_selected">
                <small><?php echo $car['vehicle_year'].' '.$car['vehicle_make'].' '.$car['vehicle_model'].' '.$car['vehicle_sub_model'].' '.$car['vehicle_engine_size']; ?></small>
            </div>
        </h6>
        <div class="aamp_ymm_selected_open">
            <div class="aamp_container aamp_padding">
                <div class="ymm_set_header">
                    Set Your Car:
                </div>
                <form role="form" method="get" action="/shop/ymm/submit" id="ymmForm" target="_top">
                    <div class="aamp_ymm_trims">
                        <?php  echo '<option selected disabled>' . $car['vehicle_year']. '</option>'?>
                    </div>
                    <div class="aamp_ymm_trims">
                        <?php  echo '<option selected disabled>' . $car['vehicle_make']. '</option>'?>
                    </div>
                    <div class="aamp_ymm_trims">
                        <?php  echo '<option selected disabled>' . $car['vehicle_model']. '</option>'?>
                    </div>
                    <div class="aamp_ymm_trims">
                        <?php  echo '<option selected disabled>' . $car['vehicle_sub_model']. '</option>'?>
                    </div>
                    <a href="/fits/<?php echo $car['slug']?>" class="aamp_button_a">
                        <div class="aamp_button">
                            View All Parts
                        </div>
                    </a>
                    <button id="ymmSubmit" type="submit" class="aamp_button aamp_clear_car">Clear Car</button>
                </form>
            </div>
        </div>
    </section>
</amp-accordion>
<?php else : ?>
<a href="/set-vehicle" class="aamp_ymm">
    <div>
        <amp-img src="/theme/img/ymm-car-icon-RSD.png" width="16" height="14" ></amp-img> SET YOUR CAR
    </div>
</a>
<?php endif; ?>