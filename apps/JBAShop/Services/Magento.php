<?php

namespace JBAShop\Services;

use PDO;
use \DB\SQL;
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
    public function syncMagentoUsersToMongo($magentoEntityId = null)
    {
        $sql = "
            SELECT
                ce.entity_id,
                cevf.VALUE as firstname,
                cevl.VALUE as lastname, 
                cevp.VALUE as password_hash,
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
        ";

        //Get all the users from the magento database
        $select = $this->db->prepare($sql);
        $select->execute();
        $users = $select->fetchall();


        foreach ($users as $user) {
            //Temp variable for our cli table
            $data = [];

            try {
                //The user data structure for transforming a Magento user to a Mongo User
                $userData = [
                    'email' => $user['email'],
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
                $newUser = \Users\Models\Users::createNewUser($userData);

                if ($newUser) {
                    //New user was successfully transwered from Magento to Mongo
                    array_push($data, ['New Mongo User Created From Magento!', $newUser['email'], ✅]);

                    //See if we have an existing netsuite user for this email and division
                    $netsuiteUser = \Netsuite\Models\Customer::getCustomerFromEmail($newUser['email']);

                    //If we found a valid netsuite user, update the netsuite object on the corresponding mongo user
                    if ($netsuiteUser) {
                        array_push($data, ['Netsuite User Found!', $netsuiteUser['email'], ✅]);

                        //Try to update the mongo user we just created with the netsuite data we need
                        try {
                            $updateUser = \Users\Models\Users::updateUserNetsuiteFields($netsuiteUser['email'], [
                                'netsuite_external_id' => $netsuiteUser['externalId'],
                                'netsuite_internal_id' => $netsuiteUser['internalId'],
                                'netsuite_entity_id' => $netsuiteUser['entityId'],
                            ]);
                            array_push($data, ['Mongo User Netsuite Data Updated!', $newUser['email'], ✅]);
                        } catch (Exception $e) {
                            $this->CLImate->to('error')->red($e->getMessage());
                        }
                    } else {
                        //No netsuite user was found for this Magento customer
                        array_push($data, ['No Netsuite User Found!', $netsuiteUser['email'], ❌]);
                    }
                }
            } catch (Exception $e) {
                $this->CLImate->to('error')->red($e->getMessage());
                //This fixes CLImate exception of pushing empty array to table() 
                array_push($data, ['Email ALready Exists... Skipping', $user['email'], ❌]);
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
            ]
        ];
        // TODO: category enabled?

        $sql = "
            SELECT DISTINCT
                cc.entity_id AS id,
                cc.`value` AS `name`,
                cc1.`value` AS url_path,
                channel.channel,
            IF
                ( cce.parent_id IN ( 1, 2, 652, 333, 515, 757, 1060, 1425 ), NULL, cce.parent_id ) AS parent_id,
                position AS sort_order,
                ccdesc.description AS description 
            FROM
                catalog_category_entity_varchar cc
                JOIN catalog_category_entity_varchar cc1 ON cc.entity_id = cc1.entity_id
                JOIN catalog_category_entity_int cc_int ON cc1.entity_id = cc_int.entity_id
                JOIN eav_entity_type ee ON cc.entity_type_id = ee.entity_type_id
                JOIN catalog_category_entity cce ON cc.entity_id = cce.entity_id
                JOIN ( SELECT entity_id, description FROM catalog_category_flat_store_1 UNION SELECT entity_id, description FROM catalog_category_flat_store_4 UNION SELECT entity_id, description FROM catalog_category_flat_store_5 ) AS ccdesc ON ccdesc.entity_id = cc.entity_id 
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

                if(empty($category)){
                    $category = new \Shop\Models\Categories();
                }
                
                $category
                    ->set('title', $row['name'])
                    ->set('description', $row['description'])
                    ->set('magento.id', $row['id'])
                    ->set('magento.parent_id', $row['parent_id'])
                    ->set('gm_product_category', 'Vehicles & Parts > Vehicle Parts & Accessories');

                if (in_array($row['parent_id'], array_keys($categoryIds))) {
                    $category->set('parent', $categoryIds[$row['parent_id']]);
                } else {
                    $incomplete = true;
                    $this->CLImate->yellow($row['url_path'] . '(' . $row['id'] . ')');
                    continue;
                }
                
                //Add Sales Channels To Categories
                $categorySalesChannels = [];
                if ($row['channel'] === 'subispeed') {
                    $categorySalesChannels[] = $salesChannels['subispeed'];
                }
                if ($category['channel'] === 'ftspeed') {
                    $categorySalesChannels[] = $salesChannels['ftspeed'];
                }
                if(!empty($categorySalesChannels)){
                    $category->set('sales_channels', $categorySalesChannels);
                }

                //Advance the progress bar, output the cat title
                $progress->advance(1, $category['title']);

                $category->save();
                $categoryIds[$row['id']] = $category->id;

                try{
                    $redirect = new \Redirect\Admin\Models\Routes([
                        'title' => 'Magento category redirect: ' . $row['name'],
                        'url' => [
                            'alias'   => $row['url_path'],
                            'redirect' => $category->url()
                        ],
                        'magento' => true
                    ]);

                    $redirect->save();
                }catch(Exception $e){
                    //If redirect already exists, do nothing
                }
            }
        } while ($incomplete);
    }

    public function syncProductInfo()
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
            mB.option_id as 'brand_id',
            cpe.type_id AS 'product_type',
            youtube.value as 'youtube             video',
            install.value as 'install instructions',
            meta_title.value as 'meta title',
            is_carb.value as 'is carb',
            url_key.value as 'default url key',
            url_path.value as 'default url path',
            coupon.value as 'qualifies for coupon',
            warranty.value as 'warranty',
            mB.option_id as 'brand_id,mfg_id',
            mB.value as 'brand/manufacturer',
            cpe.sku AS 'model',
            `status`.`value` AS 'enabled',
            ! ISNULL( subi.`value` ) AS 'subispeed',
            IF (! ISNULL( ft86.`value` ) OR ! ISNULL( ftspeed.`value` ), 1, 0 ) AS 'ftspeed',
            default_name.`value` AS 'default_title',
            subi_name.`value` AS 'subispeed_title',
            ft86_name.`value` AS 'ft86_title',
            ftspeed_name.`value` AS 'ftspeed_title',
            cats.categories,
            default_desc.`value` AS 'long_description',
            default_short_desc.`value` AS 'short_description'
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
            LEFT JOIN catalog_product_entity_text AS install ON ( def.entity_id = install.entity_id AND install.store_id = 0 AND install.attribute_id = 144)
            LEFT JOIN catalog_product_entity_varchar meta_title ON ( def.entity_id = meta_title.entity_id AND meta_title.store_id = 0 AND meta_title.attribute_id = 82)
            LEFT JOIN catalog_product_entity_int AS is_carb ON ( def.entity_id = is_carb.entity_id AND is_carb.store_id = 0 AND is_carb.attribute_id = 268)
            LEFT JOIN catalog_product_entity_varchar AS url_key ON ( def.entity_id = url_key.entity_id AND url_key.store_id = 0 AND url_key.attribute_id = 97)
            LEFT JOIN catalog_product_entity_varchar AS url_path ON ( def.entity_id = url_path.entity_id AND url_path.store_id = 0 AND url_path.attribute_id = 98)
            LEFT JOIN catalog_product_entity_int AS coupon ON ( def.entity_id = coupon.entity_id AND coupon.store_id = 0 AND coupon.attribute_id = 237)
            LEFT JOIN catalog_product_entity_text AS warranty ON ( def.entity_id = warranty.entity_id AND warranty.store_id = 0 AND warranty.attribute_id = 236 )
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
                AND def.entity_id = 23646
            ORDER BY cpe.sku ASC
        ";

        $select = $this->db->prepare($sql);
        $select->execute();
        $progress = $this->CLImate->progress()->total($select->rowCount());

        $api = new \Cloudinary\Api();
        while ($row = $select->fetch()) {
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

            // TODO: query all categories once and get their full arrays for setting in products, key by magento id
            // TODO: discontinued?
            // TODO: redirect
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

            $product
                ->set('magento.id', $row['id'])
                ->set('title', $row['default_title'])
                ->set('copy', $row['long_description'])
                ->set('short_description', $row['short_description'])
                ->set('categories', $newProductCategories);

            //If we are in a situation where we are creating a new product (eg. matrix parent, set the model number)
            if(!$product->tracking['model_number']){
                $product->set('tracking.model_number', $row['model']);
            }

            $productSalesChannels = [];

            if (!empty($row['subispeed'])) {
                $productSalesChannels[] = $salesChannels['subispeed'];
            }

            if (!empty($row['ftspeed'])) {
                $productSalesChannels[] = $salesChannels['ftspeed'];
            }

            $product->set('publication.sales_channels', $productSalesChannels);

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

            if($netsuiteProduct['itemType'] === 'kit'){
                $product->set('product_type', 'group');
            }else{
                $product->set('product_type', 'standard');
            }

                $results = $api->resources_by_tag($product->getCouldinaryTag(), ['folder' =>  'product_install_instructions','context' => true, 'max_results' => 100]);
                //If there is install instructions in the row and cloudinary doesn't have this install instruction.
                if(!empty($row['install instructions']) && empty($results['resources'])){
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
                        if(empty($instructionUrl['path'])){
                            continue;
                        }
                        //append path to link
                        $link .= $instructionUrl['path'];
                        //attempt to upload pdf from link we just built
                        try{
                            //upload to cloudinary
                            $upload = \Cloudinary\Uploader::upload(trim($link), [
                                'tags' => $product->get('tracking.model_number_flat'),
                                'format' => 'pdf',
                                'folder' => 'product_install_instructions',
                            ]);
                            //TODO: change to images.jbautosports.com/ if CNAME is set up.
                            //set the install_instructions value to the secure url from cloudinary.
                            $product->set('install_instructions', $upload['secure_url']);//set the path to cloudinary
                        }catch(\Exception $e){
                            //do nothing.
                            // var_dump($e->getMessage());die('Failed to upload to cloudinary.');
                        }
                    }
                }else if(!empty($results['resources'])){
                    $product->set('install_instructions', $results['resources'][0]['secure_url']);
                }

            try{
                $product->save();
            }catch(Exception $e){
                if($e->getMessage() !== 'Not a group item'){
                    throw $e;
                }
            }


            $progress->advance(1, $row['model']);
        }
    }

    public function syncProductImages()
    {
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

            if (!$row['disabled'] || $row['featured_image']) {
                $finalList[$row['product_id']]['images'][] = $row['url'];
            }
        }
        
        foreach ($finalList as $magentoId => $links) {
            $product = (new \Shop\Models\Products)
                ->setCondition('magento.id', (int) $magentoId)
                // ->setCondition('publication.status', 'published')
                // ->setCondition('categories.id', new \MongoDB\BSON\ObjectID('5e85f71ad36f5e431c0ed942'))
                ->getItem();


            if (empty($product)) {
                continue;
            }

            $model = $product->get('tracking.model_number_flat');

            if (!empty($links['google'])) {
                $upload = \Cloudinary\Uploader::upload(trim($links['google']), [
                    'type' => 'upload',
                    'format' => 'jpg',
                    'folder' => 'google_images'
                ]);

                // TODO: make sure admin does NOT delete this field
                $product->set('google_image', $upload['public_id']);
                $product->store();
            }

            foreach ($links['images'] as $i => $image) {
                $meta = ['order' => $i + 1];
                if (!empty($image['caption'])) {
                    $meta['caption'] = $image['caption'];
                }

                \Cloudinary\Uploader::upload(trim($image), [
                    'tags' => $model,
                    'type' => 'private',
                    'format' => 'jpg',
                    'folder' => 'product_images',
                    'context' => $meta
                ]);
            }

            if (count($links['images'])) {
                $product->getImagesForProductFromCloudinary();
            }
        }
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

        $sql = "
            SELECT *
                FROM 
                (SELECT
                    bundle.parent_id AS magento_id,
                    bundle.option_id,
                    bundle.required,
                    cpd.`value` AS discount,
                    bval.title as option_title,
                    sel.product_id AS value_model_number,
                    sel.position AS value_position,
                    sel.selection_price_value AS value_price,
                    sel.selection_qty AS value_required_quantity 
                FROM
                    catalog_product_bundle_option AS bundle
                    LEFT JOIN catalog_product_bundle_option_value AS bval ON bundle.option_id = bval.option_id 
                    AND bval.store_id = 0
                    LEFT JOIN catalog_product_bundle_selection AS sel ON bundle.option_id = sel.option_id
                    LEFT JOIN catalog_product_entity_decimal cpd ON bundle.parent_id = cpd.entity_id 
                    AND cpd.attribute_id = 76 
                ) dgroups
            JOIN 
                (SELECT
                def.entity_id AS 'id',
                youtube.`value` AS 'youtube video',
                INSTALL.`value` AS 'install instructions',
                meta_title.`value` AS 'meta title',
                is_carb. `value` AS 'is carb',
                url_key.`value` AS 'default url key',
                url_path.`value` AS 'default url path',
                coupon.`value` AS 'qualifies for coupon',
                warranty.`value` AS 'warranty',
                mB.option_id AS 'brand_id,mfg_id',
                mB.`value` AS 'brand/manufacturer',
                cpe.sku AS 'model',
                `status`.`value` AS 'enabled',
                !ISNULL( subi.`value` ) AS 'subispeed',
                IF(!ISNULL( ft86.`value` ) OR ! Isnull( ftspeed.`value` ), 1, 0 ) AS 'ftspeed',
                default_name.`value` AS 'default_title',
                subi_name.`value` AS 'subispeed_title',
                ft86_name.`value` AS 'ft86_title',
                ftspeed_name.`value` AS 'ftspeed_title',
                cats.categories,
                default_desc.`value` AS 'long_description',
                default_short_desc.`value` AS 'short_description' 
            FROM
                catalog_product_entity_int def
                INNER JOIN catalog_product_entity_int AS `status` ON ( def.entity_id = `status`.entity_id AND `status`.store_id = 0 AND `status`.attribute_id = 96 AND `status`.`value` = 1 )
                LEFT JOIN catalog_product_entity cpe ON def.entity_id = cpe.entity_id
                LEFT JOIN catalog_product_entity_varchar AS default_name ON ( def.entity_id = default_name.entity_id AND default_name.store_id = 0 AND default_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_text AS default_desc ON ( def.entity_id = default_desc.entity_id AND default_desc.store_id = 0 AND default_desc.attribute_id = 72 )
                LEFT JOIN catalog_product_entity_text AS default_short_desc ON ( def.entity_id = default_short_desc.entity_id AND default_short_desc.store_id = 0 AND default_short_desc.attribute_id = 73 )
                LEFT JOIN catalog_product_entity_varchar subi_name ON ( def.entity_id = subi_name.entity_id AND subi_name.store_id = 1 AND subi_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_varchar ft86_name ON ( def.entity_id = ft86_name.entity_id AND ft86_name.store_id = 4 AND ft86_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_varchar ftspeed_name ON ( def.entity_id = ftspeed_name.entity_id AND ftspeed_name.store_id = 4 AND ftspeed_name.attribute_id = 71 )
                LEFT JOIN catalog_product_entity_int AS subi ON ( def.entity_id = subi.entity_id AND subi.store_id = 1 AND subi.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS ft86 ON ( def.entity_id = ft86.entity_id AND ft86.store_id = 4 AND ft86.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS ftspeed ON ( def.entity_id = ftspeed.entity_id AND ftspeed.store_id = 5 AND ftspeed.attribute_id = 102 )
                LEFT JOIN catalog_product_entity_int AS youtube ON ( def.entity_id = youtube.entity_id AND youtube.store_id = 0 AND youtube.attribute_id = 180 )
                LEFT JOIN catalog_product_entity_text AS INSTALL ON ( def.entity_id = INSTALL.entity_id AND INSTALL.store_id = 0 AND INSTALL.attribute_id = 144 )
                LEFT JOIN catalog_product_entity_varchar meta_title ON ( def.entity_id = meta_title.entity_id AND meta_title.store_id = 0 AND meta_title.attribute_id = 82 )
                LEFT JOIN catalog_product_entity_int AS is_carb ON ( def.entity_id = is_carb.entity_id AND is_carb.store_id = 0 AND is_carb.attribute_id = 268 )
                LEFT JOIN catalog_product_entity_varchar AS url_key ON ( def.entity_id = url_key.entity_id AND url_key.store_id = 0 AND url_key.attribute_id = 97 )
                LEFT JOIN catalog_product_entity_varchar AS url_path ON ( def.entity_id = url_path.entity_id AND url_path.store_id = 0 AND url_path.attribute_id = 98 )
                LEFT JOIN catalog_product_entity_int AS coupon ON ( def.entity_id = coupon.entity_id AND coupon.store_id = 0 AND coupon.attribute_id = 237 )
                LEFT JOIN catalog_product_entity_text AS warranty ON ( def.entity_id = warranty.entity_id AND warranty.store_id = 0 AND warranty.attribute_id = 236 )
                LEFT JOIN (
                SELECT
                    cat.product_id,
                    Group_concat( cat.category_id ) AS categories,
                    product.sku 
                FROM
                    catalog_category_product AS cat,
                    catalog_product_entity AS product 
                WHERE
                    cat.product_id = product.entity_id 
                GROUP BY
                    cat.product_id 
                ) AS cats ON cats.product_id = def.entity_id
                LEFT JOIN catalog_product_entity_int AS m ON m.attribute_id = 81 
                AND m.entity_type_id = '4' 
                AND m.store_id = 0 
                AND def.entity_id = m.entity_id
                LEFT JOIN eav_attribute_option_value mB ON mB.option_id = m.
            VALUE
                
                AND mB.store_id = 0 
            WHERE
                def.attribute_id = 102 
            AND def.store_id = 0 
            ) DATA ON DATA.id = dgroups.magento_id
            WHERE magento_id IN(5459)
        ";

        //TESTING: WHERE magento_id IN(5459, 6101, 6341)

        //This query returns 1 record for each dynamic group member. the PDO::FETCH_GROUP is a helper to group all magento for a given dynamic group together
        //For example, $productGroup will contain all recrods for a given dynamic group
        $select = $this->db->prepare($sql);
        $select->execute();

        while($rows = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)){
            foreach($rows as $magentoID => $productGroup){
                //Set a few temp variables for product level values
                $options = [];
                $modelNumber = '';
                $categoriesIDs = [];
                $productSalesChannels = [];

                //Loop through each kit option/kit option value
                foreach($productGroup as $productOption){
                    //This value is what we'll use for the model number of the dynamic group product itself
                    $modelNumber = $productOption['model'];
                    
                    //Set the categories for the product
                    $categoriesIDs = array_unique(explode(',', $productOption['categories']));

                    //Dynamic Kit Option Properties
                    $options[$productOption['option_id']]['id'] = new \MongoDB\BSON\ObjectID();
                    $options[$productOption['option_id']]['title'] = $productOption['option_title'];
                    $options[$productOption['option_id']]['allow_none'] = $productOption['required'] ? false : true;
                    $options[$productOption['option_id']]['quantity'] = (int) $productOption['value_required_quantity'];
                    $options[$productOption['option_id']]['discount_percentage'] = 0;

                    //Dynamic Kit option value properties
                    $options[$productOption['option_id']]['values'][] = [
                        'id' => new \MongoDB\BSON\ObjectID(),
                        'model_number' => (\Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($productOption['value_model_number']))->itemId,
                        'magento_id' => $productOption['value_model_number'],
                        'option_id' => $productOption['option_id'],
                        'title' => $productOption['option_title']
                    ];

                    //Set the sales channels for the dyamic kit based on hardcoded array at the top
                    if($productOption['subispeed']){
                        $productSalesChannels[] = $salesChannels['subispeed'];
                    }
                    if($productOption['ftspeed']){
                        $productSalesChannels[] = $salesChannels['ftspeed'];
                    }
                }

                //Call The Helper Function to get all the outermost categories for the product
                $categories = $this->getOuterMostCategories($categoriesIDs);

                //The next two lines check to see if the product already exists based on magento ID
                //So we dont create the same dynamic kit twice. Instead, update it if it exists
                $newProduct = (new \Shop\Models\Products)->setCondition('magento.id', $magentoID)->getItem();
                if(empty($newProduct)){
                    $newProduct = new \Shop\Models\Products();
                }

                //Build/Update the dyanmic kit and save it to mongo
                $newProduct->set('product_type', 'dynamic_group')
                    ->set('categories', $categories)
                    ->set('tracking', [
                        'model_number' => $modelNumber,
                    ])
                    ->set('magento_test', true)
                    ->set('magento', ['id' => $magentoID])
                    ->set('title', 'dont forget to update me!')
                    ->set('kit_options', array_values($options))
                    ->set('publication.sales_channels', array_unique($productSalesChannels))
                    //Kit level discount
                    ->set('prices.group_discount_percentage', 'TODO DONT FORGET ME!!');

                try{
                    $newProduct->save();
                }catch(Exception $e){
                    $this->CLImate->red($e->getMessage());
                }
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
                FROM catalog_product_entity_media_gallery
            )";    

        $select = $this->db->prepare($sql);
        $select->execute();

        $missingProducts = [];
        while ($row = $select->fetch(\PDO::FETCH_ASSOC)) {
            if (in_array($row['product_id'], $missingProducts)) {
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
                        [ 'magento.user_id' => (int) $rating['customer_id'] ],
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
        $sql = "
            SELECT cpe.entity_id      AS 'parent_id', 
                relation.child_id, 
                labels.value          AS 'attribute_title', 
                specs.attribute_id    AS attribute_title_id, 
                specs.value           AS attribute_option_value_id, 
                labels.position       AS 'attribute_ordering', 
                spec_label.spec_value AS 'attribute_option_value', 
                sorting.sort_order    AS 'attribute_option_ordering', 
                status.value          AS 'variant_enabled' 
            FROM   catalog_product_entity AS cpe 
                LEFT JOIN catalog_product_relation relation 
                        ON cpe.entity_id = relation.parent_id 
                LEFT JOIN(SELECT * 
                            FROM   (SELECT ce.entity_id, 
                                            ce.sku, 
                                            ea.attribute_id, 
                                            ea.attribute_code, 
                                            CASE ea.backend_type 
                                            WHEN 'varchar' THEN ce_varchar.value 
                                            WHEN 'int' THEN ce_int.value 
                                            WHEN 'text' THEN ce_text.value 
                                            WHEN 'decimal' THEN ce_decimal.value 
                                            WHEN 'datetime' THEN ce_datetime.value 
                                            ELSE ea.backend_type 
                                            end AS value 
                                    FROM   (SELECT sku, 
                                                    entity_type_id, 
                                                    entity_id 
                                            FROM   catalog_product_entity 
                                            WHERE  type_id = 'simple') AS ce 
                                            LEFT JOIN eav_attribute AS ea 
                                                ON ce.entity_type_id = ea.entity_type_id 
                                            LEFT JOIN catalog_product_entity_varchar AS 
                                                    ce_varchar 
                                                ON ce.entity_id = ce_varchar.entity_id 
                                                    AND ea.attribute_id = 
                                                        ce_varchar.attribute_id 
                                                    AND ea.backend_type = 'varchar' 
                                            LEFT JOIN catalog_product_entity_int AS ce_int 
                                                ON ce.entity_id = ce_int.entity_id 
                                                    AND ea.attribute_id = 
                                                        ce_int.attribute_id 
                                                    AND ea.backend_type = 'int' 
                                            LEFT JOIN catalog_product_entity_text AS ce_text 
                                                ON ce.entity_id = ce_text.entity_id 
                                                    AND ea.attribute_id = 
                                                        ce_text .attribute_id 
                                                    AND ea.backend_type = 'text' 
                                            LEFT JOIN catalog_product_entity_decimal AS 
                                                    ce_decimal 
                                                ON ce.entity_id = ce_decimal.entity_id 
                                                    AND ea.attribute_id = 
                                                        ce_decimal.attribute_id 
                                                    AND ea.backend_type = 'decimal' 
                                            LEFT JOIN catalog_product_entity_datetime AS 
                                                    ce_datetime 
                                                ON ce.entity_id = ce_datetime.entity_id 
                                                    AND ea.attribute_id = 
                                                        ce_datetime.attribute_id 
                                                    AND ea.backend_type = 'datetime' 
                                    WHERE  ea.attribute_id IN ( 92, 151, 152, 153, 
                                                                154, 158, 155, 156, 
                                                                157, 159, 160, 161, 
                                                                162, 163, 164, 165, 
                                                                166, 167, 168, 169, 
                                                                170, 171, 172, 173, 
                                                                174, 177, 183, 189, 
                                                                191, 213, 218, 230, 
                                                                222, 234, 225, 232, 
                                                                238, 224, 243 )) data 
                            WHERE  value <> '') specs 
                        ON specs.entity_id = relation.child_id 
                JOIN (SELECT super.product_id, 
                                super.attribute_id, 
                                label.value, 
                                super.position 
                        FROM   catalog_product_super_attribute AS super 
                                LEFT JOIN catalog_product_super_attribute_label label 
                                    ON super.product_super_attribute_id = 
                                        label.product_super_attribute_id 
                        WHERE  label.store_id = 0) labels 
                    ON labels.product_id = relation.parent_id 
                        AND specs.attribute_id = labels.attribute_id 
                LEFT JOIN (SELECT option_id, 
                                    value AS 'spec_value' 
                            FROM   eav_attribute_option_value AS eaov 
                            WHERE  store_id = 0) spec_label 
                        ON spec_label.option_id = specs.value 
                LEFT JOIN (SELECT option_id, 
                                    @rownum := @rownum + 1 AS sort_order 
                            FROM   (SELECT eao.option_id, 
                                            value 
                                    FROM   eav_attribute_option eao 
                                            LEFT JOIN eav_attribute_option_value label 
                                                    ON label.option_id = eao.option_id 
                                    WHERE  store_id = 0 
                                    ORDER  BY eao.attribute_id, 
                                                eao.sort_order, 
                                                label.value ASC) data, 
                                    (SELECT @rownum := 0) row) sorting 
                        ON sorting.option_id = specs.value 
                JOIN catalog_product_entity_int status 
                    ON status.entity_id = relation.child_id 
                        AND status.attribute_id = 96 
                        AND status.store_id = 0 
                        AND status.value = 1 
            WHERE  cpe.type_id = 'configurable' 
            ORDER  BY cpe.entity_id, relation.child_id, attribute_ordering, attribute_title, attribute_option_ordering, attribute_option_value
        ";

        $select = $this->db->prepare($sql);
        $select->execute();

        while($row = $select->fetchAll(\PDO::FETCH_ASSOC | \PDO::FETCH_GROUP)){
            foreach($row as $parentId => $value){
                $magentoOptionsIds = [];

                //The main product query now includes matrix parents, find our parent
                $product = (new \Shop\Models\Products)
                    ->setCondition('magento.id', $parentId)
                    ->getItem();

                if (empty($product->_id)) {
                    continue;
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
                        'title' => (string) new \MongoDB\BSON\ObjectID()
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
                        shutup mike
                        if (empty($netsuite->itemId)) {
                            continue;
                        }

                        $product->set("variants.$i.model_number", $netsuite->itemId);
                    }
                }

                $product->save();
            }

            //Todo - Attribute.is_color - Need to set this value (based on the string Color?)
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
            ]
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
}
