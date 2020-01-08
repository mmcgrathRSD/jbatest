
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
									<a data-dismiss="modal" aria-hidden="true"><i class="glyphicon x1 glyphicon-remove"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="container">

                <div class="modal-body text-center">
                    <div id="lightboxVid" class="responsive-video"></div>
                    <img src="" id="lightboxImg">
                    <p id="lightboxCaption"></p>
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


    </div>
</div>


<?php if($checkoutmode == 0) : ?>
	<div class="footer-promo-wrapper">
		<tmpl type="modules" name="footer-promo" />
	</div>
    <footer>
        <div class="container">
            <div class="footer clearfix" id="footer">

            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <h4 id="emailSignup">SIGN UP FOR THE LATEST PROMOTIONS AND DEALS</h4>
                <div id="mc_embed_signup">
                    <form class="form-inline" id="mc-embedded-subscribe-form">
                        <div class="form-group">
                            <input id="listrak_email_capture" type="email" class="form-control" style="width: 240px" name="EMAIL" placeholder="enter your email address" required />
                            <button id="listrak_email_capture_submit" type="submit" class="btn btn-default btn-md listrakFooterSubmit">Send</button>
                        </div>
                    </form>
                </div>
                <div class="footerHelp">
                    Need Help?<hr>
                <div class="marginTop"><?php echo $this->renderView('Theme/Views::common/footer_contact.php'); ?></div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 footerLinks">
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-0 col-xs-6"></div>
                    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-6">
                        <a href="https://www.facebook.com/Rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-facebook fa-2x"></i></a>
                        <a href="https://twitter.com/RallySportTweet" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-twitter fa-2x"></i></a>
                        <a href="https://instagram.com/rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-instagram fa-2x"></i></a>
                        <a href="https://www.youtube.com/user/rallysportdirect" class="social-icon" style="margin-right: 4px;" target="_NEW"><i class="fa fa-youtube fa-2x"></i></a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 hidden-sm hidden-xs"></div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 text-left">
                        <ul>
                            <li><a href="/pages/shipping"> SHIPPING </a></li>
                            <li><a href="/pages/returns"> RETURNS </a></li>
                            <li><a href="/pages/wholesale-program"> WHOLESALE </a></li>
                            <li><a href="/pages/faq"> FAQ </a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 pull-right">
                        <ul>
                            <li><a href="/pages/about-us"> ABOUT US </a></li>
                            <li><a href="/contact-us"> CONTACT US </a></li>
                            <li><a href="/pages/careers"> CAREERS </a></li>
                            <li><a href="/shop/giftcards/balance"> GIFTCARD BALANCE </a></li>
                        </ul>
                    </div>
                </div>

            </div>
            <!--/.row-->
        </div>
        </div>
        <div class="footer-copyright">
            <span ><a href="/pages/privacy-policy">Privacy Policy</a> | <a href="/pages/terms">Terms of Use</a></span>
             <p>
                &copy; 2003-<?php echo date("Y") ?> RallySport Direct LLC
            </p>
        </div>
        <!--/.footer-->

            <?php if ($this->app->get('DEBUG') ) { ?>
						<div class="footer-bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="display:block;">
                <div class="c">
									 <style type="text/css">
										 .footer-bottom{display:block;}
									</style>
                    <div class="stats list-group">
                        <h4>Stats</h4>
                        <div class="list-group-item" >
                            <?php echo \Base::instance()->format('Page rendered in {0} msecs / Memory usage {1} KB',round(1e3*(microtime(TRUE)-$TIME),2),round(memory_get_usage(TRUE)/1e3,1)); ?>
                        </div>
                    </div>
                    <div style="word-wrap:break-word;">
                        <tmpl type="system.loaded_views" />
                    </div>
                </div>
							</div>
            <?php } ?>


        <!--/.footer-bottom-->
    </footer>
<?php endif;?>

<?php  if ( !empty($terms) ){?>
    <script type="text/javascript">
        $(document).ready( function() {
            $('.toggleSearch').click();
        });
    </script>
<?php  } ?>
