<?php
namespace RallyShop\Models\Importers;

class Orders extends \RallyShop\Models\Orders   {

	const PRIMARYKEY = 'gp.customer_number';
	const DEFAULTCREATEACTION = 'none';
	const DEBUGGING = false;


	public function checkRequiredFields($data) {

		$requiredFields = [];
		$requiredFields[] = self::PRIMARYKEY;
		$requiredFields[] = 'email';

	}

	public static function log($message, $priority = 'INFO', $category = 'General') {
	    if(self::DEBUGGING) {
	        \Dsc\Mongo\Collections\Logs::add( $message, 'ORDERSYNC', 'SYNCING' );
	    }
	}


	public static function mapDataToMongoKeys($data) {

		$map = [];
		//datafield / mongo field
		$map['orderNumber'] = 'number';
		$map['orderOrderNumber'] = 'number';
		$map['orderStatus'] = 'status';
		$map['orderContactName'] = 'contact_name';
        $map['orderEmailOverride'] = 'user_email';

		$map['originalOrderNumber'] = 'original_order_number';
		$map['orderPaymentTerms'] = 'payment_terms';
		$map['orderPriceLevel'] = 'price_level';
		$map['orderSalesRepName'] = 'sales_rep_name';
		$map['orderSalesRepNumber'] = 'sales_rep_number';
		$map['orderSalesTerritory'] = 'sales_territory';
		$map['orderPoNumber'] = 'po_number';
		$map['orderPaymentsReceived'] = 'payments_received_total';


		$map['orderActualShipDate'] = 'shipments_shipdate';
		$map['orderShipComplete'] = 'shipments_ship_complete';


		$map['orderProcessState'] = 'process_status';
		$map['orderGrandTotal'] = 'grand_total';
		$map['orderSubTotal'] = 'sub_total';
		$map['orderTaxTotal'] = 'tax_total';
		$map['orderMiscTotal'] = 'misc_total';
		$map['orderShippingTotal'] = 'shipping_total';
		$map['orderDiscountTotal'] = 'discount_total';
		$map['orderCreditTotal'] = 'credit_total';
		$map['orderGiftCardTotal'] = 'giftcard_total';

		$map['orderShippingMethodName'] = 'shipping_method.name';
		$map['orderShippingId'] = 'shipping_method.id';
		$map['orderShippingPrice'] = 'shipping_method.price';


		$map['orderShippingAddressName'] = 'shipping_address.name';
		$map['orderShippingAddressLineOne'] = 'shipping_address.line_1';
		$map['orderShippingAddressLineTwo'] = 'shipping_address.line_2';
		$map['orderShippingAddressCountry'] = 'shipping_address.country';
		$map['orderShippingAddressPostalCode'] = 'shipping_address.postal_code';
		$map['orderShippingAddressCity'] = 'shipping_address.city';
		$map['orderShippingAddressRegion'] = 'shipping_address.region';
		$map['orderShippingAddressPhoneNumber'] = 'shipping_address.phone_number';

		$mongoMapper = array();
		foreach ($map as $key => $value) {
			if(!empty($data[$key])) {
				$mongoMapper[$value] = $data[$key];
			}
		}
        
		
		static::setSalesChannelFromData($mongoMapper);
		
		
		

		//HANDLE SPECIAL CASES LIKE TIMESTAMPS
		if(!empty($data['orderCreationDate'])) {
			$mongoMapper['metadata.created'] = \Dsc\Mongo\Metastamp::getDate($data['orderCreationDate']);
		}



		return $mongoMapper;


	}
    
	public static function setSalesChannelFromData($mongoMapper) {
	    //if price level  has whole set the wholesale sales channel
	    if(strpos(strtolower($mongoMapper['price_level']),'whole') !== false) {
	       return  static::setSalesChannel('wholesale');
	    }
	    if(strpos(strtolower($mongoMapper['payment_terms']),'net') !== false) {
	        return  static::setSalesChannel('wholesale');
	    }
	    return  static::setSalesChannel('retail');
	}
    
