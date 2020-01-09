<?php 
namespace JBAShop\Models;

class UserContent extends \Dsc\Mongo\Collections\Nodes
{
    use \Dsc\Traits\Models\Publishable;
    use \Dsc\Traits\Models\Describable;
    use \Dsc\Traits\Models\Voting;
    protected $__translatable = false;
    
    protected $__collection_name = 'shop.usercontent';
    protected $__type = 'review';

    protected $__config = array(
        'default_sort' => array(
            'metadata.created.time' => -1
        ),
    );
    
    protected $__product;
    protected $__user;
    
    public $rating = 1; //  (int) 1-5
    public $images = array(); // array of asset slugs
    
    public $product_id; // \MongoDB\BSON\ObjectID INDEX
    public $variant_id; // string INDEX
    public $user_id; // \MongoDB\BSON\ObjectID INDEX
    public $user_name; // display name for the user in this comment
    public $order_verified = false; // has it been confirmed that this user ordered this product?
    
    public $ip_address = null;
    
    public $videoid = null;
    
    public function populateState()
    {
        parent::populateState();
    
        $system = \Dsc\System::instance();
    
        if ($system->app->get('APP_NAME') == 'site')
        {
            $input = $system->get('input');
    
            /**
             * Handle the sort_by value, which users use to sort the list of products
            */
            $sort_by = $input->get('sort_by', null, 'string');
            $this->handleSortBy($sort_by);
        }
    
        $filter_status = $this->getState('filter.publication_status');

        if(empty($filter_status)) {
            $this->setState('filter.publication_status', 'review');
        }


        if(empty($this->getState('filter.rating')))  {
           $this->setState('filter.rating', null);
        }


        return $this;
    }
    
    public function handleSortBy( $sort_by )
    {
        $system = \Dsc\System::instance();
    
        $default = null;
        $old_state = $system->getUserState($this->context() . '.sort_by');
        $cur_state = (!is_null($old_state)) ? $old_state : $default;
        if ($sort_by && $cur_state != $sort_by)
        {
            $pieces = explode('-', $sort_by);
        } else {
            $pieces = explode('-', $cur_state);
        }
        $sort_by = implode('-', $pieces);
        $this->setState('sort_by', $sort_by);
        $system->setUserState($this->context() . '.sort_by', $sort_by);
    
        switch($pieces[0])
        {

            case "created":

                // Set which price field to use
                $price_field = 'metadata.created.time';

                if (!empty($pieces[1]) && $pieces[1] == 'desc') {
                    $dir = -1;
                }
                else {
                    $dir = 1;
                }
                $this->setState('list.sort', array( $price_field => $dir ) );
                $this->setState('list.order', 'featured');

                break;

            case "rating":
                default:
                if (!empty($pieces[1]) && $pieces[1] == 'asc') {
                    $dir = 1;
                }
                else {
                    $dir = -1;
                }

                $this->setState('list.sort', array( 'rating' => $dir, 'metadata.created.time' => -1));
                $this->setState('list.order', 'rating');
                break;


        }
    
        return $this;
    }
    
    
    
    public static function hasUserReviewed( \Users\Models\Users $user, \Shop\Models\Products $product )
    {
        if (empty($user->id))
        {
            return false;
        }
                
        $has_reviewed = static::collection()->findOne(array(
            'product_id' => $product->id,
            'user_id' => $user->id,
        ));

        if (!$has_reviewed)
        {
            return false;
        }
        
        return new static($has_reviewed);
    }
    
    /**
     * Returns boolean true if user can review product
     * otherwise, returns string error message  
     * 
     * @param \Users\Models\Users $user
     * @param \Shop\Models\Products $product
     * @return string|boolean
     */
    public static function canUserReview( \Users\Models\Users $user, \JBAShop\Models\Products $product )
    {
        if (empty($user->id))
        {
            return false;
        }
                
        $settings = \Shop\Models\Settings::fetch();        
        switch ($settings->{'reviews.eligibile'}) 
        {
            case "identified":
                
                // has the user already reviewed it?
                $has_reviewed = static::collection()->count(array(
                    'product_id' => $product->id,
                    'user_id' => $user->id,
                ));
                
                if (!$has_reviewed)
                {
                    return true;
                }
                
                break;
            case "purchasers":
            default:
                
                // has the user purchased this item?
                if (\JBAShop\Models\Customers::hasUserPurchasedProduct( $user, $product ))
                {
                    // has the user already reviewed it?
                    $has_reviewed = static::collection()->count(array(
                        'product_id' => $product->id,
                        'user_id' => $user->id,
                    ));
                
                    if (!$has_reviewed)
                    {
                        return true;
                    }
                }
                
                break;
        }
         
        return false;
    }
    
