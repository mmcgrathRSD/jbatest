<?php 
namespace JBAShop\Site\Controllers;

class OrderedGiftCard extends \Shop\Site\Controllers\OrderedGiftCard
{
    
    protected function getModel($type="giftcard"){
        $model = null;
        
        switch($type) {
            case 'giftcard':
                $model = new \JBAShop\Models\OrderedGiftCards();
                break;
        }    
        return $model;    
    }
    
    /**
     * Display a single ordered gift card
     */
    public function read()
    {
        $f3 = \Base::instance();
        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $token = $this->inputfilter->clean( $f3->get('PARAMS.token'), 'alnum' );
    	
    	try {
    		$item = $this->getModel()->setState('filter.id', $id)->setState('filter.token', $token)->getItem();
    		if (empty($item->id)) {
    			throw new \Exception;
    		}

    	} catch ( \Exception $e ) 
    	{
    		\Dsc\System::instance()->addMessage( "Invalid Gift Card", 'error');
    		$f3->reroute( '/shop' );
    		return;
    	}
    	
    	\Base::instance()->set('giftcard', $item );
    	
    	if ($f3->get('print')) {
    	    $this->app->set('meta.title', 'Print | Gift Card');
    	    echo $this->theme->renderView('Shop/Site/Views::orderedgiftcard/print.php');
    	    return;
    	}    	
    	
    	$this->app->set('meta.title', 'Gift Card');
    	
    	echo $this->theme->render('Shop/Site/Views::orderedgiftcard/detail.php');
    }
    
    public function email()
    {
        $f3 = \Base::instance();

        $id = $this->inputfilter->clean( $f3->get('PARAMS.id'), 'alnum' );
        $token = $this->inputfilter->clean( $f3->get('PARAMS.token'), 'alnum' );
                
        $data = array(
            'sender_name' => $this->input->get( 'sender_name', null, 'string' ),
            'sender_email' => $this->input->get( 'sender_email', null, 'string' ),
            'recipient_name' => $this->input->get( 'recipient_name', null, 'string' ),
            'recipient_email' => $this->input->get( 'recipient_email', null, 'string' ),
            'message' => $this->input->get( 'message', null, 'string' ),
        );
                
        // TODO Validate the input from the form, require at least emails and names
        if (empty($data['sender_name'])
            || empty($data['sender_email'])
            || empty($data['recipient_name'])
            || empty($data['recipient_email'])
            )
        {
            \Dsc\System::instance()->addMessage( "Please complete all required fields.  All name and email fields are required.", 'error');
            $f3->reroute( '/shop/giftcard/' . $id . '/' . $token );
            return;        	
        }
         
        try {
            $item = $this->getModel()->setState('filter.id', $id)->setState('filter.token', $token)->getItem();
            if (empty($item->id)) {
                throw new \Exception;
            }
        
        } 
        catch ( \Exception $e )
        {
            \Dsc\System::instance()->addMessage( "Invalid Gift Card", 'error');
            $f3->reroute( '/shop' );
            return;
        }
        
        // use the model to send the email so the model can add the history
        try {
            $item->sendEmailShareGiftCard( $data );
        }
        catch ( \Exception $e )
        {
            \Dsc\System::instance()->addMessage( "Error sending email.", 'error');
            $f3->reroute( '/shop/giftcard/' . $id . '/' . $token );
            return;
        }
        
        \Dsc\System::instance()->addMessage( "Gift card has been sent to " . $data['recipient_email'] );
        
        $f3->reroute( '/shop' );
    }
    
    /**
     * Display a single ordered gift card
     */
    public function checkBalance()
    {
        $this->requireIdentity();
    	$this->app->set('meta.title', 'Gift Card');
    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orderedgiftcard/check_balance.php');
    }
    
    
    /**
     * Display a single ordered gift card
     */
    public function doCheckBalance()
    {
    	$f3 = \Base::instance();
    	$card_number = $this->inputfilter->clean( $f3->get('PARAMS.card_number'), 'alnum' );
    	$card_pin = $this->inputfilter->clean( $f3->get('PARAMS.card_pin'), 'alnum' );
    
    	try {
    	     $code = preg_replace("/[^0-9a-z]/", '', strtolower(trim($card_number . $card_pin)));

    	     $item = \Netsuite\Models\ActiveGiftCards::getCardByCode($code);
    	
    		if (empty($item)) {
    			throw new \Exception;
    		}
    
    	} catch ( \Exception $e )
    	{
    		\Dsc\System::instance()->addMessage( "Invalid Gift Card", 'error');
    		$f3->reroute( '/shop' );
    		return;
    	}
        
        
    	\Base::instance()->set('giftcard', $item );
    
    	if ($f3->get('print')) {
    		$this->app->set('meta.title', 'Print | Gift Card');
    		$view = \Dsc\System::instance()->get('theme');
    		echo $view->renderView('Shop/Site/Views::orderedgiftcard/print.php');
    		return;
    	}
    
    	$this->app->set('meta.title', 'Gift Card');
    
    	$view = \Dsc\System::instance()->get('theme');
    	echo $view->render('Shop/Site/Views::orderedgiftcard/detail.php');
    }

    /**
     * Display a single ordered gift card
     */
    public function doCheckBalanceAjax() {
        $response = new \stdClass();
        $response->result = false;
        $identity = $this->getIdentity();
        if (empty($identity->id)){
            $response->error = "Require Login";
            $this->OutputJson($response);
            return;
        }
        
        $currentTime = time();
        $attempts = array();
        for($idx = 0; $idx < count( (array)$identity->{'shop.balance_attempts'}); $idx++ ) {
            if( $identity->{'shop.balance_attempts.'.$idx} > ($currentTime - 15 * 60) )  {
                $attempts []= $identity->{'shop.balance_attempts.'.$idx};
            }
        }
        $attempts []= $currentTime;
        $identity->{'shop.balance_attempts'} = $attempts;
        $identity->save();
        if( count( $attempts ) > 4){
            $response->error = "You reached maximum number of of requests for 15 minutes. Please contact our customer center or wait 15 minutes to make another request.";
            $this->OutputJson($response);
            return;
        }
        
        $card_number = strtolower(trim( $this->inputfilter->clean( $this->app->get('POST.dig20'), 'alnum' ) ));
        $card_pin = strtolower(trim( $this->inputfilter->clean( $this->app->get('POST.dig4'), 'alnum' ) ));
        
       
        try {
            
             $code = preg_replace("/[^0-9a-z]/", '', strtolower(trim($card_number . $card_pin)));
             $item = \Netsuite\Models\ActiveGiftCards::getCardByCode($code);

            if (empty($item)) {
                throw new \Exception;
            }
            $response->balance_msg = 'Remaining balance on gift card <strong>'.$item->giftCertificateCode.'</strong> is <strong>'.\Shop\Models\Currency::format( $item->amountRemaining ).'</strong>.';
            $response->result = true;
        } catch ( \Exception $e )
        {
            $response->error =  "Invalid Gift Card";
            $this->OutputJson( $response );
            return;
        }
        $this->OutputJson( $response );
        return;
    }

}