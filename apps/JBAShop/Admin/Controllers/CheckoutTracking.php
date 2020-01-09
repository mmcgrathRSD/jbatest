<?php 
namespace RallyShop\Admin\Controllers;

class CheckoutTracking extends \Admin\Controllers\BaseAuth
{
    
   
	public function index()
	{
		$model = (new \RallyShop\Models\CheckoutGoals);
	
		$state = $model->emptyState()->populateState()->getState();
		\Base::instance()->set('state', $state );
	
		$paginated = $model->paginate();
		\Base::instance()->set('paginated', $paginated );
	
		$this->app->set('meta.title', 'Checkout Tracking | Shop');

		
		$view = \Dsc\System::instance()->get('theme');
		echo $view->render('RallyShop\Admin\Views::checkouttracking/index.php');
	}
	
   public function single() {
   	
   	
   	//load the checkoutGoals
   	$checkout = (new \RallyShop\Models\CheckoutGoals)->setState('filter.id',$this->app->get('PARAMS.user_id') )->getItem();
   	   	
   	$cart = (new \RallyShop\Models\Carts)->load(array('_id' => $checkout->cart_id));
   	
   	$this->app->set('cart', $cart);
   	$this->app->set('checkout',$checkout);
   	
   	$list = (new \Activity\Models\Actions)->collection()->find([
   			'actor_id' => $checkout->id,
   	]);
   	
   	$this->app->set('actions',$list);
   	
   	$view = \Dsc\System::instance()->get('theme');
   	echo $view->render('RallyShop\Admin\Views::checkouttracking/single.php');
   	
   }
    
   
   public function fragment() {
   
   
   	//load the checkoutGoals
   	$checkout = (new \RallyShop\Models\CheckoutGoals)->setState('filter.id',$this->app->get('PARAMS.user_id') )->getItem();
   
   	$cart = (new \RallyShop\Models\Carts)->load(array('_id' => $checkout->cart_id));
   
   	$this->app->set('cart', $cart);
   	$this->app->set('checkout',$checkout);
   
   	$list = (new \Activity\Models\Actions)->collection()->find([
   			'actor_id' => $checkout->id,
   	]);
   	$this->app->set('update',true);
   	$this->app->set('actions',$list);
   
   	$view = \Dsc\System::instance()->get('theme');
   	echo $view->renderView('RallyShop\Admin\Views::checkouttracking/single.php');
   
   }
    
}