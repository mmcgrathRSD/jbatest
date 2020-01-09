<?php
namespace RallyShop\Admin;

class Routes extends \Dsc\Routes\Group
{

    public function initialize()
    {
        $f3 = \Base::instance();
        
        $this->setDefaults(array(
            'namespace' => '\RallyShop\Admin\Controllers',
            'url_prefix' => '/admin/shop'
        ));
        
        $this->addSettingsRoutes();
        
        /*
         * CUSTOM FOR RALLYSPORT
         */
        $this->add('/followup/invoices', 'GET', array(
            'controller' => 'Followups',
            'action' => 'sendInvoiceFollowups'
        ));

        $this->addCrudGroup( 'YearMakeModels', 'YearMakeModel' );

     
        
        $this->add( '/yearmakemodels/imagebulk', 'POST', array(
        		'controller' => 'YearMakeModels',
        		'action' => 'uploadImageBulk'
        ) );
        
        $this->add('/checkouttracking', 'GET|POST', array(
        		'controller' => 'CheckoutTracking',
        		'action' => 'index'
        ));
        $this->add('/checkouttracking/page/@page', 'GET|POST', array(
        		'controller' => 'CheckoutTracking',
        		'action' => 'index'
        ));
        
        $this->add('/checkouttracking/@user_id', 'GET', array(
        		'controller' => 'CheckoutTracking',
        		'action' => 'single'
        ));
        
        $this->add('/checkouttracking/update/@user_id', 'GET', array(
        		'controller' => 'CheckoutTracking',
        		'action' => 'fragment'
        ));
        
        
        $this->add('/import', 'GET', array(
        		'controller' => 'Import',
        		'action' => 'index'
        ));
        
        $this->add('/import/@importer', 'POST', array(
        		'controller' => 'Import',
        		'action' => 'doImport'
        ));
        
        $this->add('/ymm/forSelection [ajax]', 'GET|POST', array(
        		'controller' => 'YearMakeModels',
        		'action' => 'forSelection'
        ));
		
        
        $this->add('/ymm/forSelectionDistinct [ajax]', 'GET|POST', array(
                'controller' => 'YearMakeModels',
                'action' => 'forSelectionDistinct'
        ));
        
        /*$this->add('/ymm/filterView [ajax]', 'POST', array(
                'controller' => 'YearMakeModels',
                'action' => 'filterYmmAjax'
        ));*/
        
        $this->add('/products/forSelectionYmm [ajax]', 'GET|POST', array(
        		'controller' => 'Products',
        		'action' => 'forSelectionYmm'
        ));
        $this->add('/product/edit/@id/cloudinary/getimages', 'GET|POST', array(
        		'controller' => 'Product',
        		'action' => 'getCloudinaryImages'
        ));
        $this->add('/product/edit/@id/upload-install-instructions [ajax]', 'POST', array(
            'controller' => 'Product',
            'action' => 'uploadInstallInstructions'
        ));
        $this->add('/product/sync/@id [ajax]', 'GET|POST', array(
            'controller' => 'Product',
            'action' => 'syncProduct'
        ));
        
  
        
        $this->add('/ymm/addToProduct [ajax]', 'POST', array(
                'controller' => 'YearMakeModel',
                'action' => 'addToProduct'
        ));
        
        $this->add('/ymm/createAndAddToProduct [ajax]', 'POST', array(
                'controller' => 'YearMakeModel',
                'action' => 'createAndAddToProduct'
        ));

        /*
         * FROM CORE OVERRIDES
         */
        
        $this->add( '/order/flow/@step/@userid', 'GET|POST', array(
        		'controller' => 'OrderFlow',
        		'action' => 'steps'
        ) );
        
        $this->addCrudGroup('Orders', 'Order');
        
        $this->add('/order/generatexml/@id', 'GET', array(
        		'controller' => 'Order',
        		'action' => 'generateXML'
        ));
        
        $this->add('/order/fulfill/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'fulfill'
        ));
        
