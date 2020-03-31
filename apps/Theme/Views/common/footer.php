<tmpl type="modules" name="footer-promo" />
<div class="footer-container">
   <div class="footer site-width">
      <div class="footer-info clearfix">
         <div class="info info-content clearfix">
            <div class="one_half">
               <h4><a name="footer"></a>We are here to help</h4>
               <ul>
                  <li><a href="/pages/contacts/">Contact Customer Service</a>
                  </li>
                  <li><a href="/pages/return-policy/">Return / Cancellation Policy</a>
                  </li>
                  <li><a href="/pages/shipping/">Shipping Information</a>
                  </li>
                  <li><a href="/pages/order-tracking/">Track Your Order</a>
                  </li>
                  <li><a href="/giftvoucher/index/check/">Check Gift Card</a>
                  </li>
                  <li><a href="/login-fix-information">I Can't Login, HELP!</a>
                  </li>
               </ul>
               <p></p>
               <p>
                  <script type="text/javascript" src="https://sealserver.trustwave.com/seal.js?style=invert"></script>
               </p>
            </div>
            <div class="one_half last">
               <h4><a name="footer2"></a>Company</h4>
               <ul>
                  <li><a href="/pages/about-us/">About Us</a>
                  </li>
                  <li><a href="/pages/privacy-policy/">Privacy Policy</a>
                  </li>
                  <li><a href="/pages/terms/">Terms of Service</a>
                  </li>
                  <li><a href="/pages/careers/">Careers</a>
                  </li>
                  <li><a href="/pages/military-discount/">Military Discount</a>
                  </li>
               </ul>
            </div>
         </div>
         <div class="newsletter-container">
            <div class="athlete_footer_connect">
               <h4 class="title">Connect with us.</h4>
               <!-- <a title="Rss" class="social-icon icon-rss " href="#" target="_social"></a> -->
               <a title="Facebook" class="social-icon icon-facebook " href="//facebook.com/SubiSpeed" target="_social"></a>
               <a title="Twitter" class="social-icon icon-twitter " href="//twitter.com/SubiSpeedDotCom" target="_social"></a>
               <a title="Mail" class="social-icon icon-mail " href="/contacts/" target="_social"></a>
               <a title="Youtube" class="social-icon icon-youtube " href="//youtube.com/SubiSpeed" target="_social"></a>
               <a title="Instagram" class="social-icon icon-instagram " href="//instagram.com/subispeed" target="_social"></a>
            </div>
         </div>
      </div>
      <div class="facebook-block">
         <div class="fb-like fb_iframe_widget" data-share="false" data-send="false" data-layout="standard" data-width="270" data-height="40" data-show-faces="false" data-font="arial" data-colorscheme="dark" data-action="like" fb-xfbml-state="rendered" fb-iframe-plugin-query="action=like&app_id=195555774072&color_scheme=dark&container_width=270&font=arial&height=35&href=https%3A%2F%2Fwww.subispeed.com%2F&layout=standard&locale=en_US&sdk=joey&send=false&share=false&show_faces=false&width=270" style="height: 30px"><span style="vertical-align: bottom; width: 270px; height: 60px;"><iframe name="f3a61a1aced8364" width="270px" height="40px" title="fb:like Facebook Social Plugin" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" allow="encrypted-media" src="https://www.facebook.com/plugins/like.php?action=like&app_id=195555774072&channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D45%23cb%3Dfedcfd90556bb4%26domain%3Dwww.subispeed.com%26origin%3Dhttps%253A%252F%252Fwww.subispeed.com%252Ff9d00abb9d5af%26relation%3Dparent.parent&color_scheme=dark&container_width=270&font=arial&height=35&href=https%3A%2F%2Fwww.subispeed.com%2F&layout=standard&locale=en_US&sdk=joey&send=false&share=false&show_faces=false&width=270" style="border: none; visibility: visible; width: 270px; height: 60px;" class=""></iframe></span></div>
         <div class="athlete_footer_customer_service">
         </div>
      </div>
   </div>
</div>

<div class="copyright-container">
	<div class="site-width">
		<div class="cc-icons"><img src="https://www.subispeed.com/media/wysiwyg/olegnax/athlete/visa.png" height="33" width="54" alt="Visa">
<img src="https://www.subispeed.com/media/wysiwyg/olegnax/athlete/mastercard.png" height="33" width="54" alt="Mastercard">
<img src="https://www.subispeed.com/media/wysiwyg/olegnax/athlete/amex.png" height="33" width="54" alt="American Express">
<img src="https://www.subispeed.com/media/wysiwyg/olegnax/athlete/discover.png" height="33" width="54" alt="Discover">
<img src="https://www.subispeed.com/media/wysiwyg/olegnax/athlete/paypal.png" height="33" width="54" alt="PayPal"></div>
		<address>© 2014 SubiSpeed.  Powered by JB Autosports.


</address>
	</div>
</div>

<?php if ($this->app->get('DEBUG') && false) { ?>
<div class="footer-bottom col-lg-12 col-md-12 col-sm-12 col-xs-12">
   <div class="c">
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