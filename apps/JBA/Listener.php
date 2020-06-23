<?php
namespace JBA;

class Listener extends \Prefab
{   

	public function onCreateAssetsAdminModelsAssets($event) {
		$model = $event->getArgument('model');
	
		if($model->storage == 'gridfs') {
			\Dsc\Queue::task('\Assets\Models\Storage\CloudFiles::gridfsToCDN', [$model->slug]);
		}
	}

	public function onUserAuthentication($event) {
		$creds = $event->getArgument('credentials');

		$username_input = $creds['login-username'];
		$password = $creds['login-password'];

		$hash = password_hash($password, PASSWORD_DEFAULT);

		// now check via email
		try {
			$model = new \Users\Models\Users;
			$model->setState('filter.email', $username_input);

			if ($user = $model->getItem()) {
				$old = $user->{'old.password'};
				$parts = explode(':', $old);

                if(hash('sha256', $parts[1] . $password) == $parts[0]){
                    $newPassword = password_hash($password, PASSWORD_DEFAULT);
                    $user->set('password', $newPassword);
                    $user->clear('old.password');
                    $user->save();
                    \Dsc\System::instance()->get('auth')->setIdentity( $user );
                }

                if(hash('md5', $parts[1] . $password) == $parts[0]){
                    $newPassword = password_hash($password, PASSWORD_DEFAULT);
                    $user->set('password', $newPassword);
                    $user->clear('old.password');
                    $user->save();
                    \Dsc\System::instance()->get('auth')->setIdentity( $user );
                }

				// if (hash('sha256', $parts[1] . $password) == $parts[0]) {
				// 	$user->set('password', $hash);
				// 	$user->clear('old.password');
				// 	$user->save();
				// 	\Dsc\System::instance()->get('auth')->setIdentity( $user );
				// }
			}
		} catch ( \Exception $e ) {
			$this->setError('Invalid Email');
		}

	}
	
	public function afterUserLogin($event) {
		$user = $event->getArgument('identity');
		if(!empty($user->get('netsuite.internalId'))) {
		    \Dsc\Queue::task('\Search\Models\Algolia\BoomiOrders::syncMyOrders', [$user->get('netsuite.internalId'), $user->get('netsuite.externalId')], ['batch'=>'algolia']);
		}
	}

    public function onGetCheckoutModel($event)
    {
        $event->setArgument('checkout', \JBAShop\Models\Checkout::instance());
    }

    public function onAlgoliaGetOrder($event)
    {
        $order = $event->getArgument('order');
        $number = !empty($order->original_order_number) ? $order->original_order_number : $order->number;

        $splits = (new \Shop\Models\Orders)
            ->setCondition('$or', [
                ['number' => new \MongoDB\BSON\Regex('^' . $number . '\.\d+')],
                ['original_order_number' => new \MongoDB\BSON\Regex('^' . $number . '\.\d+')]
            ])
            ->getItems();

        $splitOrder = clone $order;

        // reset things that we are getting from the splits
        $splitOrder->grand_total = 0;
        $splitOrder->items = [];

        if (!count($splits)) {
            $splits = [$order];
        }

        $invoices = [];
        foreach ($splits as $split) {
            $isDropship = false;
            $splitOrder->grand_total += $split->grand_total;

            $items = [];
            foreach ((array) $split->items as &$item) {
                if (!empty($item['dropship'])) {
                    $isDropship = true;
                }

                $item['warehouse_code'] = $split->warehouse_code;
                $item['shipping_method'] = $split->get('checkout.shipping_method');

                $items[] = $item;
                $splitOrder->items[] = $item;
            }

            $split->items = $items;

            if (substr($split->number, 0, 3) == 'INV') {
                $trackingNumbers = [];

                foreach ($split->tracking_numbers as $trackingNumber) {
                    $trackingNumbers[] = [
                        'isDropShip' => $isDropship,
                        'carrier' => $split->getShippingProvider(),
                        'trackingNumber' => $trackingNumber
                    ];
                }

                $split->tracking_numbers = $trackingNumbers;
                $invoices[] = (new \Shop\Models\Invoices)->bind($split->cast());
            }
        }

        $event->setArgument('invoices', $invoices);
        $event->setArgument('finalOrder', $splitOrder);
    }
}
