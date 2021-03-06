<?php

namespace JBAShop\Services;

use PDO;
use \DB\SQL;
use Dsc\ArrayHelper;
use Dsc\Mongo\Collections\Logs;
use Exception;
use \League\CLImate\CLImate;

class Magento
{
    /** @var SQL */
    public $db;

    /** @var CLImate */
    public $CLImate;


    public function __construct()
    {
        //Magento Connection
        $this->db = self::getDB();
        //CLImate local object
        $this->CLImate = new CLImate();
    }

    public static function setCloudinaryCNAME(string $cname)
    {
        $app = \Base::instance();

        $app->set('cloudinary.cname', $cname);
        $app->set('cloudinary.secure_distribution', $cname);

        \Cloudinary::config(array(
            "cloud_name" => $app->get('cloudinary.cloud_name'),
            "api_key"    => $app->get('cloudinary.api_key'),
            "api_secret" => $app->get('cloudinary.api_secret'),
            "private_cdn" => filter_var($app->get('cloudinary.private_cdn'), FILTER_VALIDATE_BOOLEAN),
            "cname" => $app->get('cloudinary.cname'),
            "secure_distribution" => $app->get('cloudinary.secure_distribution'),
            'cdn_subdomain' => filter_var($app->get('cloudinary.cdn_subdomain'), FILTER_VALIDATE_BOOLEAN),
            'secure' => true
        ));
    }

    public function sortImages($list){
        ['images' => $sorted] = $list;

        ksort($sorted);
        array_unshift($sorted, 'fake');
        array_values($sorted);
        unset($sorted[0]);

        $list['sorted_images'] = $sorted;

        return $list;
    }

    public static function getDB()
    {
        /** @var SQL $db */
        $db = \Base::instance()->get('magento.connection');

        if (!$db) {
            $db = new SQL('mysql:host=' . \Base::instance()->get('magento.host') . ';dbname=' . \Base::instance()->get('magento.db'), \Base::instance()->get('magento.username'), \Base::instance()->get('magento.password'), array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
                \PDO::ATTR_EMULATE_PREPARES  => false
            ));

            \Base::instance()->set('magento.connection', $db);
        }

