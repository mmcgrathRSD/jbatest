<?php 
namespace JBAShop\Site\Controllers;

class Category extends \Shop\Site\Controllers\Category
{ 
	use \Dsc\Traits\Controllers\SupportPreview;
	
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
	
	public function index()
    {
        $this->registerName(__METHOD__);
        $this->app->set('page', 'category');

    	$url_params = $this->app->get('PARAMS');
    	$this->app->set('comparable', false);
    	$param = $this->inputfilter->clean( $this->app->get('PARAMS.*'), 'string' );
    	$pieces = explode('?', $param);
    	$path = $pieces[0];
    	$products_model = $this->model('products');
        $sales_channel = \Base::instance()->get('sales_channel');
    	$specs = null;


    	try {
    		//get the current category and lets check to see if it is parent or a direct cat.
    	    $category = $this->model('categories')->setCondition('$and', [
                    ['path' => '/'. $path],
                    ['$or' => [
                            ['sales_channels.slug' => $sales_channel],
                            ['sales_channels.0' => ['$exists' => false]]
                        ]
                    ]
                ]
            )->getItem();
            
            if (empty($category->id)) {
    	    	throw new \Exception('No Category');
    	    }

            $this->app->set('meta.title', $category->seoTitle());
            $this->app->set('meta.description', $category->seoDescription());
            
            if( !empty($category->{'seo.meta_keywords'})){
                $this->app->set('meta.keywords', implode(',', $category->{'seo.meta_keywords'}) );
            }

            $this->app->set('og.title', $category->seoTitle()) ;
            $this->app->set('og.type', 'product.group');
            if(!empty($category->{'category_image.slug'})) {
            	$this->app->set('og.image',\Shop\Models\Assets::render($category->{'category_image.slug'}));
            }
            $this->app->set('og.url', \Dsc\Url::full());
            $this->app->set('og.description', $category->seoDescription());

            $this->app->set('canonical', $category->url(true));

    	    $state =  $products_model->emptyState()->populateState()->getState();

            $subcategories = \Shop\Models\Categories::getCategoryFilters($category,$state);
            $this->app->set('hierarchical_refinement', end($category->getHierarchy()));
            $this->app->set('children', $category->children());
            if (empty(\Base::instance()->get('disable_order_tracking', false)))
            {
                $identity = $this->auth->getIdentity();
                $catProds = [];
                //get the newest 3 products with this category.
                $catProdsCursor = $products_model::collection()->find([
                        'categories.slug' => $category->get('slug'),
                        'publication.status' => 'published',
                        'publication.sales_channels.slug' => \Base::instance()->get('sales_channel')
                    ],
                    [
                        'sort' => ['first_publication_time' => -1],
                        'limit' => 3
                    ]);
                foreach($catProdsCursor as $key => $item){
                    $product = new \Shop\Models\Products($item);
                    $catProds[] = [
                        'id' => $product->get('tracking.model_number'),
                        'name' => $product->get('title'),
                        'price' => $product->price(),
                        'category' => $category->get('title'),
                        'position' => $key,
                    ];
                }
                
                $this->app->set('gtm.dataLayer', [
                    [
                        "customerLoggedIn" => !empty($identity),
                        "customerEmail" => !empty($identity) ? $identity->email : '',
                        "customerGroupId" => "1",
                        "customerGroupCode" =>  !empty($identity) ? "GENERAL" : "NOT LOGGED IN",
                        "categoryId" => (string)$category->id,
                        "categoryName" => $category->title,
                        "categorySize" => \Shop\Models\Products::collection()->count(['categories.slug' => $category->get('slug')]),
                        "categoryProducts" => $catProds,
                        "pageType" => "catalog/category/view",
                        "site_type" => "d"
                    ]
                ]);
                $this->app->set('gtm.event', ['ecommerce' => ['impressions' => $catProds]]);
            }
            

    	} catch (\Exception $e) {
    		$this->app->error('404');
    	}

        $view = \Dsc\System::instance()->get('theme');
        $this->app->set('item', $category);

		echo $view->renderTheme('Search/Site/Views::category/index.php');
    }

      
}