	public static function setSalesChannel($type = 'retail') {
	    if($type == 'wholesale') {
	     return  \Shop\Models\SalesChannels::setSalesChannel('rallysport-dealer-portal');
	    } else {
	     return  \Shop\Models\SalesChannels::setSalesChannel('rallysport-usa');
	    }
	
	}

	/*
	 * Handles ORDER SYNCING
	 *
	 */
	public static function syncSalesOrder(array $customerData, array $order, array $items, array $tracking) {

	    try {

	    if(empty($customerData['customerNumber'])) {
	        throw new \Exception('Customer ID required');
	    }
            
	    /*
	     * check to see if this sales order is for a wholesale customer
	     */
	    
	    
	    
	    /*
	     * CHECK FOR CUSTOMER
	     */
	        $customer = (new \RallyShop\Models\Customers)->setState('filter.customer_number', $customerData['customerNumber'])->getItem();

	        
	        
	        
	        
	    static::log('1: Customer Check');


	    if(empty($customer->id)) {
	      //IF THE CUSTOMER IS EMPTY CREATE IT
	      $customer = \RallyShop\Models\Importers\Customers::sync($customerData);

	      if(empty($customer)) {
	          throw new \Exception('Customer Not Created');
	      }
	      static::log('2: Customer Created');
	    }

	    $orderFields = static::mapDataToMongoKeys($order);


	    static::log('3: MONGO KEYS');
	    /*
	     * FIND THE ORDER BY ID
	     */
	    $order = (new \RallyShop\Models\Orders)->setCondition('number', $orderFields['number'])->getItem();


	    $customer = (new \RallyShop\Models\Customers)->setState('filter.customer_number', $customerData['customerNumber'])->getItem();


	    if(!empty($order->id)) {
	      static::log('4: UPDATING ORDER');

	      return static::updateSalesOrder($customer, $order, $orderFields, $items, $tracking);
	    } else {
	      static::log('4: CREATING ORDER');
	      return static::createSalesOrder($customer, $orderFields, $items, $tracking);

	    }

	    } catch (\Exception $e) {

	        throw new \Exception( $e->getMessage());
	    }

	}

	/*
	 * UPDATE A SALES ORDER
	 */
	protected static function updateSalesOrder( \RallyShop\Models\Customers $customer, \RallyShop\Models\Orders $order, array $orderFields, array $items, array $tracking) {

	    /*
	     * First lets update all the headers feilds most likely all the fields didn't change, but for now I think it is simplier to just update them to make sure we don't miss them
	     */
	    static::log('5: Setting Update Fields');
	    foreach ($orderFields as $key => $value) {
	        $order->set($key, $value);
	    }

	    static::log('6: LOOPING ITEMS');
        $orderItems = [];
        foreach($items as $item ) {
            $orderItems[] = static::makeCartItem($item, $order->number);
        }

	    $order->set('items', $orderItems);

	    $trackingNumbers = [];
	    foreach($tracking as $key => $value ) {
	        $trackingNumbers[] = trim($value);
	    }

	    $order->set('tracking_numbers', $trackingNumbers);

	    $order->existsingp = true;
	    static::log('7: STORING');



	    $order->trackChanges()->save();



	    return $order;
	}

	protected static  function makeCartItem($item, $number = '') {
		$modelNumber = strtoupper($item['modelNumber']);

	    $product = (new \RallyShop\Models\Products)->setCondition('tracking.model_number', $modelNumber)->getItem();

		$variant_id = null;

		if (!empty($product)) {
			$variant_id = $product->{'variants.0.id'};
		}

	    if (!empty($variant_id)) {
			$cartItem = \RallyShop\Models\Carts::createItem( $variant_id, $product);
			$cartItem->price = $item['unitPrice'];
			$cartItem->quantity = $item['quantity'];

            if (!empty($item['email'])) {
                $cartItem->email = $item['email'];
            }
	    } else {
			\Dsc\System::instance()->get('mailer')->send('deverrors@rallysportdirect.com', 'Order Sync Error', ["Failed to find {$modelNumber}.  $number"]);
	        throw new \Exception('Could not find product or variant for ' . $modelNumber);
	    }

	    return $cartItem->cast();
	}