    /**
     * Add images to this review
     * 
     * @param unknown $images $_FILES array from POST
     * @return \Shop\Models\ProductReviews
     */
    public function addImages( $images=array() )
    {
        foreach ($images as $file_upload) 
        {
            try 
            {
                $asset = \Shop\Models\AssetsProductReviews::createFromUpload( $file_upload );
                $this->images[] = $asset->slug;
            }
            catch (\Exception $e) 
            {
                $this->setError( $e->getMessage() );
            }
        }
        
        return $this->store();
    }

    /**
     * Get various data sets/values related to a product's reviews, 
     * including average rating, count, images, etc
     * 
     * @param \Shop\Models\Products $product
     * @return multitype:
     */
    public static function forProduct(\JBAShop\Models\Products $product, $return_type='paginated' )
    {
        $return = null;
        
        switch ($return_type) 
        {
            case "image_count":
            
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('filter.has_image', true)
                ->getCount();
            
                break;            
            case "images":
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('filter.has_image', true)
                ->getList();
                
                break;
            case "avg_rating":
            
                $conditions = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->conditions();
                
                $agg = static::collection()->aggregate(array(
                    array(
                        '$match' => $conditions
                    ),
                    array(
                        '$group' => array(
                            '_id' => '$product_id',
                            'avgRating' => array( '$avg' => '$rating' )
                        )
                    ),
                ));

                if (!empty($agg['ok']) && !empty($agg['result']))
                {
                    $return = $agg['result'][0]['avgRating'];
                }                
            
                break;            
            case "count":
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->getCount();
                
                break;
            default:
                
                $return = (new static)->setState('filter.product_id', $product->id)
                ->setState('filter.published_today', true)
                ->setState('filter.publication_status', 'published')
                ->setState('list.limit', 10)
                ->paginate();
                
                break;
        }
        
        return $return;
    }
    
    protected function fetchConditions()
    {
        parent::fetchConditions();
        
        $this->describableFetchConditions();
        $this->publishableFetchConditions();
        
        $filter_product_id = $this->getState('filter.product_id');
        if (!empty($filter_product_id))
        {
            $this->setCondition('product_id', new \MongoDB\BSON\ObjectID( (string) $filter_product_id ) );
        }
        
        $filter_type = $this->getState('filter.type');
        if (!empty($filter_type))
        {
            $this->setCondition('type', $filter_type );
        }
        
        $filter_product_ids = $this->getState('filter.product_ids');
        if (!empty($filter_product_ids))
        {
            if (!is_array($filter_product_ids)) 
            {
                $filter_product_ids = array();
                foreach (explode(",", $this->getState('filter.product_ids')) as $product_id) 
                {
                    $filter_product_ids[] = new \MongoDB\BSON\ObjectID( (string) $product_id );
                }
            }
            $this->setCondition('product_id', array( '$in' => $filter_product_ids ) );
        }
        
        $filter_user_id = $this->getState('filter.user_id');
        if (!empty($filter_user_id))
        {
            $this->setCondition('user_id', new \MongoDB\BSON\ObjectID( (string) $filter_user_id ) );
        }
        
        $filter_has_image = $this->getState('filter.has_image');
        if (!empty($filter_has_image))
        {
            $this->setCondition('images', array(
                '$not' => array(
                    '$size' => 0
                )
            ));
        }        
        
        $filter_rating = $this->getState('filter.rating');

        
        if (!empty($filter_rating) && $filter_type == 'review' )
        {
            $this->setCondition('rating', (int) $filter_rating);
        }
        
        
        return $this;
    }
    
