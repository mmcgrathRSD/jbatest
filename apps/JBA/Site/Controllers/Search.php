<?php 
namespace JBA\Site\Controllers;

class Search extends \Dsc\Controller
{ 
	
    protected function model($type=null) 
    {
        switch (strtolower($type)) 
        {
        	case "products":
        	case "product":
        	    $model = new \JBAShop\Models\Products;
        	    break;
        	default:
        	    $model = new \JBAShop\Models\Categories;
        	    break;
        }
        
        return $model; 
    }
    
    
   public function reroute() {
   		$args = [];
   		if(!empty($_GET['search_keywords'])) {
   			$args['q']= $_GET['search_keywords'];
   		}
   	
   		$url = '/search?';
   		$url .= http_build_query($args);
   		$this->app->reroute($url);
   	
   }
    
    
    public function prediction()
    {
    	
    	$time_start = microtime(true);
    	$q = trim($this->input->get('q', null, 'default'));
    	$limit = (int) trim($this->input->get('limit', 10, 'int'));
    	
    	$model = new \Shop\Models\SearchTerms;
    	$docs = $model->collection()->find([
    	    '$text' => ['$search' => $q]
        ], [
            'projection' => [
                'term' => true,
                'score' => ['$meta' => 'textScore']
            ],
            'sort' => [
                'score' =>[
                    '$meta' => 'textScore'
                ]
            ],
            'limit' => $limit
        ]);
    	
    	$terms = [];
    	foreach($docs as $doc) {
    	    $terms[] = $doc['term'];
    	}
    	
    	$time_end = microtime(true);
    	$time = $time_end - $time_start;
    	
    	return $this->outputJson($this->getJsonResponse([
	        'terms'=> $terms
	    ]));
    }
    
    protected  function processTerm($q) {
    	
    	$terms = explode(' ', $q);
    	$searchTerms = [];
    	//process each word in the string
    	foreach ($terms as $term) {
    		if(strpos($term,'-' ) !== false) {
    			$term = str_pad($term, strlen($term) + 2, '"', STR_PAD_BOTH);
    		}
    		$searchTerms[] = $term;
    	}

    	return implode(' ', $searchTerms);
    }
    
    
    public function index()
    {

    	$terms = trim($this->input->get('q', null, 'default'));

    	$q = $this->processTerm($terms);
    
    	$products_model = new \Shop\Models\SearchProducts;
    	
    	
    	$activeVehicle =  \Dsc\System::instance()->get('session')->get('activeVehicle');
    		
    	$products_model->populateState();
    		
    	$products_model->setState('filter.keyword', $q);
    	$products_model->setState('is.search', true);
    		
    	$state = $products_model->getState();
    	
    	
    	$conditions = $products_model->conditions();
  		if(!empty(trim($q))) {
  		//LIMIT DIRECT PART NUMBER SEARCHES TO JUST REDIRECT
    	$findone = (new \JBAShop\Models\Products)
    		->setCondition('publication.status', ['$in' => ['published', 'discontinued']])
    		->setCondition('tracking.model_number', strtoupper($q))
    		->getItem();
	    	if(!empty($findone->id)) {
	    		\Base::instance()->reroute('/shop/product/'.$findone->slug);
	    		exit;
	    	}
  		}
    	//
    	$paginated = $products_model->paginate();
    	
    	\Dsc\Queue::task('\Shop\Models\SearchTerms::addSearch',[$q]);
    		
    	$this->app->set('paginated', $paginated );
    		
    	$this->app->set('terms', $terms);
    	$this->app->set('comparable', false );
    	$this->app->set('state', $products_model->getState());

		$activeVehicle = \Dsc\System::instance()->get('session')->get('activeVehicle');
		$this->app->set('activeVehicle', $activeVehicle);
        
        $view = \Dsc\System::instance()->get('theme');
        if ($this->app->get( 'AJAX' ))
        {
        	/*return $this->outputJson( $this->getJsonResponse( array(
        			'html'=> $view->renderLayout('Shop/Site/Views::category/grid.php')
        	) ) );*/
        	echo $view->renderLayout('RallySport/Site/Views::search/index.php');
        }
        else
        {
	    	echo $view->renderTheme('RallySport/Site/Views::search/index.php');
        }
        return;
    
    	 
    	
    }
    

    
    
}