	protected static function createSalesOrder(\RallyShop\Models\Customers $customer, array $orderFields, array $items, array $tracking) {

	    //TODO SHOULD WE FAIL IF ORDERNUMBER

	    $order = (new \RallyShop\Models\Orders);

	    $customer = $customer->ensureValidCustomer()->reload();
	    static::log('5: Validating Customer');
	    $order->customer = $customer->cast();
	    $order->customer_name = $customer->fullName();
	    $order->user_id = $customer->id;
	    $order->xmlgenerated = time();
	    $order->existsingp = true;
	    //MAP THE USER
	    $real_email = $customer->email(true);
	    if ($real_email != $order->user_email) {
	        $order->user_email = $real_email;
	    }

	    static::log('5: Setting Update Fields');
	   foreach ($orderFields as $key => $value) {
	       if($value && $value) {
	           $order->set($key, $value);
	       }

	    }

	    $orderItems = [];
	    foreach($items as $item ) {
	        $orderItems[] = static::makeCartItem($item, $order->number);
	    }
	    $trackingNumbers = [];
	    foreach($tracking as $key => $value ) {
	        $trackingNumbers[] = trim($value);
	    }

	    $order->set('items', $orderItems);
	    $order->set('tracking_numbers', $trackingNumbers);

	    static::log('6: TRYING TO STORE');

	     $order->save();
	    static::log('7:CALLED STORE');

	    return $order;

	}



	/*
	 * Handles ORDER SYNCING
	 *
	 */
	public static function syncInvoice(array $customerData, array $order, array $items, array $tracking) {

	    try {

	        if(empty($customerData['customerNumber'])) {
	            throw new \Exception('Customer ID required');
	        }

	        /*
	         * CHECK FOR CUSTOMER
	         */
	        $customer = (new \RallyShop\Models\Customers)->setState('filter.customer_number', $customerData['customerNumber'])->getItem();

	        static::log('1: Customer Check for ID :'. $customerData['customerNumber']);

	        if(empty($customer->id)) {
	            //IF THE CUSTOMER IS EMPTY CREATE IT
	            $customer = \RallyShop\Models\Importers\Customers::sync($customerData);

	            if(empty($customer)) {
	                throw new \Exception('Customer Not Created');
	            }

	            static::log('2: Customer Created');
	        }

	        $orderFields = static::mapDataToMongoKeys($order);


	        //avoid class conflicts
	        if($customer instanceof \RallyShop\Models\Customers) {
	        } else {
	        	$customer = (new \RallyShop\Models\Customers)->setCondition('_id', $customer->id)->getItem();
	        }



	        static::log('3: MONGO KEYS');
	        /*
	         * FIND THE ORDER BY ID
	        */

	        /*
	         * LETS SEE IF THIS INVOICE SHOULD UPDATE AND ORDER FROM MONGO OR JUST CREATE ITS SELF
	         */
	        if(empty($orderFields['original_order_number'])) {
	        	if(!empty($orderFields['number'])) {
	        		$searchOrderId = $orderFields['number'];
	        	}
	        } else {
	        	$searchOrderId = $orderFields['original_order_number'];
	        }




	        $order = (new \RallyShop\Models\Orders)->setCondition('number', $searchOrderId)->getItem();



	        if(!empty($order->id)) {
	            static::log('4: UPDATING ORDER TO INVOICE');
	            return static::updateSalesOrderToInvoice($customer, $order, $orderFields, $items, $tracking);
	        } else {
	            static::log('4: CREATING ORDER');
	            return static::createHistoricalInvoice($customer, $orderFields, $items, $tracking);

	        }

	    } catch (\Exception $e) {

	        throw new \Exception( $e->getMessage());
	    }


	}
	/*
	 * We are deleting the invoice becuase in both cases we are creating the INV-Number so just in case this is a ERP doc.
	 * We return the emails array  just to merge it so we don't double send emails from invoices, which a rare possibibilty
	 */
	protected static function deleteExistingInvoice($invoiceNumber)
	{
		$collection = \Dsc\Mongo\Helper::getCollection('shop.orders');
		$document = $collection->findOne(['number' => trim($invoiceNumber)]);
		$collection->deleteOne(['number' => trim($invoiceNumber)]);
		return $document;
	}