        $this->add('/order/close/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'close'
        ));
        
        $this->add('/order/repack/@id', 'GET', array(
        		'controller' => 'Order',
        		'action' => 'rePack'
        ));
        
        $this->add('/order/cancel/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'cancel'
        ));
        
        $this->add('/order/open/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'open'
        ));
        
        $this->add('/order/fulfill-giftcards/@id', 'GET', array(
            'controller' => 'Order',
            'action' => 'fulfillGiftCards'
        ));
        
       
        $this->add('/products/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Products',
            'action' => 'forSelection'
        ));
        $this->add('/product/update-ymm-notes/@id [ajax]', 'POST', array(
            'controller' => 'Products',
            'action' => 'updateYMMNotes'
        ));
        
        $this->addCrudGroup('Manufacturers', 'Manufacturer', array(
            'datatable_links' => true,
            'get_parent_link' => true
        ));
      
        
        $this->add('/categories/checkboxes [ajax]', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Categories',
            'action' => 'getCheckboxes'
        ));
        $this->add('/categories/forSelection [ajax]', 'GET|POST', array(
                'controller' => 'Categories',
                'action' => 'forSelection'
        ));
        
        $this->add('/categories/google-merchant/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Categories',
            'action' => 'gmTaxonomyForSelection'
        ));        
        
        $this->add('/manufacturers/checkboxes [ajax]', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Manufacturers',
            'action' => 'getCheckboxes'
        ));
        
        $this->addCrudGroup('Countries', 'Country');
        $this->add('/countries/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Countries',
            'action' => 'forSelection'
        ));
        
        $this->add('/countries/moveUp/@id', 'GET', array(
            'controller' => 'Countries',
            'action' => 'MoveUp'
        ));
        
        $this->add('/countries/moveDown/@id', 'GET', array(
            'controller' => 'Countries',
            'action' => 'MoveDown'
        ));
        
        $this->addChangeStateListRoutes('Countries', '/countries');
        
        $this->addCrudGroup('Regions', 'Region');
        $this->add('/regions/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Regions',
            'action' => 'forSelection'
        ));
        
        $this->addCrudGroup('Coupons', 'Coupon');
        
        $this->addCrudGroup('Tags', 'Tag');
        
        $this->addCrudGroup('GiftCards', 'GiftCard');
        
        $this->addCrudGroup('OrderedGiftCards', 'OrderedGiftCard', array(
            'url_prefix' => '/orders/giftcards'
        ), array(
            'url_prefix' => '/orders/giftcard'
        ));
        
        $f3->route('GET /admin/shop/uniqueid', function ()
        {
            echo (string) new \MongoDB\BSON\ObjectID();
        });
        
        $this->add('/coupons/forSelection [ajax]', 'GET|POST', array(
            'controller' => 'Coupons',
            'action' => 'forSelection'
        ));
        
        $this->addCrudGroup('Credits', 'Credit');
        
        $this->add('/credit/issue/@id', 'GET', array(
            'controller' => 'Credit',
            'action' => 'issue'
        ));
        
        $this->add('/credit/revoke/@id', 'GET', array(
            'controller' => 'Credit',
            'action' => 'revoke'
        ));
        
       
        
        $this->add('/coupon/@id/codes', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Coupon',
            'action' => 'displayCodes'
        ));
        
        
        $this->add('/groupitems/@id/remove/@groupitemid', 'GET|POST', array(
        		'controller' => 'GroupItem',
        		'action' => 'removeItemFromGroup'
        ));
        
        $this->add('/groupitems/@id/add', 'GET|POST', array(
        		'controller' => 'GroupItem',
        		'action' => 'addItemToGroup'
        ));
        
        
        
        $this->add('/coupon/@id/codes/page/@page', array(
            'GET',
            'POST'
        ), array(
            'controller' => 'Coupon',
            'action' => 'displayCodes'
        ));
        
        $this->add('/coupon/@id/codes/generate', 'POST', array(
            'controller' => 'Coupon',
            'action' => 'generateCodes'
        ));
        
        $this->add('/coupon/@id/codes/download', 'GET', array(
            'controller' => 'Coupon',
            'action' => 'downloadCodes'
        ));
        
        $this->add('/coupon/@id/code/@code/delete', 'GET', array(
            'controller' => 'Coupon',
            'action' => 'deleteCode'
        ));
        
        $this->add('/reports', 'GET', array(
            'controller' => 'Reports',
            'action' => 'index'
        ));
        
        $this->add('/reports/@slug', 'GET|POST', array(
            'controller' => 'Reports',
            'action' => 'read'
        ));
        
        $this->add('/reports/@slug/page/@page', 'GET|POST', array(
            'controller' => 'Reports',
            'action' => 'read'
        ));

        $this->addCrudGroup('Customers', 'Customer');
        
        $this->add('/customer/refreshtotals/@id', 'GET', array(
            'controller' => 'Customer',
            'action' => 'refreshTotals'
        ));        
        
        $this->addCrudGroup('Campaigns', 'Campaign');
        
        $this->add('/shipping-methods', 'GET|POST', array(
            'controller' => 'Settings',
            'action' => 'shippingMethods'
        ));
    
        $this->add('/payment-methods', 'GET', array(
            'controller' => 'PaymentMethods',
            'action' => 'index'
        ));
        
        $this->add('/payment-method/select', 'GET', array(
            'controller' => 'PaymentMethods',
            'action' => 'select'
        ));        
        
        $this->add('/payment-method/edit/@id', 'GET', array(
            'controller' => 'PaymentMethods',
            'action' => 'edit'
        ));
        
        $this->add('/payment-method/edit/@id', 'POST', array(
            'controller' => 'PaymentMethods',
            'action' => 'update'
        ));        
        
        $this->add('/settings/notifications', 'GET|POST', array(
            'controller' => 'Settings',
            'action' => 'notifications'
        ));
        
        $this->add('/settings/feeds', 'GET|POST', array(
            'controller' => 'Settings',
            'action' => 'feeds'
        ));
        
        $this->add('/settings/currencies', 'GET|POST', array(
            'controller' => 'Settings',
            'action' => 'currencies'
        ));        

        $this->addCrudGroup('OrderFailures', 'OrderFailure');
                
        $this->addCrudGroup('Wishlists', 'Wishlist');
        
        $this->add( '/export', 'GET', array(
            'controller' => 'Export',
            'action' => 'index'
        ) );
        
        $this->add( '/export/@task', 'GET', array(
            'controller' => 'Export',
            'action' => '@task'
        ) );
        
        $this->add('/assets/massupload', 'GET', array(
        		'controller' => 'Assets',
        		'action' => 'index'
        ));

        $this->add('/assets/massupload/completeupload [ajax]', 'POST', array(
            'controller' => 'Assets',
            'action' => 'completeUpload'
        ));
    }
}
