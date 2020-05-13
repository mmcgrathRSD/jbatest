<?php
namespace JBAShop\Site;

/**
 * Group class is used to keep track of a group of routes with similar aspects (the same controller, the same f3-app and etc)
 */
class Routes extends \Dsc\Routes\Group
{

    /**
     * Initializes all routes for this group
     * NOTE: This method should be overriden by every group
     */
    public function initialize()
    {
        $f3 = \Base::instance();
        
        $this->setDefaults( array(
            'namespace' => '\JBAShop\Site\Controllers',
            'url_prefix' => '' 
        ) );

        /*
         * NEW URLS
         */
        $this->add( '/part/@category/@slug', 'GET', array(
            'controller' => 'Product',
            'action' => 'read'
        ) );
        $this->add( '/fits/@slug/page/@page', 'GET|POST', array(
            'controller' => 'YearMakeModel',
            'action' => 'index'
        ));
        $this->add( '/fits/@ymm/@category/@slug', 'GET|POST', array(
            'controller' => 'Product',
            'action' => 'read'
        ) );
        
        $this->add( '/fits/@slug', 'GET|POST', array(
            'controller' => 'YearMakeModel',
            'action' => 'index'
        ));
        
       
        
        $this->add( '/fits/@slug/@category', 'GET|POST', array(
            'controller' => 'YearMakeModel',
            'action' => 'index'
        ) );
        
        $this->add( '/fits/@slug/@category/page/@page', 'GET', array(
            'controller' => 'YearMakeModel',
            'action' => 'index'
        ) );
        
        
        $this->add( '/scp/*', 'GET|POST', array(
            'controller' => 'Category',
            'action' => 'index'
        ) );
        
        $this->add( '/scp/*/page/@page', 'GET|POST', array(
            'controller' => 'Category',
            'action' => 'index'
        ) );
        
        $this->add( '/scp/*/view-all', 'GET', array(
            'controller' => 'Category',
            'action' => 'viewAll'
        ) );
        
        $this->add( '/scp/*/view-all/page/@page [ajax]', 'GET', array(
            'controller' => 'Category',
            'action' => 'viewAllPaginate'
        ) );
        
        
        $this->add( '/brands', 'GET|POST', array(
            'controller' => 'Manufacturer',
            'action' => 'allBrands'
        ) );
        
        $this->add( '/brand/@slug', 'GET|POST', array(
            'controller' => 'Manufacturer',
            'action' => 'index'
        ) );
        
        $this->add( '/brand/@slug/page/@page', 'GET|POST', array(
            'controller' => 'Manufacturer',
            'action' => 'index'
        ) );
        
        $this->add( '/brand/@slug/@category', 'GET|POST', array(
            'controller' => 'Manufacturer',
            'action' => 'index'
        ) );
        
        $this->add( '/brand/@slug/@category/page/@page', 'GET|POST', array(
            'controller' => 'Manufacturer',
            'action' => 'index'
        ) );
        
        
        /*
         * OLD URLS
         */
        
        
        
        $this->add('/shop/current-date', 'GET', array(
            'controller' => 'Utility',
            'action' => 'currentDateUTC'
        ));

        
       
        $this->add( '/shop', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
        $this->add( '/shop/page/@page', 'GET', array(
            'controller' => 'Home',
            'action' => 'index' 
        ) );
       
        
        
        //[ajax]
        $this->add( '/shop/usercontent/@objectid/@vote', 'GET|POST', array(
        		'controller' => 'UserContent',
        		'action' => 'votingSaveVote'
        ) );
        $this->add( '/shop/usercontent/youtube/@videoid', 'GET|POST', array(
            'controller' => 'UserContent',
            'action' => 'youtubeThumbNail'
        ) );
        //[ajax]
        $this->add( '/shop/usercontent/@objectid/@answer_id/@vote', 'GET|POST', array(
        		'controller' => 'UserContent',
        		'action' => 'saveAnswerVote'
        ) );
        
        $this->add( '/shop/product/@slug/reviews', 'GET|POST', array(
        		'controller' => 'UserContent',
        		'action' => 'reviews'
        ) );
        $this->add( '/part/@category/@slug/reviews/page/@page', 'GET|POST', array(
            'controller' => 'UserContent',
            'action' => 'reviews'
        ) );
        $this->add( '/shop/product/@slug/questions', 'GET|POST', array(
        		'controller' => 'UserContent',
        		'action' => 'questions'
        ) );

        $this->add( '/shop/product/@slug/questions/page/@page', 'GET|POST', array(
            'controller' => 'UserContent',
            'action' => 'questions'
        ) );
        $this->add( '/part/@category/@slug/questions/page/@page', 'GET|POST', array(
            'controller' => 'UserContent',
            'action' => 'questions'
        ) );
        //usercontent
        $this->add( '/shop/product/@slug/create/@type', 'GET', array(
        		'controller' => 'UserContent',
        		'action' => 'dispatcher'
        ) );
        $this->add( '/shop/product/@slug/create/@type', 'POST', array(
        		'controller' => 'UserContent',
        		'action' => 'process'
        ) );
        //usercontent
        $this->add( '/part/@category/@slug/create/@type', 'GET', array(
            'controller' => 'UserContent',
            'action' => 'dispatcher'
        ) );
        $this->add( '/part/@category/@slug/create/@type', 'POST', array(
            'controller' => 'UserContent',
            'action' => 'process'
        ) );
        
        $this->add( '/shop/product/@slug/create/answer/@questionid', 'POST', array(
        		'controller' => 'UserContent',
        		'action' => 'processAnswer'
        ) );
        
        $this->add( '/shop/product/@slug/create/answer/@questionid', 'GET', array(
        		'controller' => 'UserContent',
        		'action' => 'showAnswer'
        ) );
        
        $this->add( '/shop/product/@slug/notifications/@type', 'POST', array(
        		'controller' => 'Notifications',
        		'action' => 'receive'
        ) );
        $this->add( '/shop/product/@slug/notifications/@id/remove', 'POST', array(
        		'controller' => 'Notifications',
        		'action' => 'remove'
        ) );
        
        $this->add( '/shop/compare/*', 'GET|POST', array(
        		'controller' => 'Compare',
        		'action' => 'index'
        ) );
        
        $this->add( '/shop/brand/@slug/view-all', 'GET', array(
        		'controller' => 'Manufacturer',
        		'action' => 'viewAll'
        ) );
        
        $this->add( '/shop/brand/@slug/view-all/page/@page [ajax]', 'GET', array(
        		'controller' => 'Manufacturer',
        		'action' => 'viewAllPaginate'
        ) );

        $this->add( '/shop/collection/@slug', 'GET|POST', array(
            'controller' => 'Collection',
            'action' => 'index'
        ) );
        
        $this->add( '/shop/cart', 'GET', array(
            'controller' => 'Cart',
            'action' => 'read' 
        ) );
        
        $this->add( '/shop/cart/express', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'express'
        ) );

        $this->add( '/shop/getshippingrates', 'GET|POST', array(
        		'controller' => 'Cart',
        		'action' => 'getShippingRates'
        ) );
        
        $this->add( '/shop/cart/qty', 'GET', array(
        		'controller' => 'Cart',
        		'action' => 'qty'
        ) );
        
        $this->add( '/shop/cart/estimateshipping', 'GET|POST', array(
        		'controller' => 'Cart',
        		'action' => 'estimateshipping'
        ) );

        $this->add( '/shop/shipping/quote', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'getShippingRatesSplit'
        ) );