    protected function beforeCreate()
    {
        $identity = \Dsc\System::instance()->get('auth')->getIdentity()->reload();
        
        $this->user_id= $identity->id;
        
        $this->user_name= $identity->{'profile.screen_name'};

        $this->verifyOrdered();
    
    
        return parent::beforeCreate();
    }


    public function verifyOrdered()
    {
        if ($this->type == 'review') {
            /** @var \MongoDb $mongo */
            $mongo = \Dsc\System::instance()->get('mongo');

            /** @var \MongoCollection $collection */
            $ordersCollection = \Dsc\Mongo\Helper::getCollection('shop.orders');

            $count = $ordersCollection->count([
                'status' => 'closed',
                'user_id' => new \MongoDB\BSON\ObjectID((string) $this->user_id),
                'items.product_id' => new \MongoDB\BSON\ObjectID((string) $this->product_id)
            ]);

            if ($count > 0) {
                $this->order_verified = true;
            }
        }
    }
    
    protected function beforeValidate()
    {
        if(empty($this->{'publication.status'})) {
            $this->{'publication.status'} = 'review';
        }


        $this->describableBeforeValidate();
           
        $this->rating= (int) $this->rating;
        
        $product = (new \Shop\Models\Products)->setCondition('_id', $this->product_id)->getItem();
    
        if(!empty($product->id)) {
            $this->set('product_id', $product->id);
            $this->set('product_title', $product->title);
            $this->set('product_slug', $product->slug);
            $this->set('part_number', $product->{'tracking.model_number'});
        }

        return parent::beforeValidate(); 
    }
    
    protected function beforeSave()
    {
        $this->publishableBeforeSave();
        
        if(!empty($this->id)) {
            //if we have a problem of questions failing to save we and move this to afterSave
            $current = (new static)->load(array('_id' => new \MongoDB\BSON\ObjectID( (string) $this->id ) ));

            if($this->type == 'question' && $current->{'publication.status'} != 'published' && $this->{'publication.status'} == 'published' ) {
                $this->sendCustomerServiceEmail();
            }
        }
 
        
        $this->copy = strip_tags( str_replace(['\n','\n\r'], '<br>', $this->copy), '<br><p>' );
        $this->rating = (int) $this->rating;

        
        return parent::beforeSave();
    }
    
    
    
    
 
    
    
