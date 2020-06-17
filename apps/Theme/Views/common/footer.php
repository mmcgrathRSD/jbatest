<?php if($checkoutmode == 0) : ?>
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
                     <li><a href="/shop/giftcards/balance">Check Gift Card</a>
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
                  <a title="Facebook" class="social-icon icon-facebook " href="<?php echo \Base::instance()->get('social.facebook_link'); ?>" target="_social"></a>
                  <a title="Twitter" class="social-icon icon-twitter " href="<?php echo \Base::instance()->get('social.twitter_link'); ?>" target="_social"></a>
                  <a title="Mail" class="social-icon icon-mail " href="/contacts/" target="_social"></a>
                  <a title="Youtube" class="social-icon icon-youtube " href="<?php echo \Base::instance()->get('social.youtube_link'); ?>" target="_social"></a>
                  <a title="Instagram" class="social-icon icon-instagram " href="<?php echo \Base::instance()->get('social.instagram_link'); ?>" target="_social"></a>
               </div>
            </div>
         </div>
         <div class="facebook-block">
            <div id="fb-root"></div>
            <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v7.0&appId=204930700266539&autoLogAppEvents=1" nonce="vefcQnsN"></script>
            <div class="fb-like" data-href="<?php echo \Base::instance()->get('meta.site_url'); ?>" data-width="" data-layout="standard" data-action="like" data-size="small" data-share="false"></div>
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
         <address>© <?php echo \Base::instance()->get('meta.copyright_date') . ' ' . \Base::instance()->get('meta.retailer'); ?>.  Powered by JB Autosports.


   </address>
      </div>
   </div>
<?php endif; ?>

<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://" : "http://");
document.write(unescape("%3Cscript async src='" + pkBaseURL + "s3.amazonaws.com/aascript/stage.subispeed.com/abandonaid-stage.subispeed.com.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<?php if ($this->app->get('DEBUG')) { ?>
<div class="footer-bottom col-lg-12 col-md-12 col-sm-12 col-xs-12" style="
    background-color: white;
    text-align: left;
">
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