        $this->add( '/shop/shipping/update-method', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'updateShippingMethod'
        ) );

        $this->add( '/shop/cart/lightbox', 'GET', array(
        		'controller' => 'Cart',
        		'action' => 'cartLightbox'
        ) );
        $this->add( '/shop/cart/lightbox/@option', 'GET', array(
        		'controller' => 'Cart',
        		'action' => 'cartLightbox'
        ) );

        $this->add( '/shop/cart/@id', 'GET', array(
        		'controller' => 'Cart',
        		'action' => 'read'
        ) );
        
        $this->add( '/shop/cart/add', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'add' 
        ) );

        $this->add('/shop/cart/quote/@id', 'GET', array(
            'controller' => 'Cart',
            'action' => 'createByQuote'
        ));

        $this->add( '/shop/cart/remove/@cartitem_hash', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'remove'
        ) );
        
        $this->add( '/shop/cart/movetowishlist/@cartitem_hash/@variant_id', 'GET|POST', array(
        		'controller' => 'Cart',
        		'action' => 'moveToWishlist'
        ) );
        
        $this->add( '/shop/cart/updatequantities/@cartitem_hash/@cartitem_quantity', 'POST', array(
            'controller' => 'Cart',
            'action' => 'updateQuantities' 
        ) );
        
        $this->add( '/shop/cart/estimateshipping', 'POST', array(
        		'controller' => 'Cart',
        		'action' => 'estimateShipping'
        ) );
        
        $this->add( '/shop/cart/addCoupon', 'POST', array(
            'controller' => 'Cart',
            'action' => 'addCoupon'
        ) );    

        $this->app->route('GET /shop/cart/addCoupon',
		    function() {
		        $this->app->reroute('/shop/checkout');
		    }
		);
        
        $this->add( '/shop/cart/removeCoupon/@code', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'removeCoupon'
        ) );
        
        $this->add( '/shop/cart/addGiftCard', 'POST', array(
            'controller' => 'Cart',
            'action' => 'addGiftCard'
        ) );
        
        $this->add( '/shop/cart/removeGiftCard/@code', 'GET|POST', array(
            'controller' => 'Cart',
            'action' => 'removeGiftCard'
        ) );
        

        

        $this->app->route('GET|POST /shopping_cart_process_update_payment.cfm',
        		function() {
        			if(!empty($_GET['order_number'])) {
        				$orderid = $_GET['order_number'];
        				
        				$order = (new \JBAShop\Models\Orders)->setCondition('number', $orderid)->getItem();
        				 
        				if($order->id) {
        					$this->app->reroute('/shop/order/updatepayment/'.$order->id);
        				} else {
        					\Base::instance()->reroute('/shop/account');
        				}
        			} else {
        				\Base::instance()->reroute('/shop/account');
        			}
        			
        		}
        );
        
        $this->add('/shop/validation/address', 'GET|POST', array(
        		'controller' => 'Checkout',
        		'action' => 'validationAddress'
        ));
        
        $this->add( '/shop/address/countries [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'countries' 
        ) );
        
        $this->add( '/shop/address/regions/@country_isocode2 [ajax]', 'GET', array(
            'controller' => 'Address',
            'action' => 'regions' 
        ) );
        
        $this->add( '/shop/address/validate [ajax]', 'GET|POST', array(
            'controller' => 'Address',
            'action' => 'validate' 
        ) );

        $this->add( '/shop/orders', 'GET|POST', array(
            'controller' => 'Order',
            'action' => 'index' 
        ) );
        
        $this->add( '/shop/orders/page/@page', 'GET', array(
            'controller' => 'Order',
            'action' => 'index' 
        ) );
        
        $this->add( '/shop/order/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'read' 
        ) );
        
        $this->add( '/shop/order/updatepayment/@id', 'GET', array(
        		'controller' => 'Order',
        		'action' => 'updatePayment'
        ) );
        $this->add( '/shop/order/updatepayment/@id', 'POST', array(
        		'controller' => 'Order',
        		'action' => 'processUpdatePayment'
        ) );
        
        
        
        $this->add( '/shop/order/manualorderupdate/@id', 'GET', array(
        		'controller' => 'Order',
        		'action' => 'manualTokenCreate'
        ) );
        
        $this->add( '/shop/order/manualorderupdate/@id', 'POST', array(
        		'controller' => 'Order',
        		'action' => 'processManualTokenCreate'
        ) );
        
        $f3->route( 'GET /shop/order/print/@id', function ( $f3 )
        {
            $f3->set( 'print', true );
            (new \Shop\Site\Controllers\Order())->read();
        } );
        
        $this->add('/shop/account', 'GET', array(
            'controller' => 'Account',
            'action' => 'index' 
        ) );
        
        $this->add('/shop/account/information', 'GET', array(
            'controller' => 'Account',
            'action' => 'information' 
        ) );

        $this->add('/shop/account/orders', 'GET', array(
            'controller' => 'Account',
            'action' => 'orders' 
        ) );

        $this->add( '/shop/account/savepreferences', 'POST', array(
        		'controller' => 'Account',
        		'action' => 'savePreferences'
        ) );
        
        $this->add( '/shop/account/edit', 'POST', array(
        		'controller' => 'Account',
        		'action' => 'editInline'
        ) );
        
        $this->add( '/shop/account/addresses', 'GET|POST', array(
            'controller' => 'Address',
            'action' => 'index'
        ) );

        $this->add( '/shop/account/addresses/page/@page', 'GET', array(
            'controller' => 'Address',
            'action' => 'index'
        ) );
        
        $this->addCrudItem('Address', array(
            'namespace' => '\Shop\Site\Controllers',
            'url_prefix' => '/account/addresses'
        ));

        $this->add('/shop/account/addresses/edit/@id', 'GET', [
            'controller' => 'Account',
            'action' => 'index'
        ]);

        $this->add('/shop/account/address/remove/@id', 'GET', array(
            'controller' => 'Address',
            'action' => 'remove'
        ));
        
        $this->add( '/shop/account/address/setprimarybilling/@id', 'GET', array(
            'controller' => 'Address',
            'action' => 'setPrimaryBilling'
        ) );

        $this->add( '/shop/account/address/setprimaryshipping/@id', 'GET', array(
            'controller' => 'Address',
            'action' => 'setPrimaryShipping'
        ) );
        
        
         $this->add( '/shop/giftcards/balance', 'GET', array(
        		'controller' => 'OrderedGiftCard',
        		'action' => 'checkBalance'
        ) );
        
        $this->add( '/shop/giftcards/balance', 'POST', array(
        		'controller' => 'OrderedGiftCard',
        		'action' => 'doCheckBalance'
        ) );

        $this->add( '/shop/giftcards/check-balance [ajax]', 'POST', array(
                'controller' => 'OrderedGiftCard',
                'action' => 'doCheckBalanceAjax'
        ) );
        
        
        $this->add( '/shop/giftcard/@id/@token', 'GET', array(
            'controller' => 'OrderedGiftCard',
            'action' => 'read'
        ) );
        
        $f3->route( 'GET /shop/giftcard/print/@id/@token', function ( $f3 )
        {
            $f3->set( 'print', true );
            (new \Shop\Site\Controllers\OrderedGiftCard())->read();
        } );        

        $this->add( '/shop/giftcard/email/@id/@token', 'POST', array(
            'controller' => 'OrderedGiftCard',
            'action' => 'email'
        ) );
        
        
        $this->add( '/shop/giftcard/balance', 'GET', array(
        		'controller' => 'Giftcards',
        		'action' => 'index'
        ) );
        
        $this->add( '/shop/giftcard/balance', 'POST', array(
        		'controller' => 'Giftcards',
        		'action' => 'checkCard'
        ) );
        
        
        $this->add( '/shop/customer/check-campaigns', 'GET', array(
            'controller' => 'Customer',
            'action' => 'checkCampaigns'
        ) );
        
        if ($this->app->get('DEBUG') || $this->input->get('refresh', 0, 'int'))
        {
            $this->add( '/google-merchant/products.xml', 'GET', array(
                'controller' => 'GoogleMerchant',
                'action' => 'productsXml'
            ) );
        }
        else
        {
            $cache_period = 3600*24;
        
            $this->app->route('GET /shop/google-merchant/products.xml', '\Shop\Site\Controllers\GoogleMerchant->productsXml', $cache_period);
        }

        if ($this->app->get('DEBUG') || $this->input->get('refresh', 0, 'int'))
        {
            $this->add( '/pepperjam/products.txt', 'GET', array(
                'controller' => 'PepperJam',
                'action' => 'productsTxt'
            ) );
        }
        else
        {
            $cache_period = 3600*24;
        
            $this->app->route('GET /shop/pepperjam/products.txt', '\Shop\Site\Controllers\PepperJam->productsTxt', $cache_period);
        }
        
        $this->add( '/shop/product/@slug/review', 'POST', array(
            'controller' => 'ProductReviews',
            'action' => 'create'
        ) );
        
        $this->add( '/shop/product/@slug/reviews/page/@page', 'GET|POST', array(
            'controller' => 'UserContent',
            'action' => 'reviews'
        ) );
        $this->add( '/shop/product/stock/@variant_id', 'GET|POST', array(
        		'controller' => 'Product',
        		'action' => 'stockStatus'
        ) );

        $this->router->route( 'GET /shop/product/@slug/reviews/page', function($app, $params){
            $app->reroute('/shop/product/' . $params['slug'], true);
        } );
                
        $this->router->route( 'GET /shop/product/@slug/reviews/page/@page', function($app, $params){
            $app->reroute('/shop/product/' . $params['slug'], true);
        } );

        $this->add( '/shop/account/product-reviews', 'GET|POST', array(
            'controller' => 'Account',
            'action' => 'productReviews'
        ) );
        
        $this->add( '/shop/account/product-reviews/page/@page', 'GET', array(
            'controller' => 'Account',
            'action' => 'productReviews'
        ) );
                
        $this->add( '/shop/product/@slug/reviews/images/@skip', 'GET', array(
            'controller' => 'ProductReviews',
            'action' => 'customerImage'
        ) );

        $this->router->route('GET /shop/product-review/@oldid/@oldslug', function($app, $params) {
            // can't link directly to reviews yet

            $product = (new \JBAShop\Models\Products())
                ->setCondition('old.slug', $params['oldslug'])
                ->getItem()
            ;

            if (!empty($product)) {
                $app->reroute("/shop/product/{$product->slug}/reviews", true);
            } else {
                $app->reroute('/', true);
            }
        });
        
            $this->add( '/shop-by-category', 'GET|POST', array(
                'controller' => 'Category',
                'action' => 'viewCategories'
            ) );
   }
}
