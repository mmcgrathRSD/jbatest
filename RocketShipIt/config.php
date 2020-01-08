<?php

// Copyright RocketShipIt LLC All Rights Reserved
// For Support email: support@rocketship.it

// Feel free to modify the following defaults:

/*
 * <cfset application.api_ups_license_number = "1C6614376AA2D1B0" />
<cfset application.api_ups_user_id = "rallysprt" />
<cfset application.api_ups_password = "81by5{6t?9" />
<cfset application.api_ups_shipper_number = "YE0536" />
<cfset application.api_ups_shipper_state = "UT" />
<cfset application.api_ups_shipper_zip= "84020" />
<cfset application.api_ups_server = "https://onlinetools.ups.com/ups.app/xml">

<cfset application.api_usps_user_id = "842RALLY7729" />
<cfset application.api_usps_password = "461PB95DI708" />
<cfset application.api_usps_shipper_zip = "84020" />

<cfset application.api_fedex_myKey = "pJItvqa5XOYv3M3S" />
<cfset application.api_fedex_myPassword = "T3ZOHp2iaA6lMXKzuUr2pp8xo" />
<cfset appliaction.api_fedex_myAccountNo = "282153807" />
<cfset application.api_fedex_myMeterNo = "103013239" />
<cfset application.api_fedex_sandbox = "false" />
 */