    /**
     * Gets the associated user object
     *
     * @return unknown
     */
    public function user()
    {
        if (empty($this->__user)) 
        {
            $this->__user = (new \Users\Models\Users)->load(array('_id'=>$this->user_id));
        }
        
        return $this->__user;
    }
    
    
    public function votingAcceptVote($vote, $user = null)
    {
        if(!empty($user) && $user instanceof \Users\Models\Users ) {
            //check the votes collection for this same vote
            $db =  \Dsc\System::instance()->get('mongo');
            $collection = \Dsc\Mongo\Helper::getCollection('voting.votes');
            $doc = $collection->findOne(array('object_id' => $this->_id, 'user_id' => $user->id));

            $votes = $this->votes;
            if(empty($doc)) {
                //accept the vote
                $collection->insert(array('object_id' => $this->_id, 'user_id' => $user->id, 'vote' => $vote, "time" => new \MongoDB\BSON\UTCDateTime()   ));

                //update the document vote counts
                if(!empty($votes[$vote])) {
                    $votes[$vote] = (int) $votes[$vote] + 1;
                } else {
                    $votes[$vote] = (int) 1;
                }
                $this->set('votes', $votes)->store();


            } else {
                //minus the old vote
                if(!empty($votes[$doc['vote']])) {
                    $votes[$doc['vote']]--;
                    if( (int) $votes[$doc['vote']] <= 0) {
                        $votes[$doc['vote']] = (int) 0;
                    }
                }

                //add the new vote
                if(!empty($votes[$vote])) {
                    $votes[$vote] = (int) $votes[$vote] + 1;
                } else {
                    $votes[$vote] = (int) 1;
                }

                $this->set('votes', $votes)->store();

                $collection->updateOne(['_id' => $doc['_id']], array('object_id' => $this->_id, 'user_id' => $user->id, 'vote' => $vote, "time" => new \MongoDB\BSON\UTCDateTime()   ));

            }

    
        }




        return $votes;

    }
    
    
    public function votingAcceptAnswerVote($vote, $answer, $user = null)
    {
        if(!empty($user) && $user instanceof \Users\Models\Users ) {
            //check the votes collection for this same vote
            $db =  \Dsc\System::instance()->get('mongo');
            $collection = \Dsc\Mongo\Helper::getCollection('voting.votes');
            $doc = $collection->findOne(array('object_id' => $this->_id, 'answer_id' => $answer, 'user_id' => $user->id));
    
            if(empty($doc)) {
                //accept the vote
                $collection->insert(array('object_id' => $this->_id, 'user_id' => $user->id, 'answer_id' => $answer, 'vote' => $vote, "time" => new \MongoDB\BSON\UTCDateTime()   ));

                //update the document vote counts
                $votes = $this->votes;
                if(!empty($votes[$vote])) {
                    $votes[$vote] = (int) $votes[$vote] + 1;
                } else {
                    $votes[$vote] = (int) 1;
                }
                $this->set('votes', $votes)->store();


            } else {

                echo $doc['vote']; die();
                //minus the old vote
                if(!empty($votes[$doc['vote']])) {
                    $votes[$doc['vote']] = (int) $votes[$doc['vote']] - 1;
                    if($votes[$doc['vote']] <= 0) {
                        $votes[$doc['vote']] = (string) 0;
                    }
                }
                //add the new vote
                if(!empty($votes[$vote])) {
                    $votes[$vote] = (int) $votes[$vote] + 1;
                } else {
                    $votes[$vote] = (int) 1;
                }
                $this->set('votes', $votes)->store();

                $collection->updateOne(['_id' => $doc['_id']], array('object_id' => $this->_id, 'user_id' => $user->id, 'answer_id' => $answer, 'vote' => $vote, "time" => new \MongoDB\BSON\UTCDateTime()   ));
            }
    
        }

 


        return $votes;

    }
    
    
    /**
     * Gets the associated product object
     *
     * @return unknown
     */
    public function product()
    {
        if (empty($this->__product)) 
        {
            $this->__product = (new \JBAShop\Models\Products)->load(array('_id'=>$this->product_id));
        }
    
        return $this->__product;
    }
    
    public static function queueEmailForOrder( \Shop\Models\Orders $order )
    {
        $settings = \Shop\Models\Settings::fetch();
    
        if (empty($settings->{'reviews.enabled'}))
        {
            return;
        }
        
        $days_from_now = $settings->{'reviews.email_days'};
        if (empty($days_from_now))
        {
            return;
        }
        
        $email = $order->user_email;
        
        // Schedule the email to be sent $days_from_now
        $days_from_now = abs($days_from_now);
        $time = time() + $days_from_now * 86400;
        \Dsc\Queue::task('\Shop\Models\ProductReviews::sendEmailForOrder', array(
            (string) $order->id
        ), array(
            'title' => 'Request product reviews from ' . $email . ' for order ' . $order->id,
            'when' => $time,
            'email' => $email
        ));        
    }
    
