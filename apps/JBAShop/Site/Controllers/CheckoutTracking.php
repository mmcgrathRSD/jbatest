<?php
namespace JBAShop\Site\Controllers;

class CheckoutTracking extends \Dsc\Controller
{
	
	public function track()
	{
		$action = $this->input->get('action', null, 'string');
		
		$fields = array(
				"checkout[shipping_address][name]",
				"checkout[shipping_address][line_1]",
				"checkout[shipping_address][line_2]",
		 		"checkout[shipping_address][postal_code]",
				"checkout[shipping_address][region]",
				"checkout[shipping_address][phone_number]",
				"checkout[shipping_method]"
		);
		$human   = array(
				"Shipping Name",
				"Shipping Line 1",
				"Shipping Line 2",
				"Shipping Postal Code",
				"Shipping State",
				"Shipping Phone Number",
				"Shipping Method",
			
		);
		
		$action = str_replace($fields, $human, $action);
		$properties = $this->input->get('properties', null, 'string');
		if (!empty($properties) && is_string($properties)) {
			$properties = json_decode($properties);
		}
		
		$value = $this->input->get('value', null, 'string');
		
		if (!empty($value) && is_string($value)) {
			if($action == 'login-password') {
				$value = password_hash($value);
			}
			$properties = ['value' => $value];
		}
		
		if (!empty($action))
		{
			\JBAShop\Models\CheckoutTracking::track($action, $properties);
		}
	}
	
	
	
}