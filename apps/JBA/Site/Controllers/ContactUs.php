<?php 
namespace RallySport\Site\Controllers;

class ContactUs extends \Dsc\Controller 
{
    public function index()
    {


        $this->app->set('meta.title', 'Contact Us');
        $this->app->set('meta.description', 'Contact RallySport Direct');
        
        echo $this->theme->render('RallySport\Site\Views::contactus/default.php');
    }
    
    
    
   public function submit() {
   	
   		$customer  = $this->input->get('customer', '', 'string');
   		$email = $this->input->get('email', '', 'string');
   		$phone = $this->input->get('phone', '', 'string');
   		$topic = $this->input->get('topic', '', 'string');
   		$message = nl2br($this->input->get('message', '', 'html'));
   		

   		
	   	if(!empty($customer) && !empty($email)  && !empty($phone)  && !empty($topic)  && !empty($message)) {
	   		$toEmail="customerservice@rallysportdirect.com";
	   		//$toEmail="chris.french@rallysportdirect.com";
	   		//$toEmail="zac.ranck@rallysportdirect.com";
            
	   		$mailer = \Dsc\System::instance()->get('mailer');
	   		if ($content = $mailer->getEmailContents('rallysport.contact_us_request', array(
	   				'customername' => $customer,
	   				'customeremail' => $email,
	   				'customerphone' => $phone,
	   				'customertopic' => $topic,
	   				'customermessage' => $message
	   		
	   		))) {

	   			$content['subject'] = $topic;
	   			$content['replyToEmail'] = $email; 
	   			$content['replyToName'] = $customer;
	   			 
	   			$mailer->sendEvent( $toEmail, $content);
	   		}
	   		
	 
	   		

	   		if ($this->app->get('AJAX')) {
	   				return $this->outputJson( $this->getJsonResponse( array(
	   						'result'=>true,
	   						'message'=>'Email Sent'
	   				) ) );
	   		
	   		} else {
	   			\Dsc\System::addMessage('Thank you for contacting RallySport Direct. A customer service representative will contact you shortly.','success');
	   			$this->app->reroute('/contact-us');
	   		}

	   	}

   }
   
   public function returnHandoff() {
   
   			\Dsc\System::addMessage('You can create a return from inside your order.','success');
   			$this->app->reroute('/shop/account');
   
   }
   
}