    public static function sendEmailForOrder( $order_id )
    {
        $settings = \Shop\Models\Settings::fetch();
    
        if (empty($settings->{'reviews.enabled'}))
        {
            return;
        }
    
        $days_from_now = $settings->{'reviews.email_days'};
        if (empty($days_from_now))
        {
            return;
        }
    
        // load the order
        $order = (new \JBAShop\Models\Orders)->setState('filter.id', $order_id)->getItem();
        if (empty($order->id))
        {
            return;
        }
                
        // check which products from the order have not been reviewed.
        // If all have been reviewed, don't send the email.
        $product_ids = array();
        foreach ($order->items as $item) 
        {
            $key = (string) $item['product_id'];
            $product_ids[$key] = $item['product_id']; 
        }
        
        $products = array_values($product_ids);        
        $product_reviews = static::collection()->find(array(
            'product_id' => array(
                '$in' => $products
            ),
            'user_id' => $order->user_id,
        ));
        
        foreach ($product_reviews as $doc) 
        {
            $key = (string) $doc['product_id'];
            unset($product_ids[$key]);
        }
        
        // at this point, $product_ids should have the list of unreviewed products from this order
        if (empty($product_ids)) 
        {
            return;
        }
        
        // so get an array of actual products
        $products = array();
        foreach ($product_ids as $product_id) 
        {
            foreach ($order->items as $item)
            {
                if ($item['product_id'] == $product_id) 
                {
                    $products[] = $item;
                } 
            }
        }
        
        if (empty($products)) 
        {
            return;
        }
        
        // get the recipient's email and send the email
        $recipients = array(
            $order->user_email
        );
        
        if (empty($recipients))
        {
            return;
        }
        
        $subject = $settings->get('reviews.email_subject');
        if (empty($subject)) 
        {
            $subject = "Please review your recent purchases!";
        }
        
        $user = $order->user();
        
        \Base::instance()->set('user', $user);
        \Base::instance()->set('products', $products);
        
        $html = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_html/review_products.php');
        $text = \Dsc\System::instance()->get('theme')->renderView('Shop/Views::emails_text/review_products.php');
        
        foreach ($recipients as $recipient)
        {
            \Dsc\System::instance()->get('mailer')->send($recipient, $subject, array(
                $html,
                $text
            ));
        }
        
    }
    
    function timeSince($ts = null)
    {

        if(empty($ts)) {
            $ts = $this->{'metadata.created.time'};
        }


        $etime = time() - $ts;
    
        if ($etime < 1)
        {
            return '0 seconds';
        }
    
        $a = array( 365 * 24 * 60 * 60  =>  'year',
                30 * 24 * 60 * 60  =>  'month',
                24 * 60 * 60  =>  'day',
                60 * 60  =>  'hour',
                60  =>  'minute',
                1  =>  'second'
        );
        $a_plural = array( 'year'   => 'years',
                'month'  => 'months',
                'day'    => 'days',
                'hour'   => 'hours',
                'minute' => 'minutes',
                'second' => 'seconds'
        );
    
        foreach ($a as $secs => $str)
        {
            $d = $etime / $secs;
            if ($d >= 1)
            {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
            }
        }
    }
    
    
    public static function getCountFromProduct($id) {

        $ratings = array(5,4,3,2,1);
        $id = new \MongoDB\BSON\ObjectID ((string) $id);


        $collection = (new static)->collection();
        $counts = array();
        $counts['total'] = $collection->count(array('product_id' => $id , 'type' => 'review', 'publication.status' => 'published'));

        $results = [];
        foreach ($ratings as $number) {
            $results[$number] = $collection->count(['product_id' =>$id , 'type' => 'review', 'publication.status' => 'published', '$or' => [ ['rating' => $number], ['rating' => (string) $number]] ]);
        }


        $totalvalue = 0;
        $totalnumber  = 0;
        foreach ($results as $key => $value) {
            $totalnumber = $totalnumber + $value;
            $totalvalue += $key * $value;
        }

        if ($totalnumber !== 0 && $totalvalue !== 0 ) {
            $overall = round($totalvalue / $totalnumber, 1);
        } else {
            $overall = 0;
        }

        $counts['overall'] = $overall;

        $counts['ratings'] = $results;


        return $counts;
    }
    
