<?php 
namespace JBAShop\Site\Controllers;

class Notifications extends \Dsc\Controller
{    
    protected function model($type=null) 
    {
        $model = null;
        switch( $type ){
           case 'products' :
               $model = new \JBAShop\Models\Products;
               break;
           default:
           case 'notifications':
               $model = new \JBAShop\Models\Notifications;
               break;
        }
    
        return $model; 
    }
    
    public function receive()
    {
        $ymm = \Dsc\System::instance()->get('session')->get('activeVehicle');

        $slug = $this->app->get('PARAMS.slug');
        $product = $this->model('products')->setState('filter.slug', $slug)->getItem();

        $email = trim($this->app->get('POST.email'));

    	try {
            if (!empty(
                $this->model('notifications')
                    ->setCondition('email', $email)
                    ->setCondition('product.id', $product->id)
                    ->getItem()
            )) {
                throw new \Exception('You are already on the notification list for this product.');
            }

    		$item = $this->model('notifications');

    		$item->product = array(
                'id' => $product->id,
                'title' => $product->title,
                'slug' => $product->slug,
            );

            if (!empty($ymm)) {
                $item->ymm = [
                    'id' => $ymm['_id'],
                    'title' => $ymm['title']
                ];
            }

            $item->email = $email;
            
            $user = $this->auth->getIdentity();
    		if($user->id) {
    		    $item->user = array(
    		          'id' => $user->id,
    		          'name' => $user->fullName(),
                );
    		}
    		$item->save();
    		
    	} catch ( \Exception $e ) {
    	    // TODO Change to a normal 404 error
    		\Dsc\System::instance()->addMessage( "<strong>Error:</strong> " . $e->getMessage(), 'error');
    		$this->app->reroute( '/shop/product/'.$product->slug);
    		return;
    	}
    	
    	if ($this->app->get('AJAX')) {
    			return $this->outputJson( $this->getJsonResponse( array(
    					'result'=>true,
    					'message'=>'<strong>Success</strong> You will be notified when back in stock'
    			) ) );
    	} else {
    		\Dsc\System::instance()->addMessage( "<strong>Success</strong> You will be notified when back in stock", 'success');
    		$this->app->reroute( '/shop/product/'.$product->slug );
    	}
    }
}