	protected static function updateSalesOrderToInvoice( \RallyShop\Models\Customers $customer, \RallyShop\Models\Orders $order, array $orderFields, array $items, array $tracking) {
		//if how GP has already synced this invoice, we can just clearn it out sine we are updating a sales order to this invoice number
		$document = static::deleteExistingInvoice($orderFields['number']);

	    $customer = $customer->ensureValidCustomer()->reload();

	    static::log('5: Validating Customer');

	    //MAP THE USER
	    $real_email = $customer->email(true);
	    if ($real_email != $order->user_email) {
	        $order->user_email = $real_email;
	    }

	    static::log('5: Setting Update Fields');
	    foreach ($orderFields as $key => $value) {
	        if($value && $value) {
	            $order->set($key, $value);
	        }
	    }

	    if(!empty($document['emails'])) {
	    	$emails = array_merge($order->emails, $document['emails']);
	    	$order->set('emails', $emails);
	    }

	    if (!empty($document['ymm'])) {
	    	$order->set('ymm', $document['ymm']);
	    }

	    $order->customer = $customer->cast();
	    $order->customer_name = $customer->fullName();
	    $order->user_id = $customer->id;
	    $order->existsingp = true;

	    $orderItems = [];
	    foreach($items as $item ) {
	        $orderItems[] = static::makeCartItem($item, $order->number);
	    }
	    $trackingNumbers = [];
	    foreach($tracking as $key => $value ) {
	        $trackingNumbers[] = trim($value);
	    }

	    $order->set('items', $orderItems);
	    $order->set('tracking_numbers', $trackingNumbers);

	    static::log('6: TRYING TO STORE');

	    $order->save();
	    static::log('7:CALLED STORE');

	    return $order;

	}

	/*
	 * A Historical Invoice is a order that is imported aleady to the most complete state allowing a user to see user history
	 */

	protected static function createHistoricalInvoice(\RallyShop\Models\Customers $customer, array $orderFields, array $items, array $tracking) {

	   // $document = static::deleteExistingInvoice($orderFields['number']);

	    $order = (new \RallyShop\Models\Orders)->setCondition('number',$orderFields['number'])->getItem();
        if(empty($order->id)) {
            $order = (new \RallyShop\Models\Orders);
        }

	    $customer = $customer->ensureValidCustomer()->reload();


	    $order->customer = $customer->cast();
	    $order->customer_name = $customer->fullName();
	    $order->user_id = $customer->id;
	    $order->existsingp = true;


	    //MAP THE USER
	    $real_email = $customer->email(true);
	    if ($real_email != $order->user_email) {
	        $order->user_email = $real_email;
	    }


	    foreach ($orderFields as $key => $value) {
	        if(!empty($value) && !empty($key)) {
	            $order->set($key, $value);
	        }
	    }

	    $orderItems = [];
	    foreach($items as $item ) {
	        $orderItems[] = static::makeCartItem($item, $order->number);
	    }

	    $trackingNumbers = [];
	    foreach($tracking as $key => $value ) {
	        $trackingNumbers[] = trim($value);
	    }

	    $order->set('items', $orderItems);

	  //  if(!empty($document['emails'])) {
	  //  	$order->set('emails', $document['emails']);
	  //  }

      //	if (!empty($document['ymm'])) {
      //		$order->set('ymm', $document['ymm']);
      //	}

	    $order->set('tracking_numbers', $trackingNumbers);
	    // send gift certificate emails [optional]
	    //$order->fulfillGiftCards();
	    $order->history[] = array(
	        'created' => \Dsc\Mongo\Metastamp::getDate('now'),
	        'verb' => 'Imported From GP'
	    );

	    // Close the order and mark it as fulfilled
	    $order->fulfillment_status = \Shop\Constants\OrderFulfillmentStatus::fulfilled;
	    $order->status = \Shop\Constants\OrderStatus::closed;
	    $order->financial_status = \Shop\Constants\OrderFinancialStatus::paid;

	    $order->save();


	    return $order;

	}


