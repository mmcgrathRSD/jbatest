<?php 
namespace JBA\Site\Controllers;

class ContactUs extends \Dsc\Controller 
{
    public function index()
    {
        $this->app->set('meta.title', 'Contact Us');
        $this->app->set('meta.description', 'Contact JBA');
        
        echo $this->theme->render('JBA\Site\Views::contactus/default.php');
    }
    
    public function submit()
    {
        $name  = $this->input->get('z_name', '', 'string');
        $email = filter_var($this->input->get('z_requester', '', 'string'), FILTER_VALIDATE_EMAIL);
        $order = $this->input->get('z_order', '', 'string');
        $phone = $this->input->get('z_telephone', '', 'string');
        $type = $this->input->get('z_drop-down', '', 'string');
        $subject = $this->input->get('z_subject', '', 'string');
        $message = $this->input->get('z_description', '', 'string');

        if (!empty($name) && !empty($email) && !empty($type)  && !empty($message)) {
            // Create Zendesk Ticket
            if ($zendesk = \Dsc\System::instance()->get('zendesk')) {
                $finalSubject = $type;
                if (!empty($order)) {
                    $finalSubject .= " #$order";
                }

                $finalSubject .= ' - ' . $subject;

                $finalMessage = $message;
                if (!empty($phone)) {
                    $finalMessage .= "\n\n\nTelephone: {$phone}";
                }

                $zendesk->tickets()->create([
                    'brand_id' => $this->app->get('zendesk_api.brand_id'),
                    "custom_fields" => [
                        'id'    => $this->app->get('zendesk_api.custom_fields_id'),
                        'value' => 'Tracking'
                    ],
                    'subject' => $finalSubject,
                    'comment' => [
                        'value' => $finalMessage
                    ],
                    'requester' => [
                        'name' => $name,
                        'email' => $email
                    ]
                ]);
            }
               
            \Dsc\System::addMessage('Your inquery was submitted and will be responded to as soon as possible.  Thank you for contacting us.', 'success');
            $this->app->reroute('/contact-us');
        } else {
            \Dsc\System::addMessage('Please fill out all required fields!', 'error');
            $this->app->reroute('/contact-us');
        }
   }
   
   public function returnHandoff()
   {
        \Dsc\System::addMessage('You can create a return from inside your order.','success');
        $this->app->reroute('/shop/account');
   }
}

