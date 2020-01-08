<?php 
namespace RallyShop\Admin\Controllers;

class Product extends \Shop\Admin\Controllers\Product
{
    use \Dsc\Traits\Controllers\CrudItemCollection;
    use \Dsc\Traits\Controllers\SupportPreview;
    
    protected $list_route = '/admin/shop/products';
    protected $create_item_route = '/admin/shop/product/create';
    protected $get_item_route = '/admin/shop/product/read/{id}';    
    protected $edit_item_route = '/admin/shop/product/edit/{id}';
    
    protected function getModel($type = 'products')
    {
        switch( $type){
            case 'usercontent':
                $model = new \RallyShop\Models\UserContent;
                break;                
            default:
            case 'products:':
                $model = new \RallyShop\Models\Products;
                break;
        }
        return $model; 
    }
    
    protected function getItem() 
    {
        $id = $this->inputfilter->clean( $this->app->get('PARAMS.id'), 'alnum' );
        
        if (empty($id)) {
        	return $this->getModel();
        }

        try {
            $item = $this->getModel()->setState('filter.id', $id)->getItem();
        } catch ( \Exception $e ) {
            \Dsc\System::instance()->addMessage( "Invalid Item: " . $e->getMessage(), 'error');
            $this->app->reroute( $this->list_route );
            return;
        }

        return $item;
    }
    
    
    
 	public function getCloudinaryImages() 
    {
       $product = $this->getItem();
       
       $images = $product->getImagesForProductFromCloudinary();
       $count = count($images);
       \Dsc\System::addMessage("Found {$count} Images for Cloudinary Tag : " . $product->getCouldinaryTag());
       
       $id = $this->getItem()->get( $this->getItemKey() ).'#tab_tab-images';
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $this->app->reroute( $route );
    }
    
    
    protected function displayCreate() 
    {
        $model = new \RallyShop\Models\Categories;
        $categories = $model->getList();
        $this->app->set('categories', $categories );
        $this->app->set('selected', 'null' );

        $item = $this->getItem();
        
        $selected = array();
        $flash = \Dsc\Flash::instance();

        $use_flash = \Dsc\System::instance()->getUserState('use_flash.' . $this->create_item_route);
        if (!$use_flash) {
            // this is a brand-new create, so store the prefab data
            $flash->store( $item->cast() );
        }        
        
        $input = $flash->old('category_ids');

        if (!empty($input)) 
        {
            foreach ($input as $id)
            {
                $id = $this->inputfilter->clean( $id, 'alnum' );
                $selected[] = array('id' => $id);
            }
        }
        
        $flash->store( $flash->get('old') + array('categories'=>$selected));        

        $all_tags = $this->getModel()->getTags();
        $this->app->set('all_tags', $all_tags );
        
        $this->app->set('meta.title', 'Create Product | Shop');
        
        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );
        
        switch( $item->product_type ) 
        {
        	case "giftcard":
        	case "giftcards":
        	    echo $view->render('Shop\Admin\Views::giftcards/create.php');
        	    break;
        	default:
        	    echo $view->render('Shop\Admin\Views::products/create.php');
        	    break;
        }
    }
    
    protected function displayEdit()
    {
        $item = $this->getItem();

        $flash = \Dsc\Flash::instance();
        $variants = array();
        if ($flashed_variants = $flash->old('variants')) {
        	foreach ($flashed_variants as $variant)
        	{
        	    $key = implode("-", (array) $variant['attributes']);
        	    if (empty($key)) {
        	        $key = $variant['id'];
        	    }
        		$variants[$key] = $variant;
        	}
        }
        $old = array_merge( $flash->get('old'), array( 'variants' => $variants ) );
        $flash->store( $old );
        $modelContent = $this->getModel('usercontent');
        $questions = $modelContent
                            ->setState('filter.type', 'question')
                            ->setState('filter.product_id', $item->id)
                            ->getList();
        $reviews = $modelContent
                            ->setState('filter.type', 'review')
                            ->setState('filter.product_id', $item->id)
                            ->getList();
        $this->app->set('questions', $questions);
        $this->app->set('reviews', $reviews);


        $model = new \Shop\Models\Categories;
        $categories = $model->getList();
        $this->app->set('categories', $categories );
        $this->app->set('selected', 'null' );

        $all_tags = $this->getModel()->getTags();
        $this->app->set('all_tags', $all_tags );

        $this->app->set('meta.title', 'Edit Product | Shop');
        $this->app->set( 'allow_preview', $this->canPreview( true ) );

        $view = \Dsc\System::instance()->get('theme');
        $view->event = $view->trigger( 'onDisplayShopProductsEdit', array( 'item' => $item, 'tabs' => array(), 'content' => array() ) );

        switch( $item->product_type )
        {
        	case "giftcard":
        	case "giftcards":
        	    echo $view->render('Shop\Admin\Views::giftcards/edit.php');
        	    break;
        	default:
        	    echo $view->render('Shop\Admin\Views::products/edit.php');
        	    break;
        }
        
    }

    public function uploadInstallInstructions()
    {
        if (empty($_FILES['file']) || $_FILES['file']['error']) {
            $this->outputJson($this->getJsonResponse([
                'result' => false,
            ]));

            return;
        }

        $product = $this->getItem();

        $cloudFiles = new \Assets\Models\Storage\CloudFiles();
        $cloudFiles->setContainer('install_instructions');

        $localFilename = $_FILES['file']['tmp_name'];
        $remoteFilename = $_FILES['file']['name'];

//        $remoteFilename = preg_replace('/[^a-zA-Z0-9\. ]/', '', strtolower($product->{'tracking.model_number'}));
//        $remoteFilename = str_replace(' ', '_', $remoteFilename) . '.pdf';

        $cloudFiles->createObject($remoteFilename, $localFilename);

        /** @var \OpenCloud\ObjectStore\Resource\DataObject $object */
        $object = $cloudFiles->object;
        $url = (string) $object->getPublicUrl(\OpenCloud\ObjectStore\Constants\UrlType::SSL);

        $product->set('install_instructions', $url);
        $product->store();

        $this->outputJson($this->getJsonResponse([
            'result' => true,
            'message' => $url
        ]));
    }
    
    /**
     * This controller doesn't allow reading, only editing, so redirect to the edit method
     */
    protected function doRead(array $data, $key=null) 
    {
        $id = $this->getItem()->get( $this->getItemKey() );
        $route = str_replace('{id}', $id, $this->edit_item_route );
        $this->app->reroute( $route );
    }
    
    protected function displayRead() {}

    public function syncProduct()
    {
        $product = $this->getItem();
        $model = $product->{'tracking.model_number'};

        \Dsc\Queue::task(
            '\Rally\Controllers\Products::syncSingle',
            [$model],
            [
                'title'=> 'Syncing product from GP ' . $model,
                'batch' => 'local',
                'priority' => 10
            ]
        );

        \Dsc\System::addMessage('Product is being synced from GP. This may take a few minutes.');
    }
}