    public function acceptAnswerVote($vote, $answer_id, $user = null)
    {


        if(!empty($user) && $user instanceof \Users\Models\Users ) {
            //check the votes collection for this same vote
            $db =  \Dsc\System::instance()->get('mongo');
            $collection = \Dsc\Mongo\Helper::getCollection('voting.votes');
            $doc = $collection->findOne(array('object_id' => $answer_id, 'user_id' => $user->id));
    
            if(empty($doc)) {
                //accept the vote
                $collection->insert(array('object_id' => $answer_id, 'user_id' => $user->id, 'vote' => $vote, "time" => new \MongoDB\BSON\UTCDateTime()   ));
            } else {
                throw new \Exception('User has already voted');
            }
    
        }

        $answers = $this->get('answers');


        foreach ($answers as $key => $answer) {
            if($answer['answer_id'] == $answer_id) {

                //update the document vote counts
                $votes = $answer['votes'];
                if(!empty($votes[$vote])) {
                    $votes[$vote] = (int) $votes[$vote] + 1;
                } else {
                    $votes[$vote] = (int) 1;
                }
                $answer['votes'] = $votes;
                $answers[$key] = $answer;
                break;
            }
        }


        $this->set('answers', $answers)->store();


        return $votes;

    }
    
    
    public function emailSupport() {
        //send email

        $product = $this->product();

        \Base::instance()->set('product',$product);
        \Base::instance()->set('usercontent',$this);
        $user = $this->user();
        \Base::instance()->set('user',$user);

        \Dsc\System::instance()->get('theme')->setTheme('Theme', \Base::instance()->get('PATH_ROOT') . 'apps/Theme/');
        \Dsc\System::instance()->get('theme')->registerViewPath(\Base::instance()->get('PATH_ROOT') . 'apps/Theme/Views/', 'Theme/Views');

        $html = \Dsc\System::instance()->get( 'theme' )->renderView( 'Theme/Views::emails/shop/usercontent/email_support.php' );

        $subject = "Regarding your product {$product->title} - {$product->get('tracking.model_number')}";
        $email = 'customerservice@rallysportdirect.com';
        //$email = 'chris.french@rallysportdirect.com';

        $emailSent = \Dsc\System::instance()->get('mailer')->send($email, $subject, array($html), $user->email(true), $user->fullName() );
        //add flag in document about when emailed

        $this->set('ticket_created', time());
        $this->save();
    }
    
    public function sendCustomerServiceEmail() {
        //send email
        try {
            $email = 'customerservice@rallysportdirect.com';
            $mailer = \Dsc\System::instance()->get('mailer');
            if ($content = $mailer->getEmailContents('rallyshop.usercontent_question_to_customer_service', array(
                    'usercontent' => $this
            ))) {
                $mailer->sendEvent( $email, $content);
            }
            $this->set('emails.tocsr', \time())->store();
        } catch (\Exception $e) {
            //i guess do nothing for now
        }
    }
    
    
    
    
    
    protected function afterCreate()
    {
        $flash = \Dsc\Flash::instance();
        $flash->store([]);

        parent::afterCreate();
    }
    
    protected function afterUpdate()
    {
        parent::afterUpdate();
    }
    
    protected function afterDelete()
    {

        //SHOULD WE ALSO GO DELETE ASSETS

        //SHOULD WE EMAIL THE CUSTOMER?

        parent::afterDelete();
    }
    
    protected function afterSave()
    {
        //add a time range to avoid sending emails to vendors about old questions
        $timerange = (new \DateTime())->sub(new \DateInterval('P10D'))->getTimestamp();

        if($this->type == 'question'
            && $this->{'publication.status'} == 'published'
            && empty($this->{'emails.tovendor'})
            && $this->{'metadata.created.time'} > $timerange
        ) {
            $this->sendVendorEmail();
        }

        parent::afterSave();
    }
    
    
    public static function updateScreenName($user_id, $screenName) {

        $collection = (new static)->collection();
        //updates the docs created by this user
        $collection->updateMany(['user_id'=> new \MongoDB\BSON\ObjectID((string) $user_id)], ['$set' => ['user_name' =>  $screenName]]);


    }
    

    public static function updateForUser($userId) {
    
        //UPDATE POSTS BY THIS USER
        $list = (new static)->collection()->find(['user_id' => new \MongoDB\BSON\ObjectID($userId)], ['sort' => ['_id' => -1]]);
        foreach($list as $content) {
            $content = (new static)->bind($content);
            $content->updateUser();
            $content->updateProduct();
        }

        //UPDATE ANSWERS BY THIS USER
        $list = (new static)->collection()->find(['answers.user_id' => new \MongoDB\BSON\ObjectID($userId)], ['sort' => ['_id' => -1]]);
        foreach($list as $content) {
            $content = (new static)->bind($content);
            $content->updateUser();
            $content->updateProduct();
        }

    }
    
    
    public static function updatePartNumber($id, $part_number) {
    
        $item = (new static)->load(['_id' => new \MongoDB\BSON\ObjectID((string)$id)]);

        if(!empty($item)) {
            $item->part_number = $part_number;
            $item->updateProduct();
        }
    }
    
    
    
