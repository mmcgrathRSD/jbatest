<?php 
namespace JBA\Site\Controllers;

class Connect extends \Dsc\Controller 
{
    public function test()
    {
       
    	
    	$client = new \SoapClient("http://104.130.126.46/amp/wsdl", array("soap_version" => SOAP_1_2));
		    // services to rate
		$services = array("CONNECTSHIP_UPS.UPS.GND", "CONNECTSHIP_UPS.UPS.2DA",  "TANDATA_USPS.USPS.PRIORITY");
		
		// create defaults as empty array and populate using [] syntax
		$defaults = array();
		$defaults["shipper"] = "RSD";
		$defaults["packaging"] = "CUSTOM";
		$defaults["shipdate"] = "";   // use current date
		
		// create NameAddress complex type as array
		$consignee = array("company" => "Test Co.", "contact" => "Test Contact", 
		        "address1" => "1234 West Dr.", "city" => "Austin",
		        "stateProvice" => "TX", "postalCode" => "78758",
		        "countryCode" => "US");
		
		$defaults["consignee"] = $consignee;
		
		// create empty packages list
		$packages = array();
		
		// create new package dictionary
		$package = array();
		$package["weight"] = array("value" => "2.3 lbs");
		
		// add package to list
		$packages[] = $package;
		
		// create 2nd package dictionary
		$package = array();
		$package["weight"] = array("value" => "22.7 lbs");
		$package["additionalHandling"] = true;
		
		// add 2nd package to list
		$packages[] = $package;
		
		// create RateRequest message
		$req = array("services" => $services, "defaults" => $defaults, "packages" => $packages, 
		        "sortType" => "rate");
		
		// invoke Rate operation
		$resp = $client->Rate($req);
		
		// process result
		$result = $resp->result;
		print $result->code."\n";
		print $result->message."\n";
		
		// display rate and transit time for each service
		foreach ($result->resultData->item as $i) {
			var_dump($i->service); die();
		    print $i->service->name."  ".$i->message."\n";
		    print "  Total: ".$i->resultData->total->value."\n";
		    print "  Commitment: ".$i->packageResults->item[0]->resultData->timeInTransit->name."\n";
		}
    	
    	
    }
   
}