	/*
	 * Handles THIS WILL CREATE COMPLETED RMA-
	 *
	 */
	public static function syncReturn(array $customerData, array $order, array $items) {

	    try {

	        if(empty($customerData['customerNumber'])) {
	            throw new \Exception('Customer ID required');
	        }

	        /*
	         * CHECK FOR CUSTOMER
	         */
	        $customer = (new \RallyShop\Models\Customers)->setState('filter.customer_number', $customerData['customerNumber'])->getItem();

	        static::log('1: Customer Check for ID :'. $customerData['customerNumber']);

	        if(empty($customer->id)) {
	            //IF THE CUSTOMER IS EMPTY CREATE IT
	            $customer = \RallyShop\Models\Importers\Customers::sync($customerData);

	            if(empty($customer)) {
	                throw new \Exception('Customer Not Created');
	            }

	            static::log('2: Customer Created');
	        }

	        $orderFields = static::mapDataToMongoKeys($order);


	        if($customer instanceof \RallyShop\Models\Customers) {

	        } else {
	        	$customer = (new \RallyShop\Models\Customers)->setCondition('_id', $customer->id)->getItem();
	        }


	        static::log('3: MONGO KEYS');
	        /*
	         * FIND THE ORDER BY ID
	        */
	        $return = (new \RallyShop\Models\Returns)->setCondition('number', $orderFields['number'])->getItem();

	        if(!empty($return->id)) {
	            static::log('4: UPDATING ORDER TO INVOICE');
	            return static::updateReturnStatus($customer, $return, $orderFields, $items);
	        } else {
	            static::log('4: CREATING ORDER');
	            return static::createHistoricalReturn($customer, $orderFields, $items);
	        }

	    } catch (\Exception $e) {

	        throw new \Exception( $e->getMessage());
	    }



	}

	protected static function createHistoricalReturn(\RallyShop\Models\Customers $customer, array $orderFields, array $items) {


	    $return = (new \RallyShop\Models\Returns);

	    $customer = $customer->ensureValidCustomer()->reload();

	    $return->customer = $customer->cast();
	    $return->customer_name = $customer->fullName();
	    $return->user_id = $customer->id;


	    foreach ($orderFields as $key => $value) {
	        if(!empty($value) && !empty($key)) {
	            $return->set($key, $value);
	        }
	    }

	    $orderItems = [];
	    foreach($items as $item ) {
	        $orderItems[] = static::makeCartItem($item, $return->number);
	    }
	    $return->set('items', $orderItems);


	    $return->store();

	    return $return;

	}

	protected static function updateReturnStatus($customer, $return, $orderFields, $items)
    {

    }

	public static function syncQuote(array $customerData, array $order, array $items)
    {
        $fields = static::mapDataToMongoKeys($order);

        $quote = (new \Shop\Models\Quotes)
            ->setCondition('number', $fields['number'])
            ->getItem();

        if (!empty($quote->id)) {
            static::updateQuote($quote, $fields, $items);
        } else {
            static::createQuote($fields, $items);
        }
	}

    protected static function createQuote(array $fields, array $items)
    {
        if ($fields['metadata.created']['time'] <= strtotime('-30 days')) {
            return;
        }

        if (!empty($fields['status']) && $fields['status'] == 'cancelled') {
            return;
        }

        $quote = (new \Shop\Models\Quotes);

        foreach ($fields as $key => $value) {
            if ($value && $value) {
                $quote->set($key, $value);
            }
        }

        $orderItems = [];
        foreach($items as $item ) {
            $orderItems[] = static::makeCartItem($item, $quote->number);
        }

        $quote->set('items', $orderItems);
        $quote->save();
    }

    protected static function updateQuote(\Shop\Models\Quotes $quote, array $fields, array $items)
    {
        if (!empty($fields['status']) && $fields['status'] == 'cancelled') {
            $quote->deleteDocument();
            return;
        }

        foreach ($fields as $key => $value) {
            $quote->set($key, $value);
        }

        $orderItems = [];
        foreach($items as $item ) {
            $orderItems[] = static::makeCartItem($item, $quote->number);
        }

        $quote->set('items', $orderItems);
        $quote->save();
    }
}
