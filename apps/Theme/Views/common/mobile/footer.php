
<div class="modal fade" id="toastModal" role="dialog" data-backdrop="false">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">

            <div class="modal-body">
                <p>Item added to your cart!</p>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn btn-primary" data-dismiss="modal">Continue Shopping</a>

                <a href="#" class="btn btn-warning" data-toggle="modal" data-target="#cartModal" class="viewCartModal">Checkout</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="cartModal" tabindex="-1" role="dialog" aria-labelledby="cartModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div id="cartModalContent"></div>
        </div>
    </div>
</div>
<div class="modal fade" id="textModal" tabindex="-1" role="dialog" aria-labelledby="textModal">
    <div class="modal-dialog" role="document">
        <div class="container">
            <div class="modal-content">

                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <a class=" btn btn-default" data-dismiss="modal" aria-hidden="true">Close</a>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="compareModal" tabindex="-1" role="dialog" aria-labelledby="compareModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">
                <div class="modal-header">
                    <a class="pull-right" data-dismiss="modal" aria-hidden="true"><i class="glyphicon x1 glyphicon-remove"></i></a>
                    <h3 class="modal-title">Compare</h3>
                </div>
                <div class="modal-body">
                    <div id="compareContent"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(empty($this->auth->getIdentity()->id)) :  ?>
   <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="container">
                    <div class="modal-header">
						<div style="text-align:center">
							<img src="/theme/img/logo2.png" />
						</div>
                    </div>
                    <div class="modal-body">
                        <?php echo  $this->renderLayout('/Users/Site/Views::login/modal.php');?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</div> <?php //closing container ?>




<?php if($checkoutmode == 0) : ?>
	<div class="footer-promo-wrapper">
		<tmpl type="modules" name="footer-promo" />
	</div>
    <footer>
        <div class="footer clearfix" id="footer">

            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <h4 id="emailSignup" class="marginTop">SIGN UP FOR THE LATEST PROMOTIONS AND DEALS</h4>
               <div id="mc_embed_signup">
                <form class="form-inline" action="//rallysportdirect.us10.list-manage.com/subscribe/post-json?u=f2a46c2927015032f099f48f6&id=ff94960a88&c=?" target="_blank" method="post" id="mc-embedded-subscribe-form">

                    <div class="form-group">
                        <input id="listrak_email_capture_mobile" type="email" class="form-control" style="width: 240px" name="EMAIL" placeholder="enter your email address" required />
                    </div>

                    <button id="listrak_email_capture_mobile_submit" type="submit" class="btn btn-default btn-md listrakFooterSubmit">Send</button>


                </form>
               </div>
            </div>

            <div id="mailChimpSuccess" class="alert alert-success" style="display:none;"></div>
            <div id="mailChimpErrors" class="alert alert-danger" style="display:none;"></div>
            <div style="clear:both;"></div>
            <div class="row">
               <div class="footer-links">
                   <ul>
                        <li><a href="/pages/shipping"> SHIPPING </a></li>
                        <li><a href="/pages/returns"> RETURNS </a></li>
                        <li><a href="/pages/wholesale-program"> WHOLESALE </a></li>
                        <li><a href="/pages/faq"> FAQ </a></li>
                        <li><a href="/pages/about-us"> ABOUT US </a></li>
                        <li><a href="/contact-us"> CONTACT US </a></li>
                        <li><a href="/pages/careers"> CAREERS </a></li>
                        <li><a href="/shop/giftcards/balance"> GIFTCARD BALANCE </a></li>
                    </ul>
               </div>
            </div>
            <div class="row">
                <div class="social-icons">
                    <a href="https://www.facebook.com/Rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-facebook fa-2x"></i></a>
                        <a href="https://twitter.com/RallySportTweet" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-twitter fa-2x"></i></a>
                        <a href="https://instagram.com/rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-instagram fa-2x"></i></a>
                        <a href="https://www.youtube.com/user/rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-youtube fa-2x"></i></a>
                </div>
            </div>
            <div class="footer-copyright">
            <span><a href="/pages/privacy-policy">Privacy Policy</a> | <a href="/pages/terms">Terms of Use</a></span>
             <p>
                @2003-<?php echo date("Y"); ?> RallySportDirect.com All Rights Reserved.
            </p>
        </div>
            <!--/.row-->
			
        </div>
        <!--/.footer-->

        <div class="footer-bottom col-lg-12 col-md-12 col-sm-12 col-xs-12">
          

            <?php if ($this->app->get('DEBUG') ) { ?>
                <div class="c">
                    <div class="stats list-group">
                        <h4>Stats</h4>
                        <div class="list-group-item" >
                            <?php echo \Base::instance()->format('Page rendered in {0} msecs / Memory usage {1} KB',round(1e3*(microtime(TRUE)-$TIME),2),round(memory_get_usage(TRUE)/1e3,1)); ?>
                        </div>
                    </div>
									<div style="clear:both;">
										
									</div>
                    <div style="word-wrap:break-word;">
                        <tmpl type="system.loaded_views" />
                    </div>
                </div>
            <?php } ?>

        </div>
        <!--/.footer-bottom-->
    </footer>
<?php endif;?>
</div>