return array(

    //{{GENERIC
    /**
    * This is used to set generic defaults.  I.e. They are 
    * not carrier-specific.
    *
    * These defaults will be used across all carriers.  They can be 
    * overwritten on the 
    * shipment/package level.
    */
    'generic' => array(


        // 1 for Debug mode, 0 for normal operations
        // This also changes from testing to production mode
        'debugMode' => 0,

        // Default timezone
        // You can find out which timezones are available here: 
        // http://php.net/manual/en/timezones.php
        'timezone' => 'America/Denver',

        // Your company name
        'shipper' => 'Rally Sport Direct',

        // Key shipping contact individual at your company
        'shipContact' => 'Kwin Albert',

        'shipAddr1' => '218 W. 12650 S.',
        'shipAddr2' => '',
        'shipCity' => 'Draper',

        // the two-letter State or Province code
        // ex. MT => Montana, ON => Ontario
        'shipState' => 'UT',

        // The Zip or Postal code
        'shipCode' => '84020',

        // The two-letter country code
        'shipCountry' => 'US',

        // Phone number in this format: 1234567890
        'shipPhone' => '1231231234',
        'toCountry' => '',

        // General currency for things like COD
        'currency' => 'USD',

    ),
    //}}

    //{{FEDEX
    /**
    * This is used to set FedEx specfic defaults.
    *
    * These defaults will be used for FedEx calls only.  They can be 
    * overwritten on the 
    * shipment/package level using the setParameter() function.
    */

    'fedex' => array(

        // Your FedEx developer key
        'key' => 'pJItvqa5XOYv3M3S',

        // Your FedEx developer password
        'password' => 'T3ZOHp2iaA6lMXKzuUr2pp8xo',

        // Your FedEx accountNumber
        'accountNumber' => '282153807',

        // Your FedEx meter number
        'meterNumber' => '103013239',

        // Allowed packaging types:
        // FEDEX_10KG_BOX
        // FEDEX_25KG_BOX
        // FEDEX_BOX
        // FEDEX_ENVELOPE
        // FEDEX_PAK
        // FEDEX_TUBE
        // YOUR_PACKAGING
        'packagingType' => '',

        // The two possible weight units are LB and KG
        'weightUnit' => 'LB',
                
        // The two possible length units are IN and CM
        'lengthUnit' => 'IN',

        // EUROPE_FIRST_INTERNATIONAL_PRIORITY
        // FEDEX_1_DAY_FREIGHT
        // FEDEX_2_DAY
        // FEDEX_2_DAY_FREIGHT
        // FEDEX_3_DAY_FREIGHT
        // FEDEX_EXPRESS_SAVER
        // FEDEX_GROUND
        // FIRST_OVERNIGHT
        // GROUND_HOME_DELIVERY
        // INTERNATIONAL_ECONOMY
        // INTERNATIONAL_ECONOMY_FREIGHT
        // INTERNATIONAL_FIRST
        // INTERNATIONAL_PRIORITY
        // INTERNATIONAL_PRIORITY_FREIGHT
        // PRIORITY_OVERNIGHT
        // SMART_POST
        // STANDARD_OVERNIGHT
        // FEDEX_FREIGHT
        // FEDEX_NATIONAL_FREIGHT
        'service' => 'FEDEX_GROUND',

        // REGULAR_PICKUP
        // REQUEST_COURIER
        // DROP_BOX
        // BUSINESS_SERVICE_CENTER
        // STATION
        'dropoffType' => '',

        // COLLECT
        // RECIPIENT
        // SENDER
        // THIRD_PARTY
        'paymentType' => '',

        // COMMON2D
        // LABEL_DATA_ONLY
        // (Use 'COMMON2D' if you are unsure)
        'labelFormatType' => '',

        // DPL
        // EPL2
        // PDF
        // PNG
        // ZPLII
        'imageType' => '',
                
        // PAPER_4X6
        // PAPER_4X8
        // PAPER_4X9
        // PAPER_7X4.75
        // PAPER_8.5X11_BOTTOM_HALF_LABEL
        // PAPER_8.5X11_TOP_HALF_LABEL
        // STOCK_4X6
        // STOCK_4X6.75_LEADING_DOC_TAB
        // STOCK_4X6.75_TRAILING_DOC_TAB
        // STOCK_4X8
        // STOCK_4X9_LEADING_DOC_TAB
        // STOCK_4X9_TRAILING_DOC_TAB
        'labelStockType' => '',

        // BILL_OF_LADING
        // COD_RETURN_TRACKING_NUMBER
        // CUSTOMER_AUTHORIZATION_NUMBER
        // CUSTOMER_REFERENCE
        // DEPARTMENT
        // FREE_FORM_REFERENCE
        // GROUND_SHIPMENT_ID
        // GROUND_MPS
        // INVOICE
        // PARTNER_CARRIER_NUMBER
        // PART_NUMBER
        // PURCHASE_ORDER
        // RETURN_MATERIALS_AUTHORIZATION
        // TRACKING_CONTROL_NUMBER
        // TRACKING_NUMBER_OR_DOORTAG
        // SHIPPER_REFERENCE
        // STANDARD_MPS
        'trackingIdType' => 'TRACKING_NUMBER_OR_DOORTAG',

        // Currency for Insurance
        'insuredCurrency' => 'USD',
        
        // COD (Collect On Delivery) - YES or NO
        'collectOnDelivery' => 'NO',
        
        // Hold at Location - YES or NO
        'holdAtLocation' => 'NO',
        
        // Saturday Delivery - YES or NO
        'saturdayDelivery' => 'NO',
        
        // ANY
        // CASH
        // GUARANTEED_FUNDS
        'codCollectionType' => 'ANY',

    ),
    //}}

    //{{UPS
    /**
    * This is used to set UPS specfic defaults.
    *
    * These defaults will be used for UPS calls only.  They can be 
    * overwritten on the 
    * shipment/package level using the setParameter() function.
    */
    'ups' => array(

        // Your UPS Developer license
        // your UPS XML Access Key TODO: Insert link to get one
        'license' => '1C6614376AA2D1B0',

        // your UPS Developer username
        // This is tied to all shipments for tracking purposes when
        // tracking by reference values or when viewing shipments
        // on the UPS website.
        'username' => 'rallysprt',

        // your ups Developer password
        'password' => '81by5{6t?9',

        // Make sure addresses are valid before label creation
        // validate, nonvalidate
        'verifyAddress' => 'nonvalidate',

        // The following variables govern the way the system functions
        // Options
        // ZPL - Zebra UPS Thermal Printers
        // EPL - Eltron UPS Thermal Printers
        // GIF - Image based, desktop inkjet printers
        // STARPL
        // SPL
        'labelPrintMethodCode' => 'GIF',

        // Used when printing GIF images
        'httpUserAgent' => '',

        // Only valid option for ZPL, EPL, STARPL, and SPL is 4
        // When using inches use whole numbers only
        'labelHeight' => '',

        // Options are 6 or 8 inches
        'labelWidth' => '',

        // Options
        // GIF - A gif image
        'labelImageFormat' => 'GIF',

        // The following variables are for your UPS account
        // They typically don't change from shipment to shipment, although,
        // you may set any of them directly.
        // Your UPS Account number
        'accountNumber' => 'YE0536',

        // Options
        // 01 - Daily Pickup
        // 03 - Customer Counter
        // 06 - One Time Pickup
        // 07 - On Call Air
        // 11 - Suggested Retail Rates
        // 19 - Letter Center
        // 20 - Air Service Center
        'PickupType' => '01',

        // LBS or KGS
        'weightUnit' => 'LB',

        // IN, or CM
        'lengthUnit' => 'IN',

        // See the ups manual for a list of all currency types
        'insuredCurrency' => 'USD',

        // two-letter country code
        'toCountryCode' => 'US',

        // The following variables set the defaults for individual shipments
        // you may set them here to save time, or you may set them explicitly
        // each time you use the classes.
        'shipmentDescription' => '',

        // Options
        // 01 - UPS Next Day Air
        // 02 - UPS Second Day Air
        // 03 - UPS Ground
        // 07 - UPS Worldwide Express
        // 08 - UPS Worldwide Expedited
        // 11 - UPS Standard
        // 12 - UPS Three-Day Select
        // 13 - Next Day Air Saver 
        // 14 - UPS Next Day Air Early AM
        // 54 - UPS Worldwide Express Plus
        // 59 - UPS Second Day Air AM
        // 65 - UPS Saver
        'service' => '03',

        // Options
        // 01 - UPS Letter
        // 02 - Your Packaging
        // 03 - Tube
        // 04 - PAK
        // 21 - Express Box
        // 24 - 25KG Box
        // 25 - 10KG Box
        // 30 - Pallet
        // 2a - Small Express Box
        // 2b - Medium Express Box
        // 2c - Large Express Box
        'packagingType' => '02',

        'packageDescription' => '',

        // Set '0' for commercial '1' for residential
        'residentialAddressIndicator' => '1',

        // Set '0' for retail rates '1' for negotiated
        // You must turn this on with your UPS account rep
        'negotiatedRates' => '1',

        // Options
        // AJ Accounts Receivable Customer Account
        // AT Appropriation Number
        // BM Bill of Lading Number
        // 9V Collect on Delivery (COD) Number
        // ON Dealer Order Number
        // DP Department Number
        // 3Q Food and Drug Administration (FDA) Product Code
        // IK Invoice Number
        // MK Manifest Key Number
        // MJ Model Number
        // PM Part Number
        // PC Production Code
        // PO Purchase Order Number
        // RQ Purchase Request Number
        // RZ Return Authorization Number
        // SA Salesperson Number
        // SE Serial Number
        // ST Store Number
        // TN Transaction Reference Number
        'referenceCode' => '',

        // Options
        // 2 - UPS Print and Mail (PNM)
        // 3 - UPS Return Service 1-Attempt (RS1)
        // 5 - UPS Return Service 3-Attempt (RS3)
        // 8 - UPS Electronic Return Label (ERL)
        // 9 - UPS Print Return Label (PRL) 
        'returnCode' => '',

        // Options
        // 00 - Rates Associated with Shipper Number
        // 01 - Daily Rates
        // 04 - Retail Rates
        // 53 - Standard List Rates
        'customerClassification' => '00',

    ),
    //}}

    //{{USPS
    /**
    * This is used to set USPS specfic defaults.
    *
    * These defaults will be used for USPS calls only.  They can be 
    * overwritten on the 
    * shipment/package level using the setParameter() function.
    */
    'usps' => array(

        // USPS userID
        // Please note: the USPS test servers only respond to pre-defined requests.
        // For USPS rates you need to call USPS and have them move you to
        // the production servers.
        'userid' => '842RALLY7729',

        // USPS service type
        // FIRST CLASS
        // PRIORITY
        // PRIORITY COMMERCIAL
        // EXPRESS
        // EXPRESS COMMERCIAL
        // EXPRESS SH
        // EXPRESS SH COMMERCIAL
        // EXPRESS HFP
        // EXPRESS HFP
        // COMMERCIAL
        // BPM
        // PARCEL
        // MEDIA
        // LIBRARY
        // ALL
        // ONLINE
        'service' => 'PRIORITY',

        // Values:
        // REGULAR
        // LARGE (Length + girth over 84in under 109in)
        // OVERSIZE (length + girth over 108 under 131)
        'size' => '',

        // FLAT
        // LETTER
        // PARCEL
        // POSTCARD
        'firstClassMailType' => '',

        'imageType' => 'PDF',

    ),

    //}}

    //{{STAMPS
    /**
    * This is used to set Stamps.com specfic defaults.
    *
    * These defaults will be used for Stamps.com calls only.  They can be 
    * overwritten on the 
    * shipment/package level using the setParameter() function.
    */
    'stamps' => array(

        // USPS Stamps.com Credentials
        'username' => '',
        'password' => '',

        // Label Image Type
        //  Zpl
        //  EncryptedPngUrl
        //  PrintOncePdf
        //  Jpg
        //  Epl
        //  Pdf
        //  Gif
        //  Png
        //  Auto
        'imageType' => '',

        'packagingType' => 'Package',

    ),
    //}}

    //{{DHL
    'dhl' => array(

        'siteId' => '',
        'password' => '',
        'accountNumber' => '803921577',

        // The two possible length units are IN and CM
        'lengthUnit' => 'IN',

        // The two possible length units are LB and KG
        'weightUnit' => 'LB',

        // AWBNumber or LPNumber
        'trackingIdType' => 'AWBNumber',

        // EPL2, PDF, ZPL2, LP2
        'labelPrintMethodCode' => 'PDF',

    ),
    //}}

    //{{CANADA
    'canada' => array(

        'username' => '',
        'password' => '',
        'accountNumber' => '',
        'service' => 'DOM.EP',

    ),
    //}}

    //{{PUROLATOR
    'purolator' => array(

        'username' => '',
        'password' => '',
        'accountNumber' => '9999999999',

        // lb or kg
        'weightUnit' => 'lb',

        // DropOff or PreScheduled
        'pickupType' => 'PreScheduled',

        'service' => 'PurolatorExpress',
    ),
    //}}

);