        return $db;
    }

    public function syncBrands()
    {
        $sql = "
            SELECT
                aov.option_id AS id,
                aov.`value` AS name
            FROM
                eav_attribute_option_value aov
                INNER JOIN eav_attribute_option ao ON aov.option_id = ao.option_id
                INNER JOIN eav_attribute a ON a.attribute_id = ao.attribute_id 
            WHERE
                a.attribute_id = 81 
            GROUP BY
                aov.option_id,
                aov.`value` 
            ORDER BY aov.`value` ASC
        ";

        $select = $this->db->prepare($sql);
        $select->execute();

        while ($row = $select->fetch()) {
            /**$existing = (new \Shop\Models\Manufacturers)
                ->setCondition('title', $row['name'])
                ->getItem();

            if (!empty($existing->slug)) {
                $existing->set('magento.id', $row['id']);
                $existing->store();
            } else {**/
            try {
                $brand = new \Shop\Models\Manufacturers([
                    'title' => $row['name'],
                    'magento' => [
                        'id' => $row['id']
                    ]
                ]);

                $brand->save();
            } catch (\Exception $e) {
                $this->CLImate->red($e->getMessage());
            }

            // }
        }
    }

    // TODO: redirects, photos, etc
    public function syncMagentoUsersToMongo($minutes = 0)
    {
        $and = ($minutes > 0) ?  "AND updated_at BETWEEN NOW() - INTERVAL {$minutes} MINUTE AND NOW()" : '';
        //Note: The use of "udpated_at" as "created_at" is to solve a magento bug of created_dates being wrong
        $sql =
            "SELECT
                ce.entity_id,
                cevf.VALUE as firstname,
                cevl.VALUE as lastname, 
                cevp.VALUE as password_hash,
                ce.updated_at as user_created_at,
                ce.email
            FROM
                customer_entity ce
                INNER JOIN customer_entity_varchar cevf ON ce.entity_id = cevf.entity_id
                INNER JOIN eav_attribute eaf ON eaf.attribute_id = cevf.attribute_id
                INNER JOIN customer_entity_varchar cevl ON ce.entity_id = cevl.entity_id
                INNER JOIN eav_attribute eal ON eal.attribute_id = cevl.attribute_id
                INNER JOIN customer_entity_varchar cevp ON ce.entity_id = cevp.entity_id
                INNER JOIN eav_attribute eaph ON eaph.attribute_id = cevp.attribute_id
                INNER JOIN eav_entity_type eet ON eet.entity_type_id = eal.entity_type_id = eaf.entity_type_id 
            WHERE
                eet.entity_type_code = 'customer' 
                AND eaf.attribute_code = 'firstname' 
                AND eal.attribute_code = 'lastname' 
                AND eaph.attribute_code = 'password_hash'
            {$and}    
            ORDER BY user_created_at";
            

        //Get all the users from the magento database
        $select = $this->db->prepare($sql);
        $select->execute();

        while ($user = $select->fetch(\PDO::FETCH_ASSOC)) {
            //Temp variable for our cli table
            $data = [];

            try {
                //The user data structure for transforming a Magento user to a Mongo User
                $userData = [
                    'email' => strtolower($user['email']),
                    'first_name' => $user['firstname'],
                    'password' => '',
                    'role' => 'identified',
                    'active' => true,
                    'last_name' => $user['lastname'],
                    'old' => [
                        'password' => $user['password_hash'],
                    ],
                    'price_level' => 'Retail-JBA',
                    'magento' => [
                        'user_id' => $user['entity_id'],
                    ],
                ];
                //Create the new user in mongo
                $newUser = (new \Users\Models\Users)->bind($userData);

                //See if we have an existing netsuite user for this email and division
                $netsuiteUser = \Netsuite\Models\Customer::getCustomerFromEmail($newUser->email);

                //If we found a valid netsuite user, update the netsuite object on the corresponding mongo user
                if ($netsuiteUser) {
                    array_push($data, ['Netsuite User Found!', $netsuiteUser['email'], "✅"]);

                    $newUser->set('netsuite', [
                        'externalId' => $netsuiteUser['externalId'],
                        'internalId' => $netsuiteUser['internalId'],
                        'entityId'  => $netsuiteUser['entityId']
                    ]);
                } else {
                    //No netsuite user was found for this Magento customer
                    array_push($data, ['No Netsuite User Found!', $netsuiteUser['email'], "❌"]);
                }

                $newUser->save();
                //New user was successfully transwered from Magento to Mongo
                array_push($data, ['New Mongo User Created From Magento!', $newUser->email, "✅"]);
            } catch (Exception $e) {
                $this->CLImate->red($e->getMessage());
                //This fixes CLImate exception of pushing empty array to table() 
                array_push($data, ['Email Already Exists... Skipping', $user['email'], "❌"]);
            }

            //Write our output for this iteration of the loop
            $this->CLImate->table($data);
            
        }
    }

    public function syncCategories()
    {
        $salesChannels = [
            'ftspeed' => [
                'id' => new \MongoDB\BSON\ObjectID('5e18ce8bf74061555646d847'),
                'title' => 'FTSpeed',
                'slug' => 'ftspeed'
            ],
            'subispeed' => [
                'id' => new \MongoDB\BSON\ObjectID('5841b1deb38c50ba028b4567'),
                'title' => 'SubiSpeed',
                'slug' => 'subispeed'
            ],
            'ft86speedfactory' => [
                'id' => new \MongoDB\BSON\ObjectID('5e18ce8bf74061555646d847'),
                'title' => 'FTSpeed',
                'slug' => 'ftspeed'
            ],
        ];
        // TODO: category enabled?

        $sql = "SELECT DISTINCT
                    cc.entity_id AS id,
                    cc.`value` AS `name`,
                    cc1.`value` AS url_path,
                    channel.channel,
                    position AS sort_order,
                    ccdesc.description AS description,
                CASE
                        cc.entity_id 
                        WHEN 5 THEN
                        ' 2015-2020 Subaru WRX' 
                        WHEN 6 THEN
                        ' 2015-2020 Subaru WRX STI' 
                        WHEN 3 THEN
                        ' 2013-2020 Subaru BRZ' 
                        WHEN 955 THEN
                        ' 2014-2018 Subaru Forester' 
                        WHEN 1222 THEN
                        ' 2013-2017 Subaru Crosstrek' ELSE cc.`value` 
                    END page_title,
                    concat( 'View the latest ', cc.`value`, ' products at ', IF ( channel.channel = 'subispeed', 'SubiSpeed', 'FTspeed' ), '.com. Free Shipping on orders over $149.99 (48 States)' ) AS 'meta_description',
                    COALESCE ( ccdesc.meta_keywords, cc.`value` ) AS 'meta_keywords',
                IF
                    (
                        channel.channel = 'ft86speedfactory' 
                        AND cce.parent_id = 652,
                        1682,
                    IF
                        ( cce.parent_id IN ( 1, 2, 652, 333, 515, 757, 1060, 1425 ), NULL, cce.parent_id ) 
                    ) AS parent_id 
                FROM
                    catalog_category_entity_varchar cc
                    JOIN catalog_category_entity_varchar cc1 ON cc.entity_id = cc1.entity_id
                    JOIN catalog_category_entity_int cc_int ON cc1.entity_id = cc_int.entity_id
                    JOIN eav_entity_type ee ON cc.entity_type_id = ee.entity_type_id
                    JOIN catalog_category_entity cce ON cc.entity_id = cce.entity_id
                    JOIN ( SELECT entity_id, description, meta_keywords FROM catalog_category_flat_store_1 UNION SELECT entity_id, description, meta_keywords FROM catalog_category_flat_store_4 UNION SELECT entity_id, description, meta_keywords FROM catalog_category_flat_store_5 ) AS ccdesc ON ccdesc.entity_id = cc.entity_id
                    JOIN (
                    SELECT
                        entity_id,
                        'subispeed' AS channel 
                    FROM
                        catalog_category_flat_store_1 AS subispeed UNION
                    SELECT
                        entity_id,
                        'ft86speedfactory' AS channel 
                    FROM
                        catalog_category_flat_store_4 AS ft86speedfactory UNION
                    SELECT
                        entity_id,
                        'ftspeed' AS channel 
                    FROM
                        catalog_category_flat_store_5 AS ftspeed 
                    ) AS channel ON channel.entity_id = cc.entity_id 
                WHERE
                    cc.attribute_id IN ( SELECT attribute_id FROM eav_attribute WHERE attribute_code = 'name' ) 
                    AND cc1.attribute_id IN ( SELECT attribute_id FROM eav_attribute WHERE attribute_code = 'url_path' ) 
                    AND cc_int.attribute_id IN ( SELECT attribute_id FROM eav_attribute WHERE attribute_code = 'is_active' ) 
                    AND cc_int.`value` = 1 
                    AND (( cce.parent_id = 2 AND cce.children_count > 1 ) OR cce.parent_id > 2 ) 
                    AND ee.entity_model = 'catalog/category' 
                    AND cc1.`value` NOT LIKE '%shop-by-manufacturer%' 
                    AND cc.`value` != 'SUPRA' -- AND cc.`value` != 'FR-S / BRZ / 86'
                GROUP BY
                    id 
                ORDER BY
                    cce.parent_id ASC,
                    cce.position ASC
        ";

        $exclude = [];
        $categoryIds = [null => null];

        do {
            $incomplete = false;
            $select = $this->db->prepare($sql);
            $select->execute();
            //CLI Progress bar
            $progress = $this->CLImate->progress()->total($select->rowCount());

            while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
                if (in_array($row['id'], array_keys($categoryIds))) {
                    continue;
                }
                // TODO: images, etc
                // TODO: assign products to categories
                
                $category = (new \Shop\Models\Categories)->setCondition('magento.id', $row['id'])->getItem();

                $category
                    ->set('description', $row['description']);
                
                //Advance the progress bar, output the cat title
                $progress->advance(1, $category['title']);

                $category->save();
                
            }
        } while ($incomplete);
    }

    public function syncProductInfo()
    {
        $failures = [['Failures']];
        
        $salesChannels = [
            'ftspeed' => [
                'id' => '5e18ce8bf74061555646d847',
                'title' => 'FTSpeed',
                'slug' => 'ftspeed'
            ],
            'subispeed' => [
                'id' => '5841b1deb38c50ba028b4567',
                'title' => 'SubiSpeed',
                'slug' => 'subispeed'
            ]
        ];

        $categories = [];
        foreach (\Shop\Models\Categories::collection()->find(['magento.id' => ['$exists' => true]], ['projection' => ['slug' => 1, 'title' => 1, 'magento' => 1, 'ancestors' => 1]]) as $category) {
            if (empty($category['magento']['id'])) {
                continue;
            }

            $add = [
                'id'    => $category['_id'],
                'title' => $category['title'],
                'slug'  => $category['slug']
            ];

            $categories[$category['magento']['id']] = [
                'add'    => $add,
                'remove' => $category['ancestors']
            ];
        }

        $sql = "
        SELECT
            def.entity_id AS 'id',
            youtube.value as 'youtube video',
            meta_title.value as 'meta title',
            is_carb.value as 'is carb',
            url_key.value as 'default url key',
            url_path.value as 'default url path',
            coupon.value as 'qualifies for coupon',
            warranty.value as 'warranty',
            mB.option_id as 'brand_id,mfg_id',
            mB.value as 'brand/manufacturer',
            cpe.sku AS 'model',
            if(cpe.type_id <> 'simple',CONCAT(cpe.sku,'-parent'),cpe.sku ) as 'matrix_parent_model_haxor',
            cpe.type_id AS 'product_type',
            `status`.`value` AS 'enabled',
            ! ISNULL( subi.`value` ) AS 'subispeed',
            IF (! ISNULL( ft86.`value` ) OR ! ISNULL( ftspeed.`value` ), 1, 0 ) AS 'ftspeed',
            default_name.`value` AS 'default_title',
            subi_name.`value` AS 'subispeed_title',
            ft86_name.`value` AS 'ft86_title',
            ftspeed_name.`value` AS 'ftspeed_title',
            cats.categories,
            default_desc.`value` AS 'long_description',
            default_short_desc.`value` AS 'short_description',
            cpe.created_at,
            new_from.`value` as 'first_publication_date',
            new_to.`value` as 'new_flag_date'
        FROM
            catalog_product_entity_int def
            INNER JOIN catalog_product_entity_int AS `status` ON ( def.entity_id = `status`.entity_id AND `status`.store_id = 0 AND `status`.attribute_id = 96 AND `status`.`value` = 1 )
            LEFT JOIN catalog_product_entity cpe ON def.entity_id = cpe.entity_id
            LEFT JOIN catalog_product_entity_varchar AS default_name ON ( def.entity_id = default_name.entity_id AND default_name.store_id = 0 AND default_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_text AS default_desc ON ( def.entity_id = default_desc.entity_id AND default_desc.store_id = 0 AND default_desc.attribute_id = 72 )
            LEFT JOIN catalog_product_entity_text AS default_short_desc ON ( def.entity_id = default_short_desc.entity_id AND default_short_desc.store_id = 0 AND default_short_desc.attribute_id = 73 )
            LEFT JOIN catalog_product_entity_varchar subi_name ON ( def.entity_id = subi_name.entity_id AND subi_name.store_id = 1 AND subi_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_varchar ft86_name ON ( def.entity_id = ft86_name.entity_id AND ft86_name.store_id = 4 AND ft86_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_varchar ftspeed_name ON ( def.entity_id = ftspeed_name.entity_id AND ftspeed_name.store_id = 5 AND ftspeed_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_int AS subi ON ( def.entity_id = subi.entity_id AND subi.store_id = 1 AND subi.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS ft86 ON ( def.entity_id = ft86.entity_id AND ft86.store_id = 4 AND ft86.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS ftspeed ON ( def.entity_id = ftspeed.entity_id AND ftspeed.store_id = 5 AND ftspeed.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS youtube ON ( def.entity_id = youtube.entity_id AND youtube.store_id = 0 AND youtube.attribute_id = 180)
            LEFT JOIN catalog_product_entity_varchar meta_title ON ( def.entity_id = meta_title.entity_id AND meta_title.store_id = 0 AND meta_title.attribute_id = 82)
            LEFT JOIN catalog_product_entity_int AS is_carb ON ( def.entity_id = is_carb.entity_id AND is_carb.store_id = 0 AND is_carb.attribute_id = 268)
            LEFT JOIN catalog_product_entity_varchar AS url_key ON ( def.entity_id = url_key.entity_id AND url_key.store_id = 0 AND url_key.attribute_id = 97)
            LEFT JOIN catalog_product_entity_varchar AS url_path ON ( def.entity_id = url_path.entity_id AND url_path.store_id = 0 AND url_path.attribute_id = 98)
            LEFT JOIN catalog_product_entity_int AS coupon ON ( def.entity_id = coupon.entity_id AND coupon.store_id = 0 AND coupon.attribute_id = 237)
            LEFT JOIN catalog_product_entity_text AS warranty ON ( def.entity_id = warranty.entity_id AND warranty.store_id = 0 AND warranty.attribute_id = 236 )
            LEFT JOIN catalog_product_entity_datetime AS new_from ON (def.entity_id = new_from.entity_id AND new_from.store_id = 0 and new_from.attribute_id = 93)
            LEFT JOIN catalog_product_entity_datetime AS new_to ON (def.entity_id = new_to.entity_id AND new_to.store_id = 0 and new_to.attribute_id = 94 )
            LEFT JOIN (
            SELECT
                cat.product_id,
                GROUP_CONCAT( cat.category_id ) AS categories,
                product.sku
            FROM
                catalog_category_product AS cat,
                catalog_product_entity AS product
            WHERE
                cat.product_id = product.entity_id
            GROUP BY
                cat.product_id
            ) AS cats ON cats.product_id = def.entity_id
            left join
            catalog_product_entity_int AS m ON m.attribute_id = 81
                AND m.entity_type_id = '4'
                AND m.STORE_ID = 0
                AND def.entity_id = m.entity_id
                LEFT JOIN
            eav_attribute_option_value mB ON mB.option_id = m.value
                AND mB.STORE_ID = 0
        WHERE
            def.attribute_id = 102
            AND def.store_id = 0
            ORDER BY cpe.sku ASC
        ";

        $select = $this->db->prepare($sql);
        $select->execute();
        $progress = $this->CLImate->progress()->total($select->rowCount());
        $ftBrzRegex = "/2013(\+|[\s]?-[\s]?[\d]{4})[\s]FR-?S[\s]\/[\s]BRZ([\s]\/[\s]86)?/";
        $subiBrzRegex = "/[\d]{4}(\+|[\s]?-[\s]?[\d]{4})[\s]?BRZ/";

        while ($row = $select->fetch()) {
            try{
                $netsuiteProduct = \Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($row['id']);
                //If we found a netsuite product, lookup product in mongo by the netsuite internal id
                if($netsuiteProduct){
                    $product = (new \Shop\Models\Products)
                    ->setCondition('netsuite.internalId', (string) $netsuiteProduct->internalId)
                    ->getItem();
                }

                //If both the above queries didnt find anything, try to lookup by the magento id
                if(!$netsuiteProduct){
                    $product = (new \Shop\Models\Products)
                    ->setCondition('magento.id', $row['id'])
                    ->getItem();
                }
            
                //If no NS product, and no product, create a new product
                if(!$netsuiteProduct && !$product){
                    $product = new \Shop\Models\Products();
                }
                if(empty($product)){
                    throw new \Exception('Product is null ' . $row['model']);
                }

                $progress->advance(0, $row['model']);//push model to progress without progressing the count.

                // TODO: query all categories once and get their full arrays for setting in products, key by magento id
                // TODO: discontinued?
                // TODO: brands

                if (!empty($row['brand_id'])) {
                    $brand = (new \Shop\Models\Manufacturers)
                        ->setCondition('magento.id', $row['brand_id'])
                        ->getItem();

                    if (!empty($brand->slug)) {
                        $product->set('manufacturer.id', $brand->id);
                    }
                }

                $productCategories = array_values(array_intersect_key($categories, array_flip(explode(',', $row['categories']))));
                $toAdd = \Dsc\ArrayHelper::getColumn($productCategories, 'add');
                $remove = \Dsc\ArrayHelper::getColumn($productCategories, 'remove');
                if (count($remove)) {
                    $toRemove = array_merge(...\Dsc\ArrayHelper::getColumn($productCategories, 'remove'));
                } else {
                    $toRemove = [];
                }

                $newProductCategories = array_values(array_filter($toAdd, function ($v) use ($toRemove) {
                    return !in_array($v['id'], \Dsc\ArrayHelper::getColumn($toRemove, 'id'));
                }));
                //If there is a ymmSuffix get substring to first instance of '-' else use default_title.
                preg_match("/.*(?=[\s]-(.*[\s]\/[\s].*|[\s]{1,}[12][\d]{3}|[\s]Universal))/", $row['default_title'], $titleMatches);

                $product
                    ->set('magento.id', $row['id'])
                    ->set('title', !empty($titleMatches) ? trim($titleMatches[0]) : $row['default_title'])
                    ->set('copy', $row['long_description'])
                    ->set('description', $row['short_description'])
                    ->set('categories', $newProductCategories)
                    ->set('metadata.created', \Dsc\Mongo\Metastamp::getDate($row['created_at']))
                    //seo stuff, we might need to split this out later by sales channel 
                    ->set('seo.page_title', $row['default_title'])
                    ->set('seo.keywords', $row['default_title'])
                    ->set('seo.meta_description', trim(str_replace(["\r", "\n"],'',strip_tags($row['long_description']))));

                //If we are in a situation where we are creating a new product (eg. matrix parent, set the model number)
                if(!$product->tracking['model_number']){
                    if($row['product_type'] !== 'simple' && end(explode('-', $row['model_number'])) !== 'bundle'){
                        $product->set('tracking.model_number', strtoupper($row['matrix_parent_model_haxor']));
                    }else{
                        $product->set('tracking.model_number', strtoupper($row['model']));
                    }
                }

                $productSalesChannels = [];
                $suffixTitles = [];

                if (!empty($row['subispeed'])) {
                    $productSalesChannels[] = $salesChannels['subispeed'];
                    //So we don't add things we don't need to only add suffix if in sales channel
                    $suffixTitles['subi'] = $this->stripYmmFromTitle($title, $row['subispeed_title']);//If this channel is active then put in suffixTitles array FIRST!
                }

                if (!empty($row['ftspeed'])) {
                    $productSalesChannels[] = $salesChannels['ftspeed'];
                    //So we don't add things we don't need to only add suffix if in sales channel
                    $suffixTitles['ft86'] = $this->stripYmmFromTitle($title, $row['ft86_title']);//If this channel is active then put in suffixTitles array SECOND!
                    $suffixTitles['ftspeed'] = $this->stripYmmFromTitle($title, $row['ftspeed_title']);//If this channel is active then put in suffixTitles array LAST!
                }

                if (!empty($suffixTitles)) {
                    if(count(array_unique(array_filter(array_values($suffixTitles)))) > 1){
                        //Ftspeed format
                        preg_match($ftBrzRegex, $suffixTitles['ft86'], $ft86BrzMatches);
                        preg_match($ftBrzRegex, $suffixTitles['ftspeed'], $ftBrzMatches);
                        //Subispeed format
                        preg_match($subiBrzRegex, $suffixTitles['subi'], $subiBrzMatches);
                        preg_match($subiBrzRegex, $suffixTitles['ft86'], $subiFt86BrzMatches);
                        preg_match($subiBrzRegex, $suffixTitles['ftspeed'], $subiFtspeedBrzMatches);
                        //if ft86 format matches subi replace subi and erase from ft86
                        if(!empty($ft86BrzMatches) && !empty($subiBrzMatches)){
                            $suffixTitles['subi'] = preg_replace($subiBrzRegex, $ft86BrzMatches[0], $suffixTitles['subi']);
                            $suffixTitles['ft86'] = preg_replace($ftBrzRegex, "", $suffixTitles['ft86']);
                        }

                        //if ft86 matches ftspeed
                        if(!empty($ft86BrzMatches) && !empty($ftBrzMatches)){
                            $suffixTitles['ftspeed'] = preg_replace($ftBrzRegex, $ft86BrzMatches[0], $suffixTitles['subi']);
                            $suffixTitles['ft86'] = preg_replace($ftBrzRegex, "", $suffixTitles['ft86']);
                        }
                        //if either of them match subi speed regex then lets remove
                        if(!empty($subiFt86BrzMatches) || !empty($subiFtspeedBrzMatches)){
                            //remove from both if it exists in subi
                            if(!empty($subiBrzMatches)){
                                $suffixTitles['ftspeed'] = preg_replace($subiBrzRegex, "", $suffixTitles['ftspeed']);
                            }

                            $suffixTitles['ft86'] = preg_replace($subiBrzRegex, "", $suffixTitles['ft86']);
                        }
                    }
                    //glue all sites together by ' / '
                    $suffixString = implode(' / ', array_unique(array_filter($suffixTitles)));//Get valid unique suffixes and convert to csv.
                    $product->set('title_suffix', !empty($suffixString) ? preg_replace("/\/[\s]{1,}\//", "/", rtrim(ltrim($suffixString, ' /') , ' /')): NULL); //if there is a suffix string then set it else leave as null.
                }

                if (!empty($row['enabled'])) {
                    $product->set('publication.status', 'published');
                }

                //If the product doesnt exist in NS, and is either a regular item or a grouped item, unpublish it
                //$row['product_type'] configurable = matrix item parent
                //$row['product_type'] simple = standard item
                //$row['product_type'] bundle = dynamic group
                //$row['product_type'] grouped = kit items
                if( (!$netsuiteProduct && $row['product_type'] === 'simple') || (!$netsuiteProduct && $row['product_type'] === 'grouped')){
                    $product->set('publication.status', 'unpublished');
                }

                if(empty($productSalesChannels)){
                    $product->set('publication.status', 'unpublished');
                }                

                if($netsuiteProduct['itemType'] === 'kit'){
                    $product->set('product_type', 'group');
                }else if(empty($product->get('product_type'))){
                    $product->set('product_type', 'standard');
                }

                if(!empty($row['first_publication_date'])){
                    $product->set('first_publication_time', \Carbon\Carbon::parse($row['first_publication_date'])->timestamp);
                }else if($row['enabled']){
                    $product->set('first_publication_time', \Carbon\Carbon::parse($row['created_at'])->timestamp);
                }

                if(!empty($row['new_flag_date'])){
                    $product->set('new_flag_date', \Carbon\Carbon::parse($row['new_flag_date'])->format('Y-m-d'));
                }
                
                $product->set('sales_channel_ids', array_column($productSalesChannels, 'id'));

                $product->save();
            }catch(Exception $e){
                $this->CLImate->red($e->getMessage());
                if($e->getMessage() !== 'Not a group item'){
                    // throw $e;
                    $failures[] = [$row['model']];
                }
            }

            try {
                $redirect = (new \Redirect\Admin\Models\Routes)->bind([
                    'product_id' => $product->id,
                    'title' => "Magento redirect for product " . (string)$product->id,
                    'old_slug'   => $row['default url path'],
                    'url' => [
                        'redirect' => $product->url(),
                        'alias' => $row['default url path'],
                    ],
                ]);
                 
                $redirect->save();
            } catch (\Exception $e) {
                $this->CLImate->red($e->getMessage());
            }

            $progress->advance(1, $row['model']);
        }
        $this->CLImate->table($failures);
    }

    public function syncProductImages($giveMeAName = null, $uploadProfile = null)
    {
        
        $where = $giveMeAName ? "WHERE cpg.entity_id = {$giveMeAName}" : '';

        $sql = 
            "SELECT
                cpg.entity_id AS product_id,
                CONCAT( 'https://www.subispeed.com/media/catalog/product', cpg.`VALUE` ) AS url,
                IFNULL( cpgv1.position, cpgv.position ) AS `order`,
                IFNULL( cpgv1.disabled, cpgv.disabled ) AS `disabled`,
                IFNULL( IF ( base.`VALUE` = cpg.`VALUE`, 1, NULL ), IF ( base_def.`VALUE` = cpg.`VALUE`, 1, 0 ) ) AS featured_image,
                IF( google.`VALUE` = cpg.`VALUE`, 1, 0 ) AS google_image,
                image_label.`VALUE` AS caption
            FROM
                catalog_product_entity_media_gallery cpg
                LEFT JOIN catalog_product_entity_media_gallery_value cpgv1 ON ( cpgv1.value_id = cpg.value_id AND cpgv1.store_id = 1 )
                LEFT JOIN catalog_product_entity_media_gallery_value cpgv ON ( cpgv.value_id = cpg.value_id AND cpgv.store_id = 0 )
                LEFT JOIN catalog_product_entity_varchar base ON ( base.entity_id = cpg.entity_id AND base.attribute_id = 86 AND base.store_id = 1 )
                LEFT JOIN catalog_product_entity_varchar base_def ON (
                    base_def.entity_id = cpg.entity_id 
                    AND base_def.attribute_id = 86 
                    AND base_def.store_id = 0 
                    AND base_def.entity_id NOT IN (
                        SELECT entity_id
                        FROM catalog_product_entity_varchar
                        WHERE attribute_id = 86
                        AND store_id = 1
                    )
                )
                LEFT JOIN catalog_product_entity_varchar google ON ( google.entity_id = cpg.entity_id AND google.attribute_id = 220 AND google.store_id = 0 )
                LEFT JOIN catalog_product_entity_varchar image_label ON ( image_label.entity_id = cpg.entity_id AND image_label.attribute_id = 112 AND image_label.store_id = 1 ) 
            {$where}
            ORDER BY
                product_id ASC,
                featured_image DESC,
                `order` ASC,
                google_image DESC";

        $select = $this->db->prepare($sql);
        $select->execute();

        $finalList = [];

        while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
            if (
                $row['disabled']
                && !$row['featured_image']
                && !$row['google_image']
            ) {
                continue;
            }

            if ($row['google_image']) {
                $finalList[$row['product_id']]['google'] = $row['url'];
            }
            
            //All image groups are now numerically indexed by thier ordering
            if (!$row['disabled'] || $row['featured_image']) {
                $finalList[$row['product_id']]['images'][$row['order']] = $row['url'];
            }

            //Missed Captions
            if($row['caption']){
                $finalList[$row['product_id']]['caption'] = $row['caption'];
            }
        }
        //Sort the images
        $finalList = array_map(['self', 'sortImages'], $finalList);

        //Log It
        $this->CLImate->green('All the data has been prepared, we will start sending to cloudinary now');
        
        foreach ($finalList as $magentoId => $links) {
            try{
                $product = (new \Shop\Models\Products)->collection()->findOne([
                    'magento.id' => $magentoId,
                ], [
                    'projection' => [
                        '_id' => true,
                        'tracking.model_number_flat' => true,
                        'magento.id' => true,
                    ],
                ]);
            
                
                if (empty($product)) {
                    $this->CLImate->red('No Product Found ' . $magentoId. ' Skipping..');
                    continue;
                }

                $this->CLImate->green('Product Found ' . $magentoId . ' Trying Cloudinary');

                //Grab some the properties from the model we need
                ['tracking' => ['model_number_flat' => $model], 'magento' =>['id' => $magento]] = $product;
                                
                if (!empty($links['google'])) {
                    $userOptions = ['tags' => "{$model}", 'context' => ['magento_id' => $magento]];
                    $options = array_merge($uploadProfile['google'], $userOptions);
                    
                    //Upload the google image
                    $upload = \Cloudinary\Uploader::upload(trim($links['google']), $options);

                    
                    $this->CLImate->green($magentoId . ' Is On Its Way To Google Images Cloudinary');
                }


                $this->CLImate->green('About to upload ' . count($links['images']) . ' Product Images');
                
                foreach($links['sorted_images'] as $key => $productImage){
                    $meta = array_filter(['caption'=>$links['caption'], 'order' => $key], 'strlen');

                    $options = array_merge($uploadProfile['products'], ['tags' => $model, 'context' => $meta]);
                
                    $upload = \Cloudinary\Uploader::upload($productImage, $options);
                }

                $this->CLImate->blue('Product Image: ' . $model . ' ..');

            
            }catch(\Exception $e){
                \Dsc\Mongo\Collections\Logs::add([
                    'message' => json_encode(['exception' => $e->getMessage(), 'product' => $model]),
                    'locator' => $magentoId,
                ], 'error', 'imagesync')->store();
                           
            }
        }
    }

    //This function takes all the cloudinary images from "google_images" and syncs them to mongo
    public function syncCloudinaryToMongo($type, $folder){
        $apiData = [];


        $cloudinaryOptions = [
            'type' => 'upload', 
            'prefix' => 'google_images/', 
            'tags' => true,
            'context' => true,
            'max_results' => 500,
        ];
        
        do {
            $cloudinaryApi = (new \Cloudinary\Api)->resources($cloudinaryOptions);
            $apiData = array_merge($cloudinaryApi['resources'], $apiData);
        

            if($cloudinaryApi['next_cursor']){
                $cloudinaryOptions['next_cursor'] = $cloudinaryApi['next_cursor'];
            }
        } while($cloudinaryApi['next_cursor']);

        $this->CLImate->green('TOTAL IMAGES TO SYNC: ' . count($apiData));
        
        foreach($apiData as $googleImage){
            $product = (new \Shop\Models\Products)->setCondition('magento.id', (int) $googleImage['context']['custom']['magento_id'])->getItem();
        
            if(!$product->id){
                $this->CLImate->red('NO image found for ' . $googleImage['context']['custom']['magento_id']);
                continue;
            }

            try{
                $product->set('google_image', $googleImage['public_id']);
                $product->store();
                $this->CLImate->green('image synced! ' . $product->get('tracking.model_number'));
            }catch(Exception $e){
                \Dsc\Mongo\Collections\Logs::add([
                    'message' => json_encode(['exception' => $e->getMessage(), 'image' => $googleImage['public_id']]),
                    'locator' => $product->id,
                ], 'error', 'cloudinarysync')->store();
                
                $this->CLImate->red('Failure, check logs for message.locatator=' . $product->id);
            }
        }

        $this->CLImate->green('All Doneski!');
    }

    public function syncCategoryImages()
    {
        $sql = 
            "SELECT
                entity_id AS category_id,
                CONCAT( 'https://www.subispeed.com/media/catalog/category/', thumbnail ) AS thumbnail 
            FROM catalog_category_flat_store_1
            WHERE thumbnail IS NOT NULL
            
            UNION
            
            SELECT
                entity_id AS category_id,
                CONCAT( 'https://www.subispeed.com/media/catalog/category/', thumbnail ) AS thumbnail 
            FROM catalog_category_flat_store_4
            WHERE thumbnail IS NOT NULL
            
            UNION
            
            SELECT
                entity_id AS category_id,
                CONCAT( 'https://www.subispeed.com/media/catalog/category/', thumbnail ) AS thumbnail 
            FROM catalog_category_flat_store_5
            WHERE thumbnail IS NOT NULL";

        $select = $this->db->prepare($sql);
        $select->execute();

        while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
            $category = (new \Shop\Models\Categories)
                ->setCondition('magento.id', (int) $row['category_id'])
                ->getItem();

            if (empty($category)) {
                continue;
            }

            $upload = \Cloudinary\Uploader::upload($row['thumbnail'], [
                'type' => 'private',
                'format' => 'jpg',
                'folder' => 'categories',
                'context' => [
                    'magento_id' => (int) $row['category_id']
                ]
            ]);

            $category->set('category_image.slug', $upload['public_id']);
            $category->store();
        }
    }

    public function syncProductRatings()
    {
        //Store the sales channels for assignment when we write the review to mongo
        $salesChannels = [
            'ftspeed' => [
                'id' => new \MongoDB\BSON\ObjectID('5e18ce8bf74061555646d847'),
                'title' => 'FTSpeed',
                'slug' => 'ftspeed'
            ],
            'subispeed' => [
                'id' => new \MongoDB\BSON\ObjectID('5841b1deb38c50ba028b4567'),
                'title' => 'SubiSpeed',
                'slug' => 'subispeed'
            ]
        ];

        //Get all the produt ratings from Magento
        $sql = "
        SELECT
            rd.review_id,
            r.created_at,
            rd.title,
            rv_overall.entity_pk_value,
            rd.detail,
            rd.customer_id,
            rd.nickname,
            rv_overall.`value` AS 'overall_satisfaction',
            rv_ease.`value` AS 'ease_of_installation',
            rv_fit.`value` AS 'fit_and_quality',
            r.status_id,
            IF(rd.store_id = 1, 'subispeed', 'ftspeed') AS channel,
            default_name.`value` AS 'product_title',
            url_key.`value` AS 'url_key',
            url_path.`value` AS 'url_path'
        FROM review_detail rd
        LEFT JOIN rating_option_vote rv_overall
        ON rd.review_id = rv_overall.review_id
        AND rv_overall.rating_id = 3
        JOIN review r
        ON r.review_id = rv_overall.review_id
        AND r.status_id = 1
        LEFT JOIN rating_option_vote rv_ease
        ON rd.review_id = rv_ease.review_id
        AND rv_ease.rating_id = 4
        LEFT JOIN rating_option_vote rv_fit
        ON rd.review_id = rv_fit.review_id
        AND rv_fit.rating_id = 5
        LEFT JOIN catalog_product_entity_varchar AS url_key ON ( rv_overall.entity_pk_value = url_key.entity_id AND url_key.store_id = 0 AND url_key.attribute_id = 97 )
        LEFT JOIN catalog_product_entity_varchar AS url_path ON ( rv_overall.entity_pk_value = url_path.entity_id AND url_path.store_id = 0 AND url_path.attribute_id = 98 )
        LEFT JOIN catalog_product_entity_varchar AS default_name ON ( rv_overall.entity_pk_value = default_name.entity_id AND default_name.store_id = 0 AND default_name.attribute_id = 71 )
        WHERE created_at > '2020-06-08 22:30:41'
        ";

        $select = $this->db->prepare($sql);
        $select->execute();

        while ($rating = $select->fetch(\PDO::FETCH_ASSOC)) {
            //Variable to hold cli->table() output for each record
            $data = [];

            //Find the product the rating is for
            $mongoProduct = (new \Shop\Models\Products)->collection()->findOne([
                'magento.id' => $rating['entity_pk_value']
            ], [
                'projection' => [
                    '_id' => true,
                    'title' => true,
                    'slug' => true,
                    'tracking' => true,
                ],
            ]);

            //If we found a product in mongo, check to see if the user the rating is from exists
            if(isset($mongoProduct['_id'])){
                //For CLI Output
                $data[] = ['Product Was Found!', $mongoProduct['title'], "✅"];

                //Check to see if the user who enetered the review exists:
                $user = (new \Users\Models\Users)->collection()->findOne([
                    'magento.user_id' => (int) $rating['customer_id']
                ], [
                    'projection' => [
                        '_id' => true,
                        'username' => true,
                    ],
                ]);

                //If a product AND a user are found, create the product rating
                if(isset($user['_id'])){
                    //For CLI Output
                    $data[] = ['User Was Found!', $user['username'], "✅"];

                    //Check to see if the rating already exists, so we can just update it if does
                    $userContent = (new \Shop\Models\UserContent)->setCondition('magento.id', $rating['review_id'])->getItem();
                    //Rating does not exist yet, create a new UserContent instance
                    if(empty($userContent)){
                        $userContent = new \Shop\Models\UserContent();
                    }

                    //Set all the required properties for the rating. Useres both $mongoProduct, $user and $rating data
                    $userContent
                        ->set('product_id', $mongoProduct['_id'])
                        ->set('user_id', $user['_id'])
                        ->set('type', 'review')
                        ->set('user_name', $rating['nickname'])
                        ->set('publication.status', 'published')
                        ->set('magento.id', $rating['review_id'])
                        ->set('part_number', $mongoProduct['tracking']['model_number'])
                        ->set('copy', $rating['detail'])
                        ->set('title', $rating['title'])
                        ->set('product_title', $mongoProduct['title'])
                        ->set('metadata.created', \Dsc\Mongo\Metastamp::getDate($rating['created_at']))
                        ->set('product_slug', $mongoProduct['slug'])
                        ->set('username', $user['username'])
                        ->set('role', 'user')
                        ->set('rating_criteria', [
                            'overall_satisfaction' => $rating['overall_satisfaction'], 
                            'ease_of_installation' => $rating['ease_of_installation'],
                            'fit_and_quality' => $rating['fit_and_quality'],
                        ]);

                        //Assign the sales channel to the rating
                        $ratingSalesChannels = [];
                        if ($rating['channel'] === 'subispeed') {
                            $ratingSalesChannels[] = $salesChannels['subispeed'];
                        }
                        if ($rating['channel'] === 'ftspeed') {
                            $ratingSalesChannels[] = $salesChannels['ftspeed'];
                        }
                        if(!empty($ratingSalesChannels)){
                            $userContent->set('publication.sales_channels', $ratingSalesChannels);
                        }

                    try{
                        //Save the new review
                        $userContent->save();
                        $data[] = ['New Review Created For Product', $rating['entity_pk_value'], "✅"];
                    }catch(Exception $e){
                        $this->CLImate->red($e->getMessage());
                    }
                    
                }else{
                    $data[] = ['No User Found ', $user['_id'], "❌"];
                }
            }else{
                $data[] = ['No Product With Magento_ID: ', $rating['entity_pk_value'], "❌"];
            }

            $this->CLImate->table($data);
        }
    }

    public function syncDynamicGroupProducts()
    {

        $sql = "
        SELECT
            *
        FROM
            (
            SELECT
                bundle.parent_id,
                bundle.option_id,
                bundle.required,
                cpd.value AS discount,
                bval.title as option_title,
                sel.product_id AS value_model_number,
                sel.position AS value_position,
                sel.selection_price_type,
                sel.selection_price_value AS value_price,
                sel.selection_qty AS value_required_quantity 
            FROM
                catalog_product_bundle_option AS bundle
            LEFT JOIN catalog_product_bundle_option_value AS bval ON
                bundle.option_id = bval.option_id
                AND bval.store_id = 0
            LEFT JOIN catalog_product_bundle_selection AS sel ON
                bundle.option_id = sel.option_id
            LEFT JOIN catalog_product_entity_decimal cpd ON
                bundle.parent_id = cpd.entity_id
                AND cpd.attribute_id = 76
            WHERE
                sel.product_id not in ('11053',
                '12717',
                '11052',
                '10916')
                and sel.product_id not in (
                select
                    entity_id
                from
                    catalog_product_entity_int
                where
                    attribute_id = 96
                    and value = 2
                    and store_id = 0)
        union
            select
            product_id as 'bundle.parent_id',
            999 as 'bundle.option_id',
            '0' as 'bundle.required',
            NULL as 'discount',
            'Options' as 'bval.title',
            linked_product_id as 'sel.product_id',
            g_position.value as 'sel.position',
            NULL as 'sel.price_type',
            NULL as 'se.selection_price_value',
            if(g_qty.value=0,1,g_qty.value) as 'sel.selection_qty'
        from
            catalog_product_link grouped
        left join catalog_product_link_attribute_decimal g_qty on
            g_qty.link_id = grouped.link_id
            and g_qty.product_link_attribute_id = 3
        left join catalog_product_link_attribute_int g_position on
            g_position.link_id = grouped.link_id
            and g_position.product_link_attribute_id = 2
        where
            link_type_id = 3
                and linked_product_id not in ('11053',
                '12717',
                '11052',
                '10916')
                and linked_product_id not in (
                select
                    entity_id
                from
                    catalog_product_entity_int
                where
                    attribute_id = 96
                    and value = 2
                    and store_id = 0) ) dgroups
        JOIN (
            SELECT
                def.entity_id AS 'id',
                cpe.created_at ,
                new_from.`value` as 'first_publication_date',
                new_to.`value` as 'new_flag_date',
                youtube.value AS 'youtube video' ,
                install.value AS 'install instructions' ,
                meta_title.value AS 'meta title',
                is_carb.value AS 'is carb',
                url_key.value AS 'default url key',
                url_path.value AS 'default url path',
                coupon.value AS 'qualifies for coupon',
                warranty.value AS 'warranty',
                mB.option_id AS 'brand_id,mfg_id',
                mB.value AS 'brand/manufacturer',
                cpe.sku AS 'model',
                `status`.`value` AS 'enabled',
                ! Isnull(subi.`value`) AS 'subispeed',
                IF ( ! Isnull(ft86.`value`)
                OR ! Isnull(ftspeed.`value`),
                1,
                0) AS 'ftspeed',
                default_name.`value` AS 'default_title',
                subi_name.`value` AS 'subispeed_title',
                ft86_name.`value` AS 'ft86_title',
                ftspeed_name.`value` AS 'ftspeed_title',
                cats.categories,
                default_desc.`value` AS 'long_description',
                default_short_desc.`value` AS 'short_description'
            FROM
                catalog_product_entity_int def
            INNER JOIN catalog_product_entity_int AS `status` ON
                ( def.entity_id = `status`.entity_id
                AND `status`.store_id = 0
                AND `status`.attribute_id = 96
                AND `status`.`value` = 1 )
            LEFT JOIN catalog_product_entity cpe ON
                def.entity_id = cpe.entity_id
            LEFT JOIN catalog_product_entity_varchar AS default_name ON
                ( def.entity_id = default_name.entity_id
                AND default_name.store_id = 0
                AND default_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_text AS default_desc ON
                ( def.entity_id = default_desc.entity_id
                AND default_desc.store_id = 0
                AND default_desc.attribute_id = 72 )
            LEFT JOIN catalog_product_entity_text AS default_short_desc ON
                ( def.entity_id = default_short_desc.entity_id
                AND default_short_desc.store_id = 0
                AND default_short_desc.attribute_id = 73 )
            LEFT JOIN catalog_product_entity_varchar subi_name ON
                ( def.entity_id = subi_name.entity_id
                AND subi_name.store_id = 1
                AND subi_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_varchar ft86_name ON
                ( def.entity_id = ft86_name.entity_id
                AND ft86_name.store_id = 4
                AND ft86_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_varchar ftspeed_name ON
                ( def.entity_id = ftspeed_name.entity_id
                AND ftspeed_name.store_id = 5
                AND ftspeed_name.attribute_id = 71 )
            LEFT JOIN catalog_product_entity_int AS subi ON
                ( def.entity_id = subi.entity_id
                AND subi.store_id = 1
                AND subi.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS ft86 ON
                ( def.entity_id = ft86.entity_id
                AND ft86.store_id = 4
                AND ft86.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS ftspeed ON
                ( def.entity_id = ftspeed.entity_id
                AND ftspeed.store_id = 5
                AND ftspeed.attribute_id = 102 )
            LEFT JOIN catalog_product_entity_int AS youtube ON
                ( def.entity_id = youtube.entity_id
                AND youtube.store_id = 0
                AND youtube.attribute_id = 180 )
            LEFT JOIN catalog_product_entity_text AS install ON
                ( def.entity_id = install.entity_id
                AND install.store_id = 0
                AND install.attribute_id = 144 )
            LEFT JOIN catalog_product_entity_varchar meta_title ON
                ( def.entity_id = meta_title.entity_id
                AND meta_title.store_id = 0
                AND meta_title.attribute_id = 82 )
            LEFT JOIN catalog_product_entity_int AS is_carb ON
                ( def.entity_id = is_carb.entity_id
                AND is_carb.store_id = 0
                AND is_carb.attribute_id = 268 )
            LEFT JOIN catalog_product_entity_varchar AS url_key ON
                ( def.entity_id = url_key.entity_id
                AND url_key.store_id = 0
                AND url_key.attribute_id = 97 )
            LEFT JOIN catalog_product_entity_varchar AS url_path ON
                ( def.entity_id = url_path.entity_id
                AND url_path.store_id = 0
                AND url_path.attribute_id = 98 )
            LEFT JOIN catalog_product_entity_int AS coupon ON
                ( def.entity_id = coupon.entity_id
                AND coupon.store_id = 0
                AND coupon.attribute_id = 237 )
            LEFT JOIN catalog_product_entity_text AS warranty ON
                ( def.entity_id = warranty.entity_id
                AND warranty.store_id = 0
                AND warranty.attribute_id = 236 )
            LEFT JOIN catalog_product_entity_datetime AS new_from ON
                (def.entity_id = new_from.entity_id
                AND new_from.store_id = 0
                and new_from.attribute_id = 93)
            LEFT JOIN catalog_product_entity_datetime AS new_to ON
                (def.entity_id = new_to.entity_id
                AND new_to.store_id = 0
                and new_to.attribute_id = 94 )
            LEFT JOIN (
                SELECT
                    cat.product_id,
                    Group_concat(cat.category_id) AS categories,
                    product.sku
                FROM
                    catalog_category_product AS cat,
                    catalog_product_entity AS product
                WHERE
                    cat.product_id = product.entity_id
                GROUP BY
                    cat.product_id) AS cats ON
                cats.product_id = def.entity_id
            LEFT JOIN catalog_product_entity_int AS m ON
                m.attribute_id = 81
                AND m.entity_type_id = '4'
                AND m.store_id = 0
                AND def.entity_id = m.entity_id
            LEFT JOIN eav_attribute_option_value mB ON
                mB.option_id = m.value
                AND mB.store_id = 0
            WHERE
                def.attribute_id = 102
                AND def.store_id = 0) data ON
            data.id = dgroups.parent_id
        WHERE
            parent_id not in (
            select
                parent_product_id
            from
                catalog_product_bundle_selection sel
            where
                sel.product_id not in ('11053',
                '12717',
                '11052',
                '10916')/* remove disabled children */
                and sel.product_id not in (
                select
                    entity_id
                from
                    catalog_product_entity_int
                where
                    attribute_id = 96
                    and value = 2
                    and store_id = 0)
            group by
                sel.parent_product_id
            having
                count(*) = 1)
        ";

        //This query returns 1 record for each dynamic group member. the PDO::FETCH_GROUP is a helper to group all magento for a given dynamic group together
        //For example, $productGroup will contain all recrods for a given dynamic group
        $select = $this->db->prepare($sql);
        $select->execute();

        while($rows = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)){
            $failures = [['FAILURES']];

            $progress = $this->CLImate->progress()->total(count($rows));

            foreach($rows as $magentoID => $productGroup){
                $options = [];

                //The dynamic group parent is now created in the "productInfo" sync. 
                $groupParent = (new \Shop\Models\Products)->setCondition('magento.id', $magentoID)->getItem();

                if(!$productGroup){
                    $failures[] = ['No Parent Found For ', $magentoID];
                    continue;
                }
                
                foreach($productGroup as $productOption){
                    

                    //Dynamic Kit Option Properties
                    $options[$productOption['option_id']]['id'] = new \MongoDB\BSON\ObjectID();
                    $options[$productOption['option_id']]['title'] = $productOption['option_title'];
                    $options[$productOption['option_id']]['allow_none'] = $productOption['required'] ? false : true;
                    $options[$productOption['option_id']]['quantity'] = (int) $productOption['value_required_quantity'];
                    $options[$productOption['option_id']]['discount_percentage'] = 0;

                    //Lookup the model number, changed to not fail the whole group if a child doesnt exist in NS.
                    $netSuiteItemId = (\Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($productOption['value_model_number']))->itemId;

                    //Only write the child option to the values array if it exists in NS
                    if($netSuiteItemId){
                        //Dynamic Kit option value properties
                        $options[$productOption['option_id']]['values'][] = [
                            'id' => new \MongoDB\BSON\ObjectID(),
                            'model_number' => $netSuiteItemId,
                            'magento_id' => $productOption['value_model_number'],
                            'option_id' => $productOption['option_id'],
                            'title' => $productOption['option_title']
                        ];
                    }
                }                
            
                //Build/Update the dyanmic kit and save it to mongo
                $groupParent
                    ->set('product_type', 'dynamic_group')
                    ->set('kit_options', $options);
                
                if($groupParent->get('publication.status') == 'unpublished'){
                    $groupParent->set('publication.status', 'published');
                }

                try{
                    $groupParent->save();
                }catch(Exception $e){
                    $this->CLImate->red($e->getMessage());
                }

                $progress->advance(1, $groupParent->get('tracking.model_number'));
            }
        }

        $this->CLImate->table($failures);
    }

    public function splitDynamicGroupOptions(){
        $sql = "
        SELECT
                *
            FROM
                (SELECT
                    bundle.parent_id,
                    bundle.option_id,
                    bundle.required,
                    cpd.value AS discount,
                    bval.title,
                    sel.product_id,
                    sel.position,
                    sel.selection_price_type,
                    sel.selection_price_value,
                    sel.selection_qty		FROM
                    catalog_product_bundle_option AS bundle
                LEFT JOIN catalog_product_bundle_option_value AS bval ON
                    bundle.option_id = bval.option_id
                    AND bval.store_id = 0
                LEFT JOIN catalog_product_bundle_selection AS sel ON
                    bundle.option_id = sel.option_id
                LEFT JOIN catalog_product_entity_decimal cpd ON
                    bundle.parent_id = cpd.entity_id
                    AND cpd.attribute_id = 76
                WHERE
                    bundle.required =0 and
                    sel.product_id not in ('11053',
                    '12717',
                    '11052',
                    '10916')
                    and sel.product_id not in (
                    select
                        entity_id
                    from
                        catalog_product_entity_int
                    where
                        attribute_id = 96
                        and value = 2
                        and store_id = 0)
                        and bundle.`type` in('multi','checkbox')
                        and bundle.parent_id in(SELECT
                    bundle.parent_id
                    FROM
                    catalog_product_bundle_option AS bundle
                LEFT JOIN catalog_product_bundle_option_value AS bval ON
                    bundle.option_id = bval.option_id
                    AND bval.store_id = 0
                LEFT JOIN catalog_product_bundle_selection AS sel ON
                    bundle.option_id = sel.option_id
                LEFT JOIN catalog_product_entity_decimal cpd ON
                    bundle.parent_id = cpd.entity_id
                    AND cpd.attribute_id = 76
                WHERE
                    sel.product_id not in ('11053',
                    '12717',
                    '11052',
                    '10916')
                    and sel.product_id not in (
                    select
                        entity_id
                    from
                        catalog_product_entity_int
                    where
                        attribute_id = 96
                        and value = 2
                        and store_id = 0) and bundle.`type` in ('multi','checkbox')
                        group by bundle.option_id 
                        having count(bundle.option_id )>1
                        )
            union
                select
                product_id as 'bundle.parent_id',
                999 as 'bundle.option_id',
                '0' as 'bundle.required',
                NULL as 'discount',
                'Options' as 'bval.title',
                linked_product_id as 'sel.product_id' ,
                g_position.value as 'sel.position',
                NULL as 'sel.price_type',
                NULL as 'se.selection_price_value',
                if(g_qty.value=0,1,g_qty.value) as 'sel.selection_qty'
            from
                catalog_product_link grouped
            left join catalog_product_link_attribute_decimal g_qty on
                g_qty.link_id = grouped.link_id
                and g_qty.product_link_attribute_id = 3
            left join catalog_product_link_attribute_int g_position on
                g_position.link_id = grouped.link_id
                and g_position.product_link_attribute_id = 2
            where
                link_type_id = 3
                    and linked_product_id not in ('11053',
                    '12717',
                    '11052',
                    '10916')
                    and linked_product_id not in (
                    select
                        entity_id
                    from
                        catalog_product_entity_int
                    where
                        attribute_id = 96
                        and value = 2
                        and store_id = 0) ) dgroups
            JOIN (
                SELECT
                    def.entity_id AS 'id',
                    cpe.created_at ,
                    new_from.`value` as 'first_publication_date',
                    new_to.`value` as 'new_flag_date',
                    youtube.value AS 'youtube video' ,
                    install.value AS 'install instructions' ,
                    meta_title.value AS 'meta title',
                    is_carb.value AS 'is carb',
                    url_key.value AS 'default url key',
                    url_path.value AS 'default url path',
                    coupon.value AS 'qualifies for coupon',
                    warranty.value AS 'warranty',
                    mB.option_id AS 'brand_id,mfg_id',
                    mB.value AS 'brand/manufacturer',
                    cpe.sku AS 'model',
                    `status`.`value` AS 'enabled',
                    ! Isnull(subi.`value`) AS 'subispeed',
                    IF ( ! Isnull(ft86.`value`)
                    OR ! Isnull(ftspeed.`value`),
                    1,
                    0) AS 'ftspeed',
                    default_name.`value` AS 'default_title',
                    subi_name.`value` AS 'subispeed_title',
                    ft86_name.`value` AS 'ft86_title',
                    ftspeed_name.`value` AS 'ftspeed_title',
                    cats.categories,
                    default_desc.`value` AS 'long_description',
                    default_short_desc.`value` AS 'short_description'
                FROM
                    catalog_product_entity_int def
                INNER JOIN catalog_product_entity_int AS `status` ON
                    ( def.entity_id = `status`.entity_id
                    AND `status`.store_id = 0
                    AND `status`.attribute_id = 96
                    AND `status`.`value` = 1 )
                LEFT JOIN catalog_product_entity cpe ON
                    def.entity_id = cpe.entity_id
                LEFT JOIN catalog_product_entity_varchar AS default_name ON
                    ( def.entity_id = default_name.entity_id
                    AND default_name.store_id = 0
                    AND default_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_text AS default_desc ON
                    ( def.entity_id = default_desc.entity_id
                    AND default_desc.store_id = 0
                    AND default_desc.attribute_id = 72 )
                LEFT JOIN catalog_product_entity_text AS default_short_desc ON
                    ( def.entity_id = default_short_desc.entity_id
                    AND default_short_desc.store_id = 0
                    AND default_short_desc.attribute_id = 73 )
                LEFT JOIN catalog_product_entity_varchar subi_name ON
                    ( def.entity_id = subi_name.entity_id
                    AND subi_name.store_id = 1
                    AND subi_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_varchar ft86_name ON
                    ( def.entity_id = ft86_name.entity_id
                    AND ft86_name.store_id = 4
                    AND ft86_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_varchar ftspeed_name ON
                    ( def.entity_id = ftspeed_name.entity_id
                    AND ftspeed_name.store_id = 5
                    AND ftspeed_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_int AS subi ON
                    ( def.entity_id = subi.entity_id
                    AND subi.store_id = 1
                    AND subi.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS ft86 ON
                    ( def.entity_id = ft86.entity_id
                    AND ft86.store_id = 4
                    AND ft86.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS ftspeed ON
                    ( def.entity_id = ftspeed.entity_id
                    AND ftspeed.store_id = 5
                    AND ftspeed.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS youtube ON
                    ( def.entity_id = youtube.entity_id
                    AND youtube.store_id = 0
                    AND youtube.attribute_id = 180 )
                LEFT JOIN catalog_product_entity_text AS install ON
                    ( def.entity_id = install.entity_id
                    AND install.store_id = 0
                    AND install.attribute_id = 144 )
                LEFT JOIN catalog_product_entity_varchar meta_title ON
                    ( def.entity_id = meta_title.entity_id
                    AND meta_title.store_id = 0
                    AND meta_title.attribute_id = 82 )
                LEFT JOIN catalog_product_entity_int AS is_carb ON
                    ( def.entity_id = is_carb.entity_id
                    AND is_carb.store_id = 0
                    AND is_carb.attribute_id = 268 )
                LEFT JOIN catalog_product_entity_varchar AS url_key ON
                    ( def.entity_id = url_key.entity_id
                    AND url_key.store_id = 0
                    AND url_key.attribute_id = 97 )
                LEFT JOIN catalog_product_entity_varchar AS url_path ON
                    ( def.entity_id = url_path.entity_id
                    AND url_path.store_id = 0
                    AND url_path.attribute_id = 98 )
                LEFT JOIN catalog_product_entity_int AS coupon ON
                    ( def.entity_id = coupon.entity_id
                    AND coupon.store_id = 0
                    AND coupon.attribute_id = 237 )
                LEFT JOIN catalog_product_entity_text AS warranty ON
                    ( def.entity_id = warranty.entity_id
                    AND warranty.store_id = 0
                    AND warranty.attribute_id = 236 )
                LEFT JOIN catalog_product_entity_datetime AS new_from ON
                    (def.entity_id = new_from.entity_id
                    AND new_from.store_id = 0
                    and new_from.attribute_id = 93)
                LEFT JOIN catalog_product_entity_datetime AS new_to ON
                    (def.entity_id = new_to.entity_id
                    AND new_to.store_id = 0
                    and new_to.attribute_id = 94 )
                LEFT JOIN (
                    SELECT
                        cat.product_id,
                        Group_concat(cat.category_id) AS categories,
                        product.sku
                    FROM
                        catalog_category_product AS cat,
                        catalog_product_entity AS product
                    WHERE
                        cat.product_id = product.entity_id
                    GROUP BY
                        cat.product_id) AS cats ON
                    cats.product_id = def.entity_id
                LEFT JOIN catalog_product_entity_int AS m ON
                    m.attribute_id = 81
                    AND m.entity_type_id = '4'
                    AND m.store_id = 0
                    AND def.entity_id = m.entity_id
                LEFT JOIN eav_attribute_option_value mB ON
                    mB.option_id = m.value
                    AND mB.store_id = 0
                WHERE
                    def.attribute_id = 102
                    AND def.store_id = 0) data ON
                data.id = dgroups.parent_id
            WHERE
                parent_id not in (
                select
                    parent_product_id
                from
                    catalog_product_bundle_selection sel
                where
                    sel.product_id not in ('11053',
                    '12717',
                    '11052',
                    '10916')/* remove disabled children */
                    and sel.product_id not in (
                    select
                        entity_id
                    from
                        catalog_product_entity_int
                    where
                        attribute_id = 96
                        and value = 2
                        and store_id = 0)
                group by
                    sel.parent_product_id
                having
                    count(*) = 1)";

        $select = $this->db->prepare($sql);
        $select->execute();

        while($rows = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)){
            $failures = [['FAILURES']];

            $progress = $this->CLImate->progress()->total(count($rows));

            foreach($rows as $magentoID => $productGroup){
                //The dynamic group parent is now created in the "productInfo" sync. 
                $groupParent = (new \Shop\Models\Products)->setCondition('magento.id', $magentoID)->getItem();
                
                //If we dont have a parent in mongo already, something is wrong
                if(!$groupParent){
                    $failures[] = ['No Parent Found For ', $magentoID];
                    continue;
                }

                //Temp vars for reach product group
                $options = [];
                $existingOptions = $groupParent['kit_options'];

                foreach($productGroup as $productOption){
                    //Lookup the model number, for each grou subitem
                    $childProductNetsuite = (\Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($productOption['product_id']));
                    
                    //If the child id doesnt exist in NS, skip it
                    if(!$childProductNetsuite){
                        continue;
                    }

                    //Build the dynamic kit option
                    $options[$productOption['product_id']]['id'] = new \MongoDB\BSON\ObjectID();
                    $options[$productOption['product_id']]['title'] = $productOption['title'];
                    $options[$productOption['product_id']]['allow_none'] = $productOption['required'] ? false : true;
                    $options[$productOption['product_id']]['quantity'] = (int) $productOption['selection_qty'];
                    $options[$productOption['product_id']]['discount_percentage'] = 0;
                        
                    //Build the dynamic kit option values
                    $options[$productOption['product_id']]['values'][] = [
                        'id' => new \MongoDB\BSON\ObjectID(),
                        'model_number' => $childProductNetsuite['itemId'],
                        'magento_id' => $productOption['product_id'],
                        'option_id' => $productOption['product_id'],
                        'title' => $productOption['title']
                    ];
                    //If were creating a new option/value, remove it from the current state
                    array_walk($existingOptions, function(&$option, &$optionKey) use($childProductNetsuite, &$existingOptions){
                        //Looping through options
                        array_walk($option['values'], function(&$value, &$valueKey) use(&$optionKey, $childProductNetsuite, &$existingOptions){
                            //The outer loops contain the current group sub item id, remove the "value" from existing product
                            if($value['model_number'] === $childProductNetsuite['itemId']){
                               unset($existingOptions[$optionKey]['values'][$valueKey]);
                            }
                        });
                    });

                    
                }
                //Merge the new options with the existing options, now that we have split the values.
                $options = array_merge($existingOptions, $options);    

                //Its possible we have removed all values from an option, get rid of those
                $options = array_filter(array_values($options), function($option){
                    return ( count($option['values']) > 0);
                });

                //Reset array indexes, and update the kit options
                $groupParent->set('kit_options', array_values($options));

                //Save the new options
                try{
                    $groupParent->save();
                }catch(Exception $e){
                    $this->CLImate->red($e->getMessage());
                }

                $progress->advance(1, $groupParent->get('tracking.model_number'));
            }
        }
    }

    public function syncYmmsFromRally()
    {
        // remove all ymms and product ymm data before starting
        \Shop\Models\YearMakeModels::collection()->deleteMany([]);
        \Shop\Models\Products::collection()->updateMany([
            'ymms.0' => [
                '$exists' => true
            ]
        ], [
            '$unset' => [
                'ymms' => ''
            ]
        ]);

        $rallyYmms = (new \JBAShop\Models\RallyYearMakeModels)
            ->setCondition('$or', [
                [ 'vehicle_make' => 'Subaru' ],
                [ 'vehicle_make' => 'Toyota', 'vehicle_model' => '86' ],
                [ 'vehicle_make' => 'Toyota', 'vehicle_model' => 'Supra', 'vehicle_year' => ['$in' => ['2020', '2021']] ],
                [ 'vehicle_make' => 'Scion', 'vehicle_model' => 'FR-S' ]
            ])
            ->getItems();

        $progress = $this->CLImate->blue(count($rallyYmms) . ' ymms to add');

        foreach ($rallyYmms as $rallyYmm) {
            $progress = $this->CLImate->yellow('Adding ' . $rallyYmm->makeTitle() . '...');

            $jbaYmm = (new \Shop\Models\YearMakeModels)
                ->bind($rallyYmm->cast())
                ->store();

            $rallyProducts = \JBAShop\Models\RallyProducts::collection()->distinct('tracking.model_number', [
                'ymms.slug' => $jbaYmm->slug
            ]);

            $update = \Shop\Models\Products::collection()->updateMany([
                'tracking.model_number' => [
                    '$in' => $rallyProducts
                ]
            ], [
                '$push' => [
                    'ymms' => [
                        'id'    => $jbaYmm->id,
                        'slug'  => $jbaYmm->slug,
                        'title' => $jbaYmm->title
                    ]
                ]
            ]);

            $progress = $this->CLImate->green('Updated ' . $update->getModifiedCount() . ' products!');
        }
    }

    //This is a helper function to return the outermost categories for a given product
    public function getOuterMostCategories($categoryIDs){

        $categories = [];
        foreach (\Shop\Models\Categories::collection()->find(['magento.id' => ['$exists' => true]], ['projection' => ['slug' => 1, 'title' => 1, 'magento' => 1, 'ancestors' => 1]]) as $category) {
            if (empty($category['magento']['id'])) {
                continue;
            }

            $add = [
                'id'    => $category['_id'],
                'title' => $category['title'],
                'slug'  => $category['slug']
            ];

            $categories[$category['magento']['id']] = [
                'add'    => $add,
                'remove' => $category['ancestors']
            ];
        }

        //Anywhere $row['categories'] exists, replace with $categoryIDs
        $productCategories = array_values(array_intersect_key($categories, array_flip($categoryIDs)));

        $toAdd = \Dsc\ArrayHelper::getColumn($productCategories, 'add');
        $remove = \Dsc\ArrayHelper::getColumn($productCategories, 'remove');

        if (count($remove)) {
            $toRemove = array_merge(...\Dsc\ArrayHelper::getColumn($productCategories, 'remove'));
        } else {
            $toRemove = [];
        }

        $newProductCategories = array_values(array_filter($toAdd, function ($v) use ($toRemove) {
            return !in_array($v['id'], \Dsc\ArrayHelper::getColumn($toRemove, 'id'));
        }));

        return $newProductCategories;
    }

    public function syncUserContentImages()
    {
        \Shop\Models\UserContent::collection()->deleteMany(['type' => 'image']);
        // manually delete images from cloudinary user_content folder


        $guestUser = (new \Users\Models\Users)
            ->setCondition('email', 'guest@jbautosports.com')
            ->getItem();

        if (empty($guestUser->email)) {
            $guestUser = \Users\Models\Users::createNewUser([
                'email' => 'guest@jbautosports.com',
                'first_name' => 'Guest',
                'password' => '',
                'role' => 'identified',
                'active' => true,
                'last_name' => 'User',
                'price_level' => 'Retail-JBA'
            ]);
        }

        $sql =
            "SELECT
                CONCAT('https://www.subispeed.com/media/catalog/product/customerimg', file) AS image_url,
                product_id,
                customer_id,
                IF(guest_email = 'chrisngrod@gmail.com' OR guest_email = 'chris@jbautosports.com', 'guest@jbautosports.com', guest_email) AS guest_email,
                title,
                `status`,
                IF(store_id = 1, 'subispeed', 'ftspeed') AS store,
                created_at
            FROM amasty_amcustomerimg_image
            WHERE store_id IN (1, 4, 5)
            AND `status` != 'declined'
            AND `file` NOT IN (
                SELECT `value`
                FROM catalog_product_entity_media_gallery)
            AND created_at > '2020-06-19 00:00:00'";

        $select = $this->db->prepare($sql);
        $select->execute();

        $missingProducts = [];
        while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
            
            $imageExists = getimagesize($row['image_url']);
            
            if (in_array($row['product_id'], $missingProducts) || !$imageExists) {
                continue;
            }

            $data = [];

            $mongoProduct = (new \Shop\Models\Products)->collection()->findOne([
                'magento.id' => $row['product_id']
            ], [
                'projection' => [
                    '_id' => true,
                    'title' => true,
                    'slug' => true,
                    'tracking' => true
                ],
            ]);

            if (isset($mongoProduct['_id'])) {
                $data[] = ['Product Found!', $mongoProduct['tracking']['model_number'], '✅'];

                $user = (new \Users\Models\Users)->collection()->findOne([
                    '$or' => [
                        [ 'magento.user_id' => (int) $row['customer_id'] ],
                        [ 'email' => $row['guest_email'] ]
                    ]
                ], [
                    'projection' => [
                        '_id' => true,
                        'username' => true,
                    ]
                ]);

                if (empty($user['_id'])) {
                    $user = $guestUser;
                }

                $upload = \Cloudinary\Uploader::upload($row['image_url'], [
                    'type' => 'upload',
                    'format' => 'jpg',
                    'folder' => Cloudinary::USER_CONTENT,
                    'tags' => 'review',
                    'context' => [
                        'model_number' => $mongoProduct['tracking']['model_number_flat']
                    ]
                ]); 


                $userContent = (new \Shop\Models\UserContent)
                    ->set('type', 'image')
                    ->set('user_id', $user['_id'])
                    ->set('user_name', $user['username'])
                    ->set('username', $user['username'])
                    ->set('role', 'user')
                    ->set('product_id', $mongoProduct['_id'])
                    ->set('part_number', $mongoProduct['tracking']['model_number'])
                    ->set('product_title', $mongoProduct['title'])
                    ->set('product_slug', $mongoProduct['slug'])
                    ->set('publication.status', $row['status'] == 'pending' ? 'review' : 'published')
                    ->set('images.0', $upload['public_id'])
                    ->set('metadata.created', \Dsc\Mongo\Metastamp::getDate($row['created_at']))
                ;

                if (!empty($row['title'])) {
                    $userContent->set('caption', trim($row['title']));
                }

                try {
                    $userContent->save();
                    $data[] = ['User image added for product', $mongoProduct['tracking']['model_number'], '✅'];
                } catch(Exception $e) {
                    $this->CLImate->red($e->getMessage());
                }

            } else {
                $missingProducts[] = $row['product_id'];
                $data[] = ['Product not found!', $row['product_id'], '❌'];
            }

            $this->CLImate->table($data);
        }
    }

    public function syncMatrixItems()
    {
        $salesChannels = [
            'ftspeed' => [
                'id' => '5e18ce8bf74061555646d847',
                'title' => 'FTSpeed',
                'slug' => 'ftspeed'
            ],
            'subispeed' => [
                'id' => '5841b1deb38c50ba028b4567',
                'title' => 'SubiSpeed',
                'slug' => 'subispeed'
            ]
        ];

        $swatchSQL =
            "SELECT
                eao.option_id,
                concat( 'https://www.subispeed.com/media/amconf/images/', eao.option_id, '.jpg' ) AS 'swatch_img' 
            FROM eav_attribute_option eao
            WHERE eao.attribute_id IN ( SELECT attribute_id FROM amasty_amconf_attribute WHERE use_image = 1 )"
        ;
        $swatchSelect = $this->db->prepare($swatchSQL);
        $swatchSelect->execute();
        $swatchData = $swatchSelect->fetchAll(\PDO::FETCH_KEY_PAIR);


        $sql = 
            "SELECT
                cpe.entity_id AS 'parent_id',
                relation.child_id,
                labels.`value`
                AS 'attribute_title',
                specs.attribute_id AS attribute_title_id,
                specs.`value`
                AS attribute_option_value_id,
                labels.position AS 'attribute_ordering',
                spec_label.spec_value AS 'attribute_option_value',
                sorting.sort_order AS 'attribute_option_ordering',
                STATUS.`value`
                AS 'variant_enabled' 
            FROM
                catalog_product_entity AS cpe
                LEFT JOIN catalog_product_relation relation ON cpe.entity_id = relation.parent_id
                LEFT JOIN (
                SELECT
                    * 
                FROM
                    (
                    SELECT
                        ce.entity_id,
                        ce.sku,
                        ea.attribute_id,
                        ea.attribute_code,
                    CASE
                            ea.backend_type 
                            WHEN 'varchar' THEN
                            ce_varchar.`value`
                            WHEN 'int' THEN
                            ce_int.`value`
                            WHEN 'text' THEN
                            ce_text.`value`
                            WHEN 'decimal' THEN
                            ce_decimal.`value`
                            WHEN 'datetime' THEN
                            ce_datetime.`value`
                            ELSE ea.backend_type 
                    END AS VALUE
                    FROM
                        ( SELECT sku, entity_type_id, entity_id FROM catalog_product_entity WHERE type_id = 'simple' ) AS ce
                        LEFT JOIN eav_attribute AS ea ON ce.entity_type_id = ea.entity_type_id
                        LEFT JOIN catalog_product_entity_varchar AS ce_varchar ON ce.entity_id = ce_varchar.entity_id 
                        AND ea.attribute_id = ce_varchar.attribute_id 
                        AND ea.backend_type = 'varchar'
                        LEFT JOIN catalog_product_entity_int AS ce_int ON ce.entity_id = ce_int.entity_id 
                        AND ea.attribute_id = ce_int.attribute_id 
                        AND ea.backend_type = 'int'
                        LEFT JOIN catalog_product_entity_text AS ce_text ON ce.entity_id = ce_text.entity_id 
                        AND ea.attribute_id = ce_text.attribute_id 
                        AND ea.backend_type = 'text'
                        LEFT JOIN catalog_product_entity_decimal AS ce_decimal ON ce.entity_id = ce_decimal.entity_id 
                        AND ea.attribute_id = ce_decimal.attribute_id 
                        AND ea.backend_type = 'decimal'
                        LEFT JOIN catalog_product_entity_datetime AS ce_datetime ON ce.entity_id = ce_datetime.entity_id 
                        AND ea.attribute_id = ce_datetime.attribute_id 
                        AND ea.backend_type = 'datetime' 
                    WHERE
                        ea.attribute_id IN (
                            92,
                            151,
                            152,
                            153,
                            154,
                            158,
                            155,
                            156,
                            157,
                            159,
                            160,
                            161,
                            162,
                            163,
                            164,
                            165,
                            166,
                            167,
                            168,
                            169,
                            170,
                            171,
                            172,
                            173,
                            174,
                            177,
                            183,
                            189,
                            191,
                            213,
                            218,
                            230,
                            222,
                            234,
                            225,
                            232,
                            238,
                            224,
                            243 
                        )) DATA 
                WHERE
                    
                VALUE
                    <> '' 
                ) specs ON specs.entity_id = relation.child_id
                JOIN (
                SELECT
                    super.product_id,
                    super.attribute_id,
                    label.`value`,
                    super.position 
                FROM
                    catalog_product_super_attribute AS super
                    LEFT JOIN catalog_product_super_attribute_label label ON super.product_super_attribute_id = label.product_super_attribute_id 
                WHERE
                    label.store_id = 0 
                ) labels ON labels.product_id = relation.parent_id 
                AND specs.attribute_id = labels.attribute_id
                LEFT JOIN ( SELECT option_id, VALUE AS 'spec_value' FROM eav_attribute_option_value AS eaov WHERE store_id = 0 ) spec_label ON spec_label.option_id = specs.`value`
                LEFT JOIN (
                SELECT
                    option_id,
                    @rownum := @rownum + 1 AS sort_order 
                FROM
                    (
                    SELECT
                        eao.option_id,
                        
                    VALUE
                        
                    FROM
                        eav_attribute_option eao
                        LEFT JOIN eav_attribute_option_value label ON label.option_id = eao.option_id 
                    WHERE
                        store_id = 0 
                    ORDER BY
                        eao.attribute_id,
                        eao.sort_order,
                        label.
                    VALUE
                    ASC 
                    ) DATA,
                    ( SELECT @rownum := 0 ) ROW 
                ) sorting ON sorting.option_id = specs.
                
            VALUE
                JOIN catalog_product_entity_int STATUS ON STATUS.entity_id = relation.child_id 
                AND STATUS.attribute_id = 96 
                AND STATUS.store_id = 0 
                AND STATUS.`value` = 1
            WHERE
                cpe.type_id = 'configurable' 
                AND cpe.entity_id NOT IN ( SELECT entity_id FROM catalog_product_entity_int WHERE attribute_id = 96 AND VALUE = 2 AND store_id = 0 ) 
                AND cpe.entity_id >= 9839
                ORDER BY cpe.entity_id, relation.child_id, attribute_ordering, attribute_title_id, attribute_title, attribute_option_ordering, attribute_option_value
            ";

        $select = $this->db->prepare($sql);
        $select->execute();

        while($row = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)){
            foreach($row as $parentId => $value){
                //temp var for cli output
                $data = [];
                array_push($data, ['Syncing Matrix Parent: ', $parentId, '✅']);
                $this->CLImate->table($data);
                $magentoOptionsIds = [];

                //The main product query now includes matrix parents, find our parent
                $product = (new \Shop\Models\Products)
                    ->setCondition('magento.id', $parentId)
                    ->getItem();
                
                if($product){
                    array_push($data, ['Matrix Parent Found In Mongo: ', $product->get('magento.id'), '✅']);
                }else{
                    array_push($data, ['Matrix Parent NOT in mongo: ', $parentId, '❌']);
                }

                if (empty($product->_id)) {
                    continue;
                }

                //This handles situation where matrix parent doesnt have sales channels (assign both)
                if (empty($product->get('publication.sales_channels'))) {
                    $product->set('sales_channel_ids', array_column($salesChannels, 'id'));
                }

                $product->set('product_type', 'matrix');
                $product->set('variants', [(new \Shop\Models\Prefabs\Variant)->cast()]);

                $grouped = [];
                //For each unique attribute title, keep track of all of its options
                foreach($value as $attributeKey => $attributeValue){
                    $grouped[$attributeValue['attribute_title']][] = $attributeValue;
                }

                $children = [];
                //For each unique attribute title, keep track of all of its options
                foreach($value as $attributeKey => $attributeValue){
                    $children[$attributeValue['child_id']][] = (int) $attributeValue['attribute_option_value_id'];
                }

                $attributes = [];
                //Grouped now contains an array keyed by unique attribute titles, and all of its options as values
                foreach ($grouped as $attributeTitle => $attributeOptions) {
                    //Create the attribute title and id
                    $attribute = [
                        'title' => $attributeTitle
                    ];
                    //for each attribute title, pull out all unique option values
                    // $options = array_unique(array_column($attributeOptions, 'attribute_option_value'));


                    $options = array_combine(array_column($attributeOptions, 'attribute_option_value_id'), array_column($attributeOptions, 'attribute_option_value'));

                    //We now have a unique option list for each unique attribute title, create options array
                    foreach ($options as $k => $option) {
                        $optionId = (string) new \MongoDB\BSON\ObjectID();
                        $magentoOptionsIds[$k] = $optionId;

                        $attributeOption = [
                            'id' => $optionId,
                            'value' => $option
                        ];

                        if (!empty($swatchData[$k])) {
                            try{
                                $upload = \Cloudinary\Uploader::upload($swatchData[$k], [
                                    'type' => 'upload',
                                    'format' => 'jpg',
                                    'folder' => 'swatches'
                                ]);

                                //If there is no image, dont set the swatch
                                if($upload){
                                    $attributeOption['swatch'] = $upload['public_id'];
                                }
                            }catch(Exception $e){
                                $this->CLImate->red($e->getMessage());
                            }
                            
                        }

                        $attribute['options'][] = $attributeOption;
                    }

                    $attributes[] = $attribute;
                }

                $product->set('attributes', $attributes);
                $product->save();

                foreach ($children as $magentoId => $optionIds) {
                    $mongoIds = array_values(array_intersect_key($magentoOptionsIds, array_flip($optionIds)));

                    foreach ($product->variants as $i => $variant) {
                        if (
                            !empty($variant['model_number'])
                            || count(array_diff($mongoIds, $variant['attributes'])) != 0
                        ) {
                            continue;
                        }

                        $netsuite = \Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($magentoId);
                        //If the matrix variant doesnt exist in the external mapping talbe, skip it
                        if (empty($netsuite->itemId)) {
                            continue;
                        }
                        $product->set("variants.$i.model_number", strtoupper($netsuite->itemId));
                    }
                }

                try{
                    $product->save();
                    array_push($data, ['Matrix Item Created! ', $product->get('magento.id'), '✅']);
                }catch(Exception $e){
                    array_push($data, ['Error Creating Matrix Parent', $e->getMessage(), '❌']);
                }
                $this->CLImate->table($data);

            }
        }
    }

    public function moveProductDescriptionImages()
    {
        $productDocs = \Shop\Models\Products::collection()->find([
            'copy' => new \MongoDB\BSON\Regex('<img\s', 'i')
        ], [
            'projection' => [
                'copy' => true,
                'tracking.model_number' => true
            ],
            'batchSize' => 50,
            'noCursorTimeout' => true,
        ]);

        foreach ($productDocs as $doc) {
            $description = $doc['copy'];
            $this->CLImate->blue('Checking ' . $doc['tracking']['model_number'] . ' for images we can move to Cloudinary...');

            $xpath = new \DOMXPath(@\DOMDocument::loadHTML($description));
            $images = $xpath->evaluate("//img/@src");

            $changed = false;
            foreach ($images as $image) {
                $originalImg = $image->value;
                $img = $image->value;

                if (strpos($img, '/') === 0) {
                    $img = 'https://subispeed.com' . $img;
                } else if (strpos($img, 'images/') === 0) {
                    $img = 'https://subispeed.com/' . $img;
                }

                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $parsed = parse_url($img);
                    $img = '';

                    if (isset($parsed['scheme'])) {
                        $img .= $parsed['scheme'] . '://';
                    }

                    if (isset($parsed['host'], $parsed['path'])) {
                        $parsed['path'] = implode('/', array_map(function($v){
                            return rawurlencode($v);
                        }, explode('/', $parsed['path'])));

                        $img .= $parsed['host'] . $parsed['path'];
                    }
                }

                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    continue;
                }

                $this->CLImate->yellow('Found image: ' . $img);

                try {
                    $upload = \Cloudinary\Uploader::upload($img, [
                        'type' => 'upload',
                        'folder' => 'content'
                    ]);

                    // TODO: use CNAME when Justin sets these up
                    $newImg = \cloudinary_url($upload['public_id'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
	                    'secure' => true
                    ]);

                    $this->CLImate->yellow('New image uploaded: ' . $newImg);
                } catch (\Exception $e) {
                    continue;
                }

                $description = str_replace($originalImg, $newImg, $description);
                $changed = true;
            }

            if ($changed) {
                \Shop\Models\Products::collection()->updateOne(
                    ['_id' => $doc['_id']],
                    ['$set' => [
                        'copy' => $description
                    ]]
                );

                $this->CLImate->green('Product updated!');
            }
        }
    }

    public function moveCategoryDescriptionImages()
    {
        $categoryDocs = \Shop\Models\Categories::collection()->find([
            'description' => new \MongoDB\BSON\Regex('<img\s', 'i')
        ], [
            'projection' => [
                'description' => true,
                'hierarchical_categories' => true
            ]
        ]);

        foreach ($categoryDocs as $doc) {
            $description = $doc['description'];
            $this->CLImate->blue('Checking ' . $doc['hierarchical_categories'] . ' for images we can move to Cloudinary...');

            $xpath = new \DOMXPath(@\DOMDocument::loadHTML($description));
            $images = $xpath->evaluate("//img/@src");

            $changed = false;
            foreach ($images as $image) {
                $originalImg = $image->value;
                $img = $image->value;

                if (strpos($img, '/') === 0) {
                    $img = 'https://subispeed.com' . $img;
                } else if (strpos($img, 'images/') === 0) {
                    $img = 'https://subispeed.com/' . $img;
                }

                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    $parsed = parse_url($img);
                    $img = '';

                    if (isset($parsed['scheme'])) {
                        $img .= $parsed['scheme'] . '://';
                    }

                    if (isset($parsed['host'], $parsed['path'])) {
                        $parsed['path'] = implode('/', array_map(function($v){
                            return rawurlencode($v);
                        }, explode('/', $parsed['path'])));

                        $img .= $parsed['host'] . $parsed['path'];
                    }
                }

                if (!filter_var($img, FILTER_VALIDATE_URL)) {
                    continue;
                }

                $this->CLImate->yellow('Found image: ' . $img);

                try {
                    $upload = \Cloudinary\Uploader::upload($img, [
                        'type' => 'upload',
                        'folder' => 'content'
                    ]);

                    // TODO: use CNAME when Justin sets these up
                    $newImg = \cloudinary_url($upload['public_id'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
	                    'secure' => true
                    ]);

                    $this->CLImate->yellow('New image uploaded: ' . $newImg);
                } catch (\Exception $e) {
                    continue;
                }

                $description = str_replace($originalImg, $newImg, $description);
                $changed = true;
            }

            if ($changed) {
                \Shop\Models\Categories::collection()->updateOne(
                    ['_id' => $doc['_id']],
                    ['$set' => [
                        'description' => $description
                    ]]
                );

                $this->CLImate->green('Category updated!');
            }
        }
    }
    public function syncRedirects(){
        $sql = "
        SELECT cr.store_id, cr.request_path, cr.target_path, cr.category_id, cr.product_id FROM core_url_rewrite cr
        WHERE
            ( cr.product_id IS NOT NULL OR cr.category_id IS NOT NULL)
        GROUP BY cr.request_path, cr.target_path
        ORDER BY cr.product_id
        ";
        $success = 0;
        $failed = 0;
        $select = $this->db->prepare($sql);
        $select->execute();
        $progress = $this->CLImate->progress()->total($select->rowCount());
        $this->CLImate->green($select->rowCount() . " redirects to process");
        $data = [['id', 'type', 'message', 'status']];
        while ($row = $select->fetch()) {
            if(!empty($row['product_id'])){
                try{
                    $product = (new \Shop\Models\Products)->setCondition('magento.id', $row['product_id'])->getItem();
                    if(!empty($product)){
                        $redirect = (new \Redirect\Admin\Models\Routes)->setCondition('url.alias', $row['request_path'])->getItem();
                        if(!empty($redirect)){
                            $redirect['url']['redirect'] = $product->url();
                            $redirect->save();
                        }else{
                            (new \Redirect\Admin\Models\Routes)->bind([
                                'product_id' => $product->id,
                                'title'      => "Magento non-default redirect for product " . (string)$product->id,
                                'old_slug'   => $row['request_path'],
                                'type' => 'redirect.routes',
                                'url' => ['redirect' => $product->url(), 'alias' => $row['request_path']],
                            ])->store();
                        }
                        array_push($data, [$row['product_id'], "product", $row['request_path'], "✅"]);
                        $success++;
                    }
                }catch(\Exception $e){
                    array_push($data, [$row['product_id'], "product", $row['request_path'], "❌"]);
                    $failed++;
                }
            }else if (empty($row['product_id']) && !empty($row['category_id'])){
                try{
                    $category = (new \Shop\Models\Categories)->setCondition('magento.id', $row['category_id']);
                    if(!empty($category)){
                        $redirect = (new \Redirect\Admin\Models\Routes)->setCondition('url.alias', $row['request_path'])->getItem();
                        if(!empty($redirect)){
                            $redirect['url']['redirect'] = $category->url();
                            $redirect->save();
                        }else{
                            (new \Redirect\Admin\Models\Routes)->bind([
                                'category_id' => $category->id,
                                'title'      => "Magento non-default redirect for product " . (string)$category->id,
                                'old_slug'   => $row['request_path'],
                                'url' => ['redirect' => $category->url(), 'alias' => $row['request_path']],
                            ])->store();
                        }
                        array_push($data, [$row['category_id'], "category", $row['request_path'], "✅"]);
                        $success++;
                    }
                }catch(\Exception $e){
                    array_push($data, [$row['category_id'], "category", $row['request_path'], "❌"]);
                    $failed++;
                }
            }

            if(count($data) > 2 && (count($data) - 1) % 10 === 0){
                $this->CLImate->table($data);
                $data = [['id', 'type', 'message', 'status']];
            }
        }
        if(count($data) > 1){
            $this->CLImate->table($data);
        }

        $this->CLImate->yellow("Failed redirects: $failed");
        $this->CLImate->green("Successful redirects synced: $success.");
    }

    /** Strip ymm from title. JBA saves their product titles in the format [PRODUCT_NAME] - [YMM].
     *So assuming that $title = [PRODUCT_NAME] and $ymm = [PRODUCT_NAME] - [YMM] and there for this function will return [YMM] from the $ymmTitle.*/
    private function stripYmmFromTitle($title, $ymmTitle)
    {
        preg_match('/[\s]-(.*[\s]\/[\s].*|[\s]{1,}[12][\d]{3}|[\s]Universal).*/', $ymmTitle, $titleMatches);
        //strip out the title and any remaining  - separator from start of string.

        return trim(preg_replace('/^[\s]?-/', '', !empty($titleMatches) ? $titleMatches[0] : null));
    }

    public function syncSpecs()
    {
        $sql = 
            "SELECT
                cpe.entity_id AS product_id,
                `names`.frontend_label AS label,
                `value`.`value` 
            FROM
                `catalog_product_entity_int` AS cpe
                LEFT JOIN eav_attribute AS `names` ON cpe.attribute_id = `names`.attribute_id
                LEFT JOIN eav_attribute_option_value AS `value` ON cpe.`value` = `value`.option_id 
            WHERE
                cpe.entity_type_id = 4 
                AND `value`.`value` IS NOT NULL 
                AND cpe.attribute_id IN ( SELECT attribute_id FROM catalog_eav_attribute WHERE is_filterable = 1 ) 
                AND `names`.frontend_label NOT IN ('Manufacturer', 'Sub Model')
                AND cpe.store_id = 0";

        $select = $this->db->prepare($sql);
        $select->execute();

        while ($row = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)) {
            foreach ($row as $magentoId => $specs) {
                $product = (new \Shop\Models\Products)
                    ->setCondition('magento.id', $magentoId)
                    ->getItem();

                if (empty($product->id)) {
                    continue;
                }

                $mongoSpecs = array_combine(str_replace('.', '', array_column($specs, 'label')), array_column($specs, 'value'));
                $product->set('specs', $mongoSpecs);
                
                try {
                    $product->save();
                } catch (\Exception $e) {
                    if ($product->get('product_type') == 'group') {
                        $product->set('product_type', 'standard');
                        $product->save();
                    }
                }
            }
        }
    }

    public function syncInstallInstructions()
    {
        $api = new \Cloudinary\Api();
        $sql = 
            "SELECT 
                def.entity_id AS 'id',
                cpe.sku AS 'model',
                install.value as 'install instructions'
            FROM
                catalog_product_entity_int def
                LEFT JOIN catalog_product_entity cpe ON def.entity_id = cpe.entity_id
                JOIN catalog_product_entity_text AS install ON ( def.entity_id = install.entity_id AND install.store_id = 0 AND install.attribute_id = 144)
            WHERE
                def.attribute_id = 102
                AND def.store_id = 0
                AND install.value is not null
            ORDER BY def.entity_id ASC";

        $select = $this->db->prepare($sql);
        $select->execute();
        $progress = $this->CLImate->progress()->total($select->rowCount());

        while ($row = $select->fetch()) {
            try {
                $netsuiteProduct = \Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($row['id']);
                //If we found a netsuite product, lookup product in mongo by the netsuite internal id
                if ($netsuiteProduct) {
                    $product = (new \Shop\Models\Products)
                    ->setCondition('netsuite.internalId', (string) $netsuiteProduct->internalId)
                    ->getItem();
                }

                //If both the above queries didnt find anything, try to lookup by the magento id
                if (!$netsuiteProduct) {
                    $product = (new \Shop\Models\Products)
                    ->setCondition('magento.id', $row['id'])
                    ->getItem();
                }
                
                if(empty($product)) {
                    $progress->advance(1, $row['model']);
                    continue;
                }

                $results = $api->resources_by_tag('install_' . $product->getCouldinaryTag(), ['folder' =>  'product_install_instructions','context' => true, 'max_results' => 100]);
                //If there is install instructions in the row and cloudinary doesn't have this install instruction.
                if (!empty($row['install instructions']) && empty($results['resources'])) {
                    //extract href value from tag.
                    preg_match('/href="(.*?)"/', $row['install instructions'], $instructionMatches);
                    //if we have a group match parse url
                    if(!empty($instructionMatches[1])){
                        //parse hrefness
                        $instructionUrl = parse_url($instructionMatches[1]);
                        //start building valid link.
                        $link = !empty($instructionUrl['scheme']) ? $instructionUrl['scheme'] : 'http://';//set scheme if not found
                        $link .= !empty($instructionUrl['host']) ? $instructionUrl['host'] : 'www.subispeed.com';//set host if not found
                        //this shouldn't happen but just in case they are using odd stuff in href="" break out and abort upload
                        if (empty($instructionUrl['path'])) {
                            continue;
                        }
                        //append path to link
                        $link .= $instructionUrl['path'];

                        //attempt to upload pdf from link we just built
                        try {
                            //upload to cloudinary
                            $upload = \Cloudinary\Uploader::upload(trim($link), [
                                'tags' => 'install_' . $product->get('tracking.model_number_flat'),
                                'format' => 'pdf',
                                'folder' => 'product_install_instructions',
                            ]);

                            $pdfUrl = \cloudinary_url($upload['public_id'], [
                                'fetch_format' => 'auto',
                                'sign_url' => true,
                                'secure' => true
                            ]);

                            //set the install_instructions value to the secure url from cloudinary.
                            $product->set('install_instructions', $pdfUrl);//set the path to cloudinary
                        } catch(\Exception $e) {
                            // do nothing.
                            // var_dump($e->getMessage());die('Failed to upload to cloudinary.');
                        }
                    }
                } else if(!empty($results['resources'])) {
                    $pdfUrl = \cloudinary_url($results['resources'][0]['public_id'], [
                        'fetch_format' => 'auto',
                        'sign_url' => true,
                        'secure' => true
                    ]);

                    $product->set('install_instructions', $pdfUrl);
                }

                $product->save();
            } catch(\Exception $e) {
                // do nothing
            }

            $progress->advance(1, $row['model']);
        }
    }

    public function syncEmailsFromRally()
    {
        $listIds = [
            'subispeed' => 350899,
            'ftspeed'   => 350900
        ];

        $messageIds = [
            11365437 => ['subispeed' => 11419668, 'ftspeed' => 11419670],
            11337429 => ['subispeed' => 11421643, 'ftspeed' => 11421648],
            11336939 => ['subispeed' => 11421402, 'ftspeed' => 11421389],
            11335992 => ['subispeed' => 11421404, 'ftspeed' => 11421381],
            11336619 => ['subispeed' => 11419669, 'ftspeed' => 11419671],
            11367215 => ['subispeed' => 11421405, 'ftspeed' => 11421384],
            11335962 => ['subispeed' => 11421407, 'ftspeed' => 11421391],
            11335974 => ['subispeed' => 11419666, 'ftspeed' => 11419673],
            11338555 => ['subispeed' => 11421635, 'ftspeed' => 11421382],
            11344166 => ['subispeed' => 11421639, 'ftspeed' => 11421383],
            11336618 => ['subispeed' => 11419675, 'ftspeed' => 11419674],
            11335975 => ['subispeed' => 11421634, 'ftspeed' => 11421396],
            11336935 => ['subispeed' => 11422468, 'ftspeed' => 11422469],
            11335993 => ['subispeed' => 11422462, 'ftspeed' => 11422467]
        ];

        $fieldIds = [
            7369 => ['subispeed' => 10347, 'ftspeed' => 10409],
            7373 => ['subispeed' => 10351, 'ftspeed' => 10413],
            7374 => ['subispeed' => 10352, 'ftspeed' => 10414],
            7365 => ['subispeed' => 10344, 'ftspeed' => 10406],
            7372 => ['subispeed' => 10350, 'ftspeed' => 10412],
            7375 => ['subispeed' => 10353, 'ftspeed' => 10415],
            7367 => ['subispeed' => 10346, 'ftspeed' => 10408],
            7371 => ['subispeed' => 10349, 'ftspeed' => 10411],
            7304 => ['subispeed' => 10329, 'ftspeed' => 10391],
            7308 => ['subispeed' => 10332, 'ftspeed' => 10394],
            7309 => ['subispeed' => 10333, 'ftspeed' => 10395],
            7302 => ['subispeed' => 10327, 'ftspeed' => 10389],
            7307 => ['subispeed' => 10331, 'ftspeed' => 10393],
            7310 => ['subispeed' => 10334, 'ftspeed' => 10396],
            7303 => ['subispeed' => 10328, 'ftspeed' => 10390],
            7306 => ['subispeed' => 10330, 'ftspeed' => 10392],
            7315 => ['subispeed' => 10339, 'ftspeed' => 10401],
            7379 => ['subispeed' => 10357, 'ftspeed' => 10419],
            7378 => ['subispeed' => 10356, 'ftspeed' => 10418],
            7301 => ['subispeed' => 10326, 'ftspeed' => 10388],
            7316 => ['subispeed' => 10340, 'ftspeed' => 10402],
            7370 => ['subispeed' => 10348, 'ftspeed' => 10410],
            7314 => ['subispeed' => 10338, 'ftspeed' => 10400],
            7312 => ['subispeed' => 10336, 'ftspeed' => 10398],
            7317 => ['subispeed' => 10341, 'ftspeed' => 10403],
            7318 => ['subispeed' => 10342, 'ftspeed' => 10404],
            7376 => ['subispeed' => 10354, 'ftspeed' => 10416],
            7377 => ['subispeed' => 10355, 'ftspeed' => 10417],
            7366 => ['subispeed' => 10345, 'ftspeed' => 10407],
            7311 => ['subispeed' => 10335, 'ftspeed' => 10397],
            7313 => ['subispeed' => 10337, 'ftspeed' => 10399],
            7364 => ['subispeed' => 10343, 'ftspeed' => 10405],
            7380 => ['subispeed' => 10358, 'ftspeed' => 10420],
            7381 => ['subispeed' => 10359, 'ftspeed' => 10421],
            7382 => ['subispeed' => 10360, 'ftspeed' => 10422],
            7384 => ['subispeed' => 10361, 'ftspeed' => 10423],
            7385 => ['subispeed' => 10362, 'ftspeed' => 10424],
            7386 => ['subispeed' => 10363, 'ftspeed' => 10425],
            7387 => ['subispeed' => 10364, 'ftspeed' => 10426],
            7388 => ['subispeed' => 10365, 'ftspeed' => 10427],
            7389 => ['subispeed' => 10366, 'ftspeed' => 10428],
            7498 => ['subispeed' => 10367, 'ftspeed' => 10429],
            7500 => ['subispeed' => 10368, 'ftspeed' => 10430],
            7501 => ['subispeed' => 10369, 'ftspeed' => 10431],
            7505 => ['subispeed' => 10370, 'ftspeed' => 10432],
            7506 => ['subispeed' => 10371, 'ftspeed' => 10433],
            7507 => ['subispeed' => 10372, 'ftspeed' => 10434],
            7553 => ['subispeed' => 10373, 'ftspeed' => 10435],
            7556 => ['subispeed' => 10374, 'ftspeed' => 10436],
            7564 => ['subispeed' => 10375, 'ftspeed' => 10437],
            7565 => ['subispeed' => 10376, 'ftspeed' => 10438],
            7566 => ['subispeed' => 10377, 'ftspeed' => 10439],
            8223 => ['subispeed' => 10378, 'ftspeed' => 10440],
            8327 => ['subispeed' => 10379, 'ftspeed' => 10441],
            8328 => ['subispeed' => 10380, 'ftspeed' => 10442],
            8329 => ['subispeed' => 10381, 'ftspeed' => 10443],
            8330 => ['subispeed' => 10382, 'ftspeed' => 10444],
            'GC' => ['subispeed' => 10516, 'ftspeed' => 10515]
        ];

        \Mailer\Models\Templates::collection()->deleteMany([]);

        foreach ($listIds as $salesChannel => $listId) {
            $rallyTemplates = (new \JBAShop\Models\RallyMailerTemplates)
                ->setCondition('sales_channel', 'rallysport-usa')
                ->getItems();

            foreach ($rallyTemplates as $rallyTemplate) {
                $jbaTemplate = (new \Mailer\Models\Templates)->bind($rallyTemplate->cast());
                $jbaTemplate->set('_id', null);
                $jbaTemplate->set('sales_channel', $salesChannel);
                $jbaTemplate->set('event_subject', '');
                $jbaTemplate->set('event_html', '');
                $jbaTemplate->set('event_text', '');

                if (!empty($jbaTemplate->get('listrak'))) {
                    $jbaTemplate->set('listrak.listid', $listId);
                    
                    $fields = [];
                    if (!empty($messageIds[$jbaTemplate->get('listrak.messageid')][$salesChannel])) {
                        if ($jbaTemplate->get('listrak.messageid') == 11337429) {
                            $fields[$fieldIds['GC'][$salesChannel]] = '{{@giftcard.originalAmount}';
                        }

                        $jbaTemplate->set('listrak.messageid', $messageIds[$jbaTemplate->get('listrak.messageid')][$salesChannel]);
                    }

                    foreach ((array) $jbaTemplate->get('listrak.fields') as $fieldId => $value) {
                        $fields[$fieldIds[$fieldId][$salesChannel]] = $value;
                    }

                    $jbaTemplate->set('listrak.fields', $fields);
                } else {
                    continue;
                }

                $jbaTemplate->store();
            }
        }

    }

    public function getProductImagesFromCloudinary(){
        $products = \Shop\Models\Products::collection()->find(
            [
                'publication.status' => 'published',
                'publication.sales_channels' => ['$exists' => true, '$not' => ['$size' => 0]]
            ],
            [
                'sort' => ['tracking.model_number' => -1],
                'batchSize' => 50,
                'noCursorTimeout' => true,
            ]
        );

        foreach($products as $doc){
            try{
                $product = (new \Shop\Models\Products)->bind($doc);
                $count = $product->getImagesForProductFromCloudinary();
            }catch(Exception $e){
                $this->CLImate->red('ERROR: ' . $e->getMessage());
            }

            if($count > 0){
                $this->CLImate->green($count . ' Product Images Synced' . $product->get('tracking.model_number_flat'));
            }else{
                $this->CLImate->red('No Images Found For:' . $product->get('tracking.model_number'));
            }

            $this->CLImate->green('sleeping..');
            sleep(1);
        }

        
    }

    public function testCloudinaryAsync($amount){
        $model = 'SOMEFAKEMODEL';
        $magento = 1111111;
        $image = 'https://via.placeholder.com/150.jpg';


        //GlobalConfig
        $uploadProfile = [
            'google' => ['upload-preset'=> 'api_uploads', 'folder' => 'api_testing_google', 'type' => 'upload'],
            'products' => ['upload-preset'=> 'api_uploads', 'folder' => 'api_testing_products', 'type' => 'private'],
        ];

        
        //Google Config
        $userOptions = ['tags' => "test_google_{$model}", 'context' => ['magento_id' => $magento]];
        $googleOptions = array_merge($uploadProfile['google'], $userOptions);

        //Product Config
        $meta = ['caption'=> 'im a caption!', 'order' => 1];
        $productOptions = array_merge($uploadProfile['products'], ['tags' => $model, 'context' => $meta]);
        
        $images = array_fill(0, $amount, $image);

        //Send the google images
        array_walk($images, function($image) use($googleOptions){
            $uploadGoogle = \Cloudinary\Uploader::upload($image, $googleOptions);
        });

        //Send Product Images
        array_walk($images, function($image) use($productOptions){
            $uploadProduct = \Cloudinary\Uploader::upload($image, $productOptions);
        });
        

    }

    public function fixItemMatrixImages(){
        // 1. Get all published matrix items with a sales channel
        // 2. Loop through all the matrix items and look for attribute options without a swatch value
        // 3. If no swatch, set "use_product_photo" to true, else false
        $matrixProducts = (new \Shop\Models\Products)->collection()->find(
            [
                'publication.status' => 'published',
                'product_type' => 'matrix'
            ],
            [
                'noCursorTimeout' => true,
                'batchSize' => 500,
            ]
        );

        foreach($matrixProducts as $product){
            $productModel = (new \Shop\Models\Products)->bind($product);
            
            $productModel->attributes = (array) $productModel->attributes;
            usort($productModel->attributes, function($a, $b) {
                return ($a['ordering'] < $b['ordering']) ? -1 : 1;
            });

            $lastAttribute = end($productModel->get('attributes'));
            $swatchCount = count(array_column((array) $lastAttribute['options'], 'swatch'));
            $optionCount = count($lastAttribute['options']);

            if($swatchCount && $swatchCount < $optionCount){
                $productModel->set('use_product_photo_as_swatch', true);
            }
            
            $productModel->store();
    
        }
    }

    public function newFixMatrixItemImages(){
        $sql = "SELECT
                    cpsa.product_id,
                    amconf.use_image_from_product 
                FROM
                    catalog_product_super_attribute cpsa
                    LEFT JOIN amasty_amconf_product_attribute amconf ON amconf.product_super_attribute_id = cpsa.product_super_attribute_id 
                WHERE
                    amconf.use_image_from_product = 1";
        
        $select = $this->db->prepare($sql);
        $select->execute();
        $rows = $select->fetchAll(\PDO::FETCH_ASSOC);
        

        foreach($rows as $row){
            $productModel = (new \Shop\Models\Products)
                ->setCondition('magento.id', $row['product_id'])
                ->setCondition('product_type', 'matrix')
                ->getItem();

            if(empty($productModel)){
                continue;
            }

            $productModel->set('use_product_photo_as_swatch', true);

            $productModel->save();
        }
    }

    public function syncWishlists()
    {
        $sql = 
            "SELECT
                wi.wishlist_id,
                c.email,
                IF (wi.store_id = 1, 'subispeed', 'ftspeed') AS site,
                wi.product_id
            FROM wishlist_item wi
            INNER JOIN wishlist w
                ON w.wishlist_id = wi.wishlist_id
            INNER JOIN customer_entity c
                ON c.entity_id = w.customer_id
            WHERE w.updated_at > '2019-01-01'
            AND wi.store_id IN (1, 4, 5)
            ORDER BY
                wi.wishlist_id,
                wi.added_at"
        ;

        $select = $this->db->prepare($sql);
        $select->execute();

        $magentoWishlists = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP);

        $wishlistCount = count($magentoWishlists);
        $this->CLImate->green("Syncing {$wishlistCount} wishlists...");
        $progress = $this->CLImate->progress()->total($wishlistCount);

        foreach ($magentoWishlists as $magentoWishlist) {
            $email = $magentoWishlist[0]['email'];
            $progress->advance(1, $email);

            $salesChannel = $magentoWishlist[0]['site'];

            $user = (new \Users\Models\Users)
                ->setCondition('email', $email)
                ->getItem();

            if (empty($user)) {
                continue;
            }

            // $this->CLImate->green("Working on {$salesChannel} wishlist for {$email}...");

            // find existing wishlist
            $wishlist = (new \Shop\Models\Wishlists)
                ->setCondition('user_id', $user->id)
                ->setCondition('sales_channel', $salesChannel)
                ->getItem();

            // create wishlist if it doesn't exist
            if (empty($wishlist)) {
                // $this->CLImate->blue("Wishlist not found. Creating...");
                $wishlist = new \Shop\Models\Wishlists;
                $wishlist->user_id = $user->id;
                $wishlist->sales_channel = $salesChannel;
            } else {
                // $this->CLImate->blue("Wishlist found. Updating...");
            }

            foreach ($magentoWishlist as $item) {
                // get NS model number
                $netsuiteItem = \Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($item['product_id']);
                if (empty($netsuiteItem->itemId)) {
                    continue;
                }

                $netsuiteModel = $netsuiteItem->itemId;

                $product = (new \Shop\Models\Products)
                    ->setCondition('tracking.model_number', $netsuiteModel)
                    ->getItem();

                if (!empty($product->id)) {
                    $wishlist->addItem($product, [], false);
                }
            }

            if (count($wishlist->items)) {
                $wishlist->save();
                // $this->CLImate->green("Success!");
            } else {
                // $this->CLImate->red("No items found. Wishlist not created.");
            }
        }
    }
}
