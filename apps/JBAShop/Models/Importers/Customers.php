<?php
namespace JBAShop\Models\Importers;

class Customers extends \JBAShop\Models\Customers {
	
	const PRIMARYKEY = 'gp.customer_number';
	const DEFAULTCREATEACTION = 'none';
	
	
	public function checkRequiredFields($data) {
		
		$requiredFields = [];
		$requiredFields[] = self::PRIMARYKEY;
		$requiredFields[] = 'email';
	
	}
	
	
	public static function mapDataToMongoKeys($data)
    {
		$map = [];
		//datafield / mongo field
		$map['customerNumber'] = 'gp.customer_number';
		$map['email'] = 'email';
		$map['firstName'] = 'first_name';
		$map['lastName'] = 'last_name';
		$map['contactPerson'] = 'contact_name';
		$map['salesRepNumber'] = 'sales_rep_number';
		$map['paymentTerms'] = 'terms';
		$map['priceLevel'] = 'price_level';
		$map['profileId'] = 'gp.cim_profile_id';
		$map['salesTerritory'] = 'sales_territory';
		$map['customerClass'] = 'customer_class';
		
		$map['customerSocialRole'] = 'profile.social_role';
		$map['customerSocialRoleBrand'] = 'profile.social_role_brand';
		$map['screenName'] = 'profile.screen_name';
		$map['profileSocialInteractionLikes'] = 'profile.social_role_brand';
		$map['profileName'] = 'profile.social_role_brand';
		$map['profileLocation'] = 'profile.location';
		$map['profileCars'] = 'profile.cars';
		$map['profileMods'] = 'profile.mods';
		$map['profileVehicleUse'] = 'profile.vehicle_use';
		$map['profileAboutMe'] = 'profile.about_me';
        $map['onHold'] = 'on_hold';
        $map['notes'] = 'notes';
        $map['role'] = 'role';
		$map['password'] = 'old.password';
		
		$map['productAnnouncementsOptIn'] = 'preferences.emails.promo';
		// rsdsql
		$map['lastInvoice'] = 'shop.last_invoice';
		$map['totalOrders'] = 'shop.orders_count';
		$map['totalSpent'] = 'shop.total_spent';
		$map['lastOrderVal'] = 'shop.last_order_value';
		$map['lastOrderDate'] = 'shop.open_order_timestamp';
		$map['lastInvoiceDate'] = 'shop.last_invoice_timestamp';

		$mongoMapper = array();
		foreach ($map as $key => $value) {
		    if (!isset($data[$key])) continue;

			if(!empty($data[$key]) || $data[$key] === false) {
				$mongoMapper[$value] = $data[$key];
			}
		}
		
		//HANDLE SPECIAL CASES LIKE TIMESTAMPS
		if(!empty($data['creationDate'])) {
			$mongoMapper['metadata.created'] = \Dsc\Mongo\Metastamp::getDate($data['creationDate']);
		}

		if(!empty($data['customerSocialRole'])) {
			switch ($data['customerSocialRole']) {
				case 'staff':
					$mongoMapper['role'] = 'staff';
				break;
			}
		}
		
		return $mongoMapper;
	}
	
	
	