    public function updateUser() {

        if(!empty($this->customer_number)) {
            $user = (new \JBAShop\Models\Customers)->setState('filter.customer_number', $this->customer_number)->getItem();
        } else {
            $user = (new \JBAShop\Models\Customers)->setState('filter.id', $this->user_id)->getItem();
        }


        if(!empty($user->id)) {
            $this->set('user_id', $user->id);

            if(!empty($user->{'profile.screen_name'})) {
                $this->set('user_name', $user->{'profile.screen_name'});
            } else {
                $this->set('user_name', $user->username);

            }
            $this->set('role', $user->{'profile.social_role'});
        } else {
    
        }
    
        if(!empty($this->answers)) {
            $answers = $this->answers;
            foreach ($this->answers as $key => $answer) {

                if(!empty($answer['customer_number'])) {
                    $user = (new \JBAShop\Models\Customers)->setState('filter.customer_number', $answer['customer_number'])->getItem();
                } else {
                    $user = (new \JBAShop\Models\Customers)->setState('filter.id', $answer['user_id'])->getItem();
                }

                if(!empty($user->id)) {
                    $answer['user_id']= $user->id;
                    if(!empty($user->{'profile.screen_name'})) {
                        $answer['username']= $user->{'profile.screen_name'};
                    } else {
                        $answer['username']= $user->username;
                    }

                    $answer['role']= $user->{'profile.social_role'};
                } else {
    
                }
                $answers[$key] = $answer;
            }
            $this->set('answers', $answers);
        }

        $this->store();

    }
    
    public function updateProduct() {
    
        $product = (new \Shop\Models\Products)->setCondition('tracking.model_number', $this->part_number)->getItem();
    
        if(!empty($product->id)) {
            $this->set('product_id', $product->id);
            $this->set('product_title', $product->title);
            $this->set('product_slug', $product->slug);
        }
    
        $this->store();
    
    }
    
    public function updateAnswers () {
        $list = (new static)->collection()->find(array('type' => 'question'));
        foreach($list as $content) {
            $answers = $content['answers'];
            foreach($answers as $key => $answer) {
                if(empty($answer['answer_id'])) {
                    $answer['answer_id'] = new \MongoDB\BSON\ObjectID();
                    $answers[$key] = $answer;
                }
            }
    
            $content = (new static)->bind($content);
            $content->set('answers',$answers)->save();
        }
    }
       
    public static function outputStars($number, $max = 5, $spaces = false)
    {
        $stars = '';

        for ($i = 1; $i <= $number; $i++) {
            $stars .= '<span class="fa fa-star" data-rating="1"></span>' . ($spaces ? ' ' : '');
        }

        if (strpos($number,'.')) {
            $stars .= '<span class="fa fa-star-half-empty" data-rating=".5"></span>' . ($spaces ? ' ' : '');
            $i++;
        }

        while ($i <= $max) {
            $stars .= '<span class="fa fa-star-o" data-rating="0"></span>' . ($spaces ? ' ' : '');
            $i++;
        }

        return $stars;
    }

    function showImageThumb($id, $options = array()) {
        return static::image_thumb($id, $options);
    }

    function showImage($id, $options = array()) {
        return static::image($id, $options);
    }

    public static function image($id, array $options = array()) {
        $options['transformation'] = 'user_content';
        $options['format'] = 'jpg';
        return cloudinary_url($id, $options);
    }

    public static function image_thumb($id,  array $options = array()) {
        $options['transformation'] = 'user_content_thumb';
        $options['format'] = 'jpg';
        return cloudinary_url($id, $options);
    }

    public static function media_thumb($id,  array $options = array()) {
        $options['transformation'] = 'user_media_thumb';
        $options['format'] = 'jpg';
        return cloudinary_url($id, $options);
    }
    
}
