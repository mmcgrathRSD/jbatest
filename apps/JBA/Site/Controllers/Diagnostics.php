<?php
namespace JBA\Site\Controllers;

class Diagnostics extends \Dsc\Controller
{

    public function run()
    {
        echo 'French broke these out into individual jobs you need to run them directly see the JBA/Site/Routes/Cli.php file for the routes';
    }

    /**
     * Starts new Relic background job tracking
     *
     * @param string $method
     */
    private function setNewRelicReporting($method)
    {
        if (extension_loaded('newrelic')) {
            newrelic_start_transaction();
            newrelic_background_job(true);
            newrelic_name_transaction($method);
        }
    }

    /**
     * If there was an error we use new relic for tracking to end the email spam
     *
     * @param string $message
     * @param
     *            \Exception trace
     */
    private function sendNewRelicError($message, $exception = null)
    {
        if (extension_loaded('newrelic')) {
            newrelic_notice_error($message, $exception);
        }
    }

    /**
     * ends the transaction and sends the data to new relic
     */
    private function endNewRelic()
    {
        if (extension_loaded('newrelic')) {
            newrelic_end_transaction();
        }
    }

    /**
     * This method create a rich relevance XML feed and FTP its it over to their servers
     */
    public function createRichRelevanceProductFeeds()
    {
        $this->setNewRelicReporting(__METHOD__);

        try {
            (new \JBAShop\Services\RichRevelance())->generateFeeds()->compressFeeds();
        } catch (\Exception $e) {
            $this->sendNewRelicError('Error Creating Rich Relevance Feed', $e);
        }
        $this->endNewRelic();
    }

   /**
     * This method create a google product feeds and puts it on our server to be fetched
     */
    public function createGoogleProductFeeds()
    {
        $this->setNewRelicReporting(__METHOD__);
        try {
            //Get all sales channels
            $channels = (new \Shop\Models\SalesChannels())->getItems();
            //target the analytics file path from config.
            $path = \Base::instance()->get('analytics.file_path'); 
            //feed file paths
            $feeds = [];
            //for each channel generate a google product feed xml file.
            foreach($channels as $channel){
                //add file path to feeds so we can compress.
                $feeds[] = (new \JBAShop\Services\GoogleProductsFeed($path))->generateFeeds($channel->get('slug') . '_products');
            }
            //Compress feeds if any
            (new \JBAShop\Services\GoogleProductsFeed())->compressFeeds($feeds);
        } catch (\Exception $e) {
            $this->sendNewRelicError('Error Creating Google Product Feed', $e);
        }
        $this->endNewRelic();
    }

    public function updateFlags()
    {
        $this->setNewRelicReporting(__METHOD__);
        try {
            $mongo = \Dsc\System::instance()->get('mongo');
            /** @var \MongoCollection $collection */
            $collection = \Dsc\Mongo\Helper::getCollection('shop.products');
            $thirtyDays = strtotime('-30 days');

            $collection->updateMany([
                'flag.enabled' => 1,
                'flag.text' => 'NEW',
                'first_publication_time' => [
                    '$lt' => $thirtyDays
                ]
            ], // More than 30 days ago
[
                '$set' => [
                    'flag.text' => '',
                    'flag.enabled' => 0
                ]
            ]);

            $collection->updateMany([
                'flag.enabled' => [
                    '$nin' => [
                        true,
                        'true',
                        '1',
                        1,
                        'yes'
                    ]
                ],
                'first_publication_time' => [
                    '$gt' => $thirtyDays
                ]
            ], // Less than 30 days ago
[
                '$set' => [
                    'flag.text' => 'NEW',
                    'flag.enabled' => 1
                ]
            ]);
        } catch (\Exception $e) {
            $this->sendNewRelicError('Error During the New  Product Flags', $e);
        }
        $this->endNewRelic();
    }

    public function buildSiteMap()
    {
        $this->setNewRelicReporting(__METHOD__);
        $salesChannels = (new \Shop\Models\SalesChannels)->getItems();

        foreach($salesChannels as $salesChannel){
            try {
                $domain = "http:///www.{$salesChannel->get('slug')}.com/";
                $sitemap = new \Dsc\Sitemap();
                $sitemap->setDomain($domain);
                $sitemap->setPath("/var/www/static.{$salesChannel->get('slug')}.com/sitemaps/");
                $sitemap->setFilename('google');
                
                $xmldomain = "https://static.{$salesChannel->get('slug')}.com/";
                $routes = [
                    'base' => []
                ];
                $event = \Dsc\System::instance()->trigger('siteMapRegisterRoutes', [
                    'routes' => $routes,
                    'sitemap' => $sitemap,
                    'salesChannel' => $salesChannel
                ]);

                $sitemap = $event->getArgument('sitemap');
                $sitemap->createSitemapIndex($xmldomain . 'sitemaps/', 'Today');
            } catch (\Exception $e) {
                $this->sendNewRelicError('Error Build Site Map', $e);
            }
        }

        $this->endNewRelic();
    }