	/*
	 * Handles Customer importing from data
	 * 
	 */
	public static function sync(array $data) {
		
		
		// check if safemode is being used
		$safemode_enabled = \Base::instance()->get('safemode.enabled');
		$safemode_user = \Base::instance()->get('safemode.username');
		$safemode_email = \Base::instance()->get('safemode.email');
		$safemode_password = \Base::instance()->get('safemode.password');
		$safemode_id = \Base::instance()->get('safemode.id');
		
		$user = new \Users\Models\Users;
		$user->id = $safemode_id;
		$user->username = 'customerImporter';
		$user->first_name = 'customerImporter';
		$user->password = $safemode_password;
		$user->email = $safemode_email;
		
			$role = 'root';
	
		$user->role = $role;
		$user->__safemode = true;
		
		
		\Dsc\System::instance()->get('auth')->setIdentity($user);
		
		
		try {
			$data = static::mapDataToMongoKeys($data);
			$key = self::PRIMARYKEY;
			
			
			
			if(empty($data[$key])) {
				throw new \Exception( 'No Customer ERP Identitier '. $key);
			}
			
			$model = new static; 
			
			$item = $model
                ->setCondition('$or', [
                    [$key => $data[$key]],
                    ['number' => $data[$key]]
                ])
                ->getItem();
			

			if(empty($item->id)) {
				//CHECK TO SEE IF THIS IS A DUPLICATED ACCOUNT BY EMAIL
				$user = (new \JBAShop\Models\Customers);
				
				if(!empty($data['email'])) {
					$data['email'] = trim(strtolower($data['email']));
				}
				
				if( !empty($data['email']) && $exisiting = $user->emailExists($data['email']) )  {
					
					$key = self::PRIMARYKEY;
					
					$customerIds = $exisiting->{'gp.additional_customer_numbers'};
					
					if(empty($customerIds)) {
						$customerIds = [];
					}
										
					$customerIds[] = $data[$key];
					
					$customerIds = array_unique(($customerIds));
					
					if(strtolower($data['customer_class']) == 'wholesale') {
					    
					    $exisiting->set('number', $data[$key])->store();
					    
					    $exisiting = $model->updateCustomerFromData($data, $exisiting);
					    
					} else {
					
    					if(!empty($exisiting->number)) {
    					    $exisiting->set('gp.additional_customer_numbers', $customerIds);
    					} else {
    					    $exisiting->set('number', $data[$key])->store();
    					}
					
					$exisiting->store();
					
					}
					
					return $exisiting;
		
				} else {
					//else create it
					$item = static::createNewCustomerFromData($data);
				}
				
				
			} else {
				$item = $model->updateCustomerFromData($data, $item);
			}
			\Dsc\System::instance()->get('auth')->logout();
		} catch (\Exception $e) {
			\Dsc\System::instance()->get('auth')->logout();
			throw new \Exception($e->getMessage());
			
		}
			
		return $item;
	}
	
	
	protected static function createNewCustomerFromData(array $data, $registration_action = null) {
		try {
			
		
		$user = (new \Users\Models\Users);
		$key = self::PRIMARYKEY;
		
		
		//EMAIL IS A REQUIRED FIELD
		if(empty($data['email'])) {
			//throw new \Exception( 'Field email is required');
			$unique = new \MongoDB\BSON\ObjectID();
			$data['email'] = 'guest'. $unique.'@placeholder.com';
		} 
		
		$data['role'] = 'identified';
		
		
		//LETS CONFIRM THAT THIS EMAIL IN UNIQUE
		//if($exisiting = $user->emailExists( $data['email'] )) {
		//	throw new \Exception( $exisiting->email . ' EMAIL ADDRESS IS ALREADY REGISTER TO A USER WITH A DIFFERENT '. $key .' '. $exisiting->$key);
		//}	
		$user->bind($data);
		/*foreach ($data as $key => $value) {
			if(!empty($key) && is_string($key)) {
				$user->set($key, $value);
			}
		}*/
		
		$user->set('imported', new \MongoDB\BSON\UTCDateTime());
				
		if(empty($registration_action)) {
			$registration_action = self::DEFAULTCREATEACTION;
		}
		
		
		// $user->save() will handle other validations, such as username uniqueness, etc
		// and throws an exception if validation/save fails
		switch ($registration_action)
		{
			case "none":
				$user->active = true;
				$user->save();
				break;
			case "email_validation":
				$user->active = false;
				$user->save();
				
				$user->sendEmailValidatingEmailAddress();
					
				break;
			case "generate_password":
					$user->active = false;
					$user->save();
		
				break;
			default:
				$user->active = true;
				$user->save();
		
					
				break;
		}
		
		return $user;
		} catch (\Exception $e) {
			throw new \Exception('createNewCustomerFromData ' . $e->getMessage() );
		}
		
		
		
	}
	
	
	protected function updateCustomerFromData(array $data, $model) {
		try {
            
            $customRoles = ["staff", "root", "industry", "customer-service"]; 
            
			foreach ($data as $key => $value) {
				if(!empty($key) && is_string ($key)) {
                    if($key == 'role' && in_array(strtolower($model->get('role')),$customRoles) ) {
				        continue; //don't update the sync if we have customroles
				    }
					//THIS HAS A strtolower on it before but that is incorrect I wonder if we wanted just some of the keys? was I thinking of trim?
                    $model->set($key, $value);
                    
				}
			}
			$model->save();
		} catch (\Exception $e) {
			throw new \Exception('updateCustomerFromData ' . $e->getMessage() );
		}
		
	}
	
		
}
