<?php namespace JBAShop\Services;

use \DB\SQL;
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
    public function syncMagentoUsersToMongo($magentoEntityId = null){
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
            LIMIT 3
        ";

        //Get all the users from the magento database
        $select = $this->db->prepare($sql);
        $select->execute();
        $users = $select->fetchall();

        
        foreach($users as $user){
            //Temp variable for our cli table
            $data = [];
            
            try{
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
                ]; 
                //Create the new user in mongo
                $newUser = \Users\Models\Users::createNewUser( $userData, $registration_action );
                
                if($newUser){
                    //New user was successfully transwered from Magento to Mongo
                    array_push($data, ['New Mongo User Created From Magento!', $newUser['email'], ✅]);
                    
                    //See if we have an existing netsuite user for this email and division
                    $netsuiteUser = \Netsuite\Models\Customer::getCustomerFromEmail($newUser['email']);
                    
                    //If we found a valid netsuite user, update the netsuite object on the corresponding mongo user
                    if($netsuiteUser){
                        array_push($data, ['Netsuite User Found!', $netsuiteUser['email'], ✅]);
                        
                        //Try to update the mongo user we just created with the netsuite data we need
                        try{
                            $updateUser = \Users\Models\Users::updateUserNetsuiteFields($email, [
                                'netsuite_external_id' => $netsuiteUser['externalId'],
                                'netsuite_internal_id' => $netsuiteUser['internalId'],
                                'netsuite_entity_id' => $netsuiteUser['entityId'],
                            ]);
                            array_push($data, ['Mongo User Netsuite Data Updated!', $newUser['email'], ✅]);
                        }catch(Exception $e){
                            $this->CLImate->to('error')->red($e->getMessage());
                        }
                    }else{
                        //No netsuite user was found for this Magento customer
                        array_push($data, ['No Netsuite User Found!', $netsuiteUser['email'], ❌]);
                    }
                }
            }catch(Exception $e){
                $this->CLImate->to('error')->red($e->getMessage());
            }
            
            //Write our output for this iteration of the loop
            $this->CLImate->table($data);
        }
    }

    public function syncCategories()
    {
        // TODO: category enabled?

        $sql = "
            SELECT DISTINCT
                cc.entity_id AS id,
                cc.`value` AS `name`,
                cc1.`value` AS url_path,
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
            
            while ($row = $select->fetch()) {
                if (in_array($row['id'], array_keys($categoryIds))) {
                    continue;
                }
                // TODO: images, etc
                // TODO: assign products to categories

                $category = new \Shop\Models\Categories([
                    'title'   => $row['name'],
                    'description' => $row['description'],
                    // 'slug'    => $row['url_path'],
                    'magento' => [
                        'id'        => $row['id'],
                        'parent_id' => $row['parent_id']
                    ]
                ]);

                if (in_array($row['parent_id'], array_keys($categoryIds))) {
                    $category->set('parent', $categoryIds[$row['parent_id']]);
                } else {
                    $incomplete = true;
                    $this->CLImate->yellow($row['url_path'] . '(' . $row['id'] . ')');
                    continue;
                }
                
                //Advance the progress bar, output the cat title
                $progress->advance(1, $category['title']);

                $category->save();
                $categoryIds[$row['id']] = $category->id;

                $redirect = new \Redirect\Admin\Models\Routes([
                    'title' => 'Magento category redirect: ' . $row['name'],
                    'url' => [
                        'alias'   => $row['url_path'],
                        'redirect' => $category->url()
                    ],
                    'magento' => true
                ]);
                $redirect->save();
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
        foreach(\Shop\Models\Categories::collection()->find(['magento.id' => ['$exists' => true]], ['projection' => ['slug' => 1, 'title' => 1, 'magento' => 1, 'ancestors' => 1]]) as $category) {
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
                cpe.sku AS 'model',
                `status`.`value` AS 'enabled',
                !ISNULL(subi.`value`) AS 'subispeed',
                IF (!ISNULL(ft86.`value`) OR !ISNULL(ftspeed.`value`), 1, 0) AS 'ftspeed',
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
                LEFT JOIN catalog_product_entity_int AS m
                    ON m.attribute_id = 81
                    AND m.entity_type_id = '4'
                    AND m.STORE_ID = 0
                    AND def.entity_id = m.entity_id
                LEFT JOIN eav_attribute_option_value mB
                    ON mB.option_id = m.value
                    AND mB.STORE_ID = 0
            WHERE
                def.attribute_id = 102 
                AND def.store_id = 0
            ORDER BY cpe.sku ASC
        ";

        $select = $this->db->prepare($sql);
        $select->execute();
        $progress = $this->CLImate->progress()->total($select->rowCount());

        while ($row = $select->fetch()) {
            $netsuiteProduct = \Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($row['id']);
            if (!$netsuiteProduct) {
                $progress->advance(1, $row['model']);
                continue;
            }

            $product = (new \Shop\Models\Products)
                ->setCondition('netsuite.internalId', (string) $netsuiteProduct->internalId)
                ->getItem();

            // TODO: query all categories once and get their full arrays for setting in products, key by magento id
            // TODO: discontinued?
            // TODO: redirect
            // TODO: brands

            if (!empty($product->id)) {
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
                
                $newProductCategories = array_values(array_filter($toAdd, function($v) use ($toRemove) {
                    return !in_array($v['id'], \Dsc\ArrayHelper::getColumn($toRemove, 'id'));
                }));

                $product
                    ->set('magento.id', $row['id'])
                    ->set('title', $row['default_title'])
                    ->set('copy', $row['long_description'])
                    ->set('short_description', $row['short_description'])
                    ->set('categories', $newProductCategories)
                ;

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

                $product->save();
            }

            $progress->advance(1, $row['model']);
        }
    }

    public function assignCloudinaryImages()
    {
        // This is not a sync

        // Images need to be uploaded and tagged by flat model in cloudinary
        // Then we can run something here if needed to populate them on the products (something may already exist)
    }
}