    public function sendShopperApprovedEmails()
    {
        $this->setNewRelicReporting(__METHOD__);
        try {
            $dateTime = new \DateTime();
            $now = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P21D'));

            $DaysAgoStart = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P2D'));
            $DaysAgoEnd = $dateTime->getTimestamp();

            $orders = (new \Shop\Models\Orders)
                ->setCondition('customer.price_level', [
                    '$not' => new \MongoDB\BSON\Regex('whole', 'i')
                ])
                ->setCondition('shipments_shipdate', [
                    '$lte' => $DaysAgoStart,
                    '$gte' => $DaysAgoEnd
                ])
                ->setCondition('emails.shopper_approved', [
                    '$exists' => false
                ])
                ->getItems();

            $splitOrders = [];
            foreach ($orders as $order) {
                if (preg_match('/\.\d+$/', $order->original_order_number, $matches)) {
                    $splitOrders[substr($order->original_order_number, 0, -(strlen($matches[0])))] = '';
                    continue;
                }
                
                $this->sendShopperApprovedEmail($order);
            }

            $splitOrders = array_filter(array_keys($splitOrders));
            if (!empty($splitOrders)) {
                $orders = (new \Shop\Models\Orders)
                    ->setCondition('number', [
                        '$in' => $splitOrders
                    ])
                    ->setCondition('emails.shopper_approved', [
                        '$exists' => false
                    ])
                    ->getItems();
    
                foreach ($orders as $order) {
                    $regex = new \MongoDB\BSON\Regex($order->number . '\.\d+$', 'i');
                    
                    $splits = \Shop\Models\Orders::collection()->count([
                        'status' => ['$ne' => 'cancelled'],
                        '$or' => [
                            [ 'original_order_number' => $regex],
                            [ 'number' => $regex ]
                        ]
                    ]);
                    
                    $shippedSplits = \Shop\Models\Orders::collection()->count([
                        'status' => ['$ne' => 'cancelled'],
                        'original_order_number' => $regex,
                        'shipments_shipdate' => [ '$gt' => 0 ]
                    ]);
                    
                    if ($shippedSplits >= $splits) {
                        $this->sendShopperApprovedEmail($order);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->sendNewRelicError('Error Send Shopper Approved Emails', $e);
        }
        $this->endNewRelic();
    }
    
    private function sendShopperApprovedEmail($order)
    {
        $mailer = \Dsc\System::instance()->get('mailer');
        
        if (empty($order->original_order_number)) {
            $order->original_order_number = $order->number;
        }
        
        if ($content = $mailer->getEmailContents('rallyshop.request_review_shopper_approved', [
            'order' => $order
        ])) {
            // $order->user_email
            $mailer->sendEvent($order->user_email, $content);
            $order
                ->set('emails.shopper_approved', \time())
                ->store();
        }
    }

    public function sendReviewProducts()
    {
        $this->setNewRelicReporting(__METHOD__);
        try {
            $dateTime = new \DateTime();
            $now = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P35D'));
            $DaysAgoStart = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P2D'));
            $DaysAgoEnd = $dateTime->getTimestamp();

            $ordersUS = (new \Shop\Models\Orders())->setCondition('$and', array(
                array(
                    'shipments_shipdate' => array(
                        '$lte' => $DaysAgoStart
                    )
                ),
                array(
                    'shipments_shipdate' => array(
                        '$gte' => $DaysAgoEnd
                    )
                )
            ))
                ->setCondition('emails.request_review', [
                '$exists' => false
            ])
                ->setCondition('shipping_address.country', 'US')
                ->getList();

            foreach ($ordersUS as $order) {
                $mailer = \Dsc\System::instance()->get('mailer');
                if ($content = $mailer->getEmailContents('shop.review_products', array(
                    'order' => $order
                ))) {
                    $mailer->sendEvent($order->user_email, $content);
                    $order->set('emails.request_review', \time())->store();
                }
            }
            /*
             * INTERNATIONAL PRODUCT REVIEWS
             */
            $dateTime = new \DateTime();
            $now = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P47D'));

            $DaysAgoStart = $dateTime->getTimestamp();
            $dateTime->sub(new \DateInterval('P3D'));
            $DaysAgoEnd = $dateTime->getTimestamp();

            $ordersUS = (new \Shop\Models\Orders())->setCondition('$and', array(
                array(
                    'shipments_shipdate' => array(
                        '$lte' => $DaysAgoStart
                    )
                ),
                array(
                    'shipments_shipdate' => array(
                        '$gte' => $DaysAgoEnd
                    )
                )
            ))
                ->setCondition('emails.request_review', [
                '$exists' => false
            ])
                ->setCondition('shipping_address.country', [
                '$ne' => 'US'
            ])
                ->getList();

            foreach ($ordersUS as $order) {
                $mailer = \Dsc\System::instance()->get('mailer');
                if ($content = $mailer->getEmailContents('shop.review_products', array(
                    'order' => $order
                ))) {
                    $mailer->sendEvent($order->user_email, $content);
                    $order->set('emails.request_review', \time())->store();
                }
            }
        } catch (Exception $e) {
            $this->sendNewRelicError('Error Send Review Products Emails', $e);
        }
        $this->endNewRelic();
    }

    public function sendRallyRecap($preview = false)
    {
        $this->setNewRelicReporting(__METHOD__);
        try {
            $dateTime = (new \DateTime())->modify('-1 day');
            $midnight = $dateTime->setTime(0, 0, 0)->getTimestamp();
            $JustBeforeMidnight = $dateTime->setTime(23, 59, 59)->getTimestamp();

            $important_products = (new \JBAShop\Models\Products())->setCondition('policies.include_in_recap', '1')->getList();

            $new_products = (new \JBAShop\Models\Products())->setCondition('first_publication_time', array(
                '$gt' => $midnight,
                '$lt' => $JustBeforeMidnight
            ))->getList();

            $settings = \JBAShop\Models\Settings::fetch();

            $cursor = (new \JBAShop\Models\YearMakeModels())->collection()->find([
                'metadata.created.time' => array(
                    '$gt' => $midnight,
                    '$lt' => $JustBeforeMidnight
                )
            ]);

            $ymms = [];
            foreach ($cursor as $doc) {
                $ymms[] = $doc;
            }

            $cursor = (new \JBAShop\Models\Manufacturers())->collection()->find([
                'metadata.created.time' => array(
                    '$gt' => $midnight,
                    '$lt' => $JustBeforeMidnight
                )
            ]);
            $brands = [];
            foreach ($cursor as $doc) {
                $brands[] = $doc;
            }

            $cursor = (new \JBAShop\Models\UserContent())->collection()->find([
                'metadata.created.time' => array(
                    '$gt' => $midnight,
                    '$lt' => $JustBeforeMidnight
                ),
                'publication.status' => 'published'
            ]);
            $user_videos = [];
            $user_images = [];
            $user_reviews = [];
            $user_questions = [];
            foreach ($cursor as $doc) {

                if (! empty($doc['videoid'])) {
                    $user_videos[] = $doc;
                }
                if (! empty($doc['images'])) {
                    $user_images[] = $doc;
                }
                if (! empty($doc['type']) && $doc['type'] == 'review') {
                    $user_reviews[] = $doc;
                }

                if (! empty($doc['type']) && $doc['type'] == 'question') {
                    $user_questions[] = $doc;
                }
            }

            $now = $dateTime->format('l F jS Y');
            /*
             * Merging these together for one group now
             */
            $products = [];
            foreach ($important_products as $important_product) {
                $products[$important_product->get('tracking.model_number')] = $important_product;
            }
            foreach ($new_products as $important_product) {
                $products[$important_product->get('tracking.model_number')] = $important_product;
            }
            if ($preview) {
                return [
                    'settings' => $settings,
                    'products' => $products,
                    'important_products' => $important_products,
                    'new_products' => $new_products,
                    'ymms' => $ymms,
                    'brands' => $brands,
                    'user_videos' => $user_videos,
                    'user_images' => $user_images,
                    'user_reviews' => $user_reviews,
                    'user_questions' => $user_questions,
                    'date' => $now
                ];
            }

            $mailer = \Dsc\System::instance()->get('mailer');
            if (empty($important_products) && empty(empty($new_products)) && empty($most_recent_products) && empty($ymms)) {

                if ($content = $mailer->getEmailContents('rallyshop.rally_recap', array(
                    'settings' => $settings,
                    'important_products' => $important_products,
                    'products' => $products,
                    'new_products' => $new_products,
                    'ymms' => $ymms,
                    'brands' => $brands,
                    'user_videos' => $user_videos,
                    'user_images' => $user_images,
                    'user_reviews' => $user_reviews,
                    'user_questions' => $user_questions,
                    'date' => $now
                ))) {

                    $mailer->sendEvent('rsd-all@rallysportdirect.com', $content);
                }
            } else {
                // we have data
                if ($content = $mailer->getEmailContents('rallyshop.rally_recap', array(
                    'settings' => $settings,
                    'products' => $products,
                    'important_products' => $important_products,
                    'new_products' => $new_products,
                    'most_recent_products' => $most_recent_products,
                    'ymms' => $ymms,
                    'brands' => $brands,
                    'user_videos' => $user_videos,
                    'user_images' => $user_images,
                    'user_reviews' => $user_reviews,
                    'user_questions' => $user_questions,
                    'date' => $now
                ))) {

                    $mailer->sendEvent('rsd-all@rallysportdirect.com', $content);
                }
            }
        } catch (Exception $e) {
            $this->sendNewRelicError('Error Send Rally Recap Email', $e);
        }
        $this->endNewRelic();
    }
}
