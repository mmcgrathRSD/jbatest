<div class="slideshow-container icons-white">
	<div class="athlete-slideshow" id="athlete-slideshow" data-width="960">
		<!-- SLIDES -->
	<?php $slides = (array) $module->model->get('Hero_Slides');
		foreach($slides as $key => $slide){ ?>
			<div class="slide slide-193 <?php echo $key === 0 ? 'first' : ''; ?>">
			<?php $imgUrl = \cloudinary_url(\Audit::instance()->isMobile() ? $slide['moblile_image']['src'] : $slide['desktop_image']['src'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
	                    'secure' => true
                    ]);?>
				<div class="slide-img" style=" background:url('<?php echo $imgUrl;?>') 50% 0 no-repeat; " data-height="<?php echo \Audit::instance()->isMobile() ? $slide['mobile_image']['height'] : $slide['desktop_image']['height']; ?>" >
					<img width="1" height="1" src="<?php echo \cloudinary_url('pages/spacer.gif', [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
	                    'secure' => true
                    ]); ?>" alt="" />
				</div>
				<div class="slide-content-container">
					<div class="controls-title">
						<div class="controls">
							<div class="slide-control slide-prev"></div>
							<div class="slide-control slide-next"></div>
							<div class="progress"></div>
						</div>
						<div class="slide-title">
							<span class="text" style="color: <?php echo $slide['text_color']; ?>"><?php echo $slide['title'];?></span><br />
							<span class="text" style="color: <?php echo $slide['text_color']; ?>"><?php echo $slide['sub_title'];?></span><br />
							<a class="link" href="<?php echo $slide['link']['href']; ?>" style=""><?php echo $slide['link']['text'] ?></a>
						</div>
						<div class="clear"></div>
					</div>
					<div class="slide-banners hide-below-768">
						<?php if(!empty($slide['slide_banner'])){?>
							<a class="slide-banner clearfix" href="<?php echo $slide['slide_banner']['href']; ?>"><img width="350" height="213" target="_blank" src="<?php echo \cloudinary_url($slide['slide_banner']['src'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
	                    'secure' => true
                    ]); ?>" alt="<?php echo $slide['slide_banner']['alt'] ?>" /></a>	
						<?php } ?>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		<?php } ?>
		<!-- SLIDES END -->
	</div>

	<script>
		var ATHLETE_SLIDESHOW = {
			allowWrap:  <?php echo filter_var($module->model->get('Hero_Options.allow_wrap'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false'; ?>,
			autoHeight: 'calc',
			autoHeightSpeed:  <?php echo (int) $module->model->get('Hero_Options.auto_height_speed'); ?>,
			easing: 'easeInOutExpo',
			fx: 'scrollVert',
			log: <?php echo filter_var($module->model->get('Hero_Options.log'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false'; ?>,
			loader: 'wait',
			next: ' .controls .slide-next',
			pauseOnHover:  <?php echo filter_var($module->model->get('Hero_Options.pause_on_hover'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false'; ?>,
			prev: ' .controls .slide-prev',
			slideActiveClass: 'athlete-slide-active',
			slideClass: 'athlete-slide',
			slides: '> div.slide',
			speed: <?php echo (int) $module->model->get('Hero_Options.speed'); ?>,
			sync: <?php echo filter_var($module->model->get('Hero_Options.sync'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false'; ?>,
			timeout: <?php echo filter_var($module->model->get('Hero_Options.timer'), FILTER_VALIDATE_BOOLEAN) ? $module->model->get('Hero_Options.length') : 0;?>,
			swipe: <?php echo filter_var($module->model->get('Hero_Options.swipe'), FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false'; ?>
		};
	</script>
</div>
<!-- SLIDESHOW EOF -->