<?php

namespace JBAShop\Services;

use PDO;
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
        $this->db = self::getDB();
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

                if (!empty($row['subispeed'])) {
                    $product->set('publication.sales_channels.0', $salesChannels['subispeed']);
                }

                if (!empty($row['ftspeed'])) {
                    $product->set('publication.sales_channels.0', $salesChannels['ftspeed']);
                }

                if (!empty($row['enabled'])) {
                    $product->set('publication.status', $salesChannels['published']);
                }

                $product->save();
            }

            $progress->advance();
        }
    }

    public function assignCloudinaryImages()
    {
        // This is not a sync

        // Images need to be uploaded and tagged by flat model in cloudinary
        // Then we can run something here if needed to populate them on the products (something may already exist)
    }

    public function syncDynamicGroupProducts()
    {

        $sql = "
            SELECT
                bundle.parent_id AS magento_id,
                bundle.option_id,
                bval.title AS option_title,
                cpd.`value` AS bundle_discount,
                bundle.required AS option_required,
                sel.product_id AS value_model_number,
                sel.position AS value_position,
                sel.selection_price_value AS value_price,
                sel.selection_qty AS value_required_quantity 
            FROM
                catalog_product_bundle_option AS bundle
                LEFT JOIN catalog_product_bundle_option_value AS bval ON bundle.option_id = bval.option_id
                LEFT JOIN catalog_product_bundle_selection AS sel ON bundle.option_id = sel.option_id
                LEFT JOIN catalog_product_entity_decimal cpd ON bundle.parent_id = cpd.entity_id 
            WHERE
                bval.store_id = 0 
                AND cpd.attribute_id = 76
                AND bundle.parent_id = 5219
        ";

        //Execute query one, to get dynamic group parents
        $select = $this->db->prepare($sql);
        $select->execute();
        $rows = $select->fetchAll(PDO::FETCH_GROUP);

        $progress = $this->CLImate->progress()->total(count($rows));

        //All of the records are now grouped into product gruops based on the magento_id, start looping through them
        foreach ($rows as $productGroupKey => $productGroupValue) {
            //Create the parent property for mongo
            $kit_options_builder = [];
            $kit_options = [];

            //This is the replItem that corresponds to the magento entity id of the product group
            //$netsuiteProduct = \Netsuite\Models\ExternalItemMapping::getByExternalErpProductId((string) $productGroupKey);

            //Test to see if i can get a product
            //$productTest = (new \Shop\Models\ProductTest)->setCondition('tracking.model_number', $netsuiteProduct->itemId)->getItem();

            //Loop through each option grouping the options values together
            foreach ($productGroupValue as $productOptionKey => $productOptionValue) {
                $kit_options_builder[$productOptionValue['option_id']]['values'][] = [
                    'id' => new \MongoDB\BSON\ObjectID(),
                    'title' => null,
                    'magento_entity_id' => $productOptionValue['value_model_number'],
                    'model_number' => (\Netsuite\Models\ExternalItemMapping::getNetsuiteItemByProductId($productOptionValue['value_model_number']))->itemId,
                    'required_model_number' => '',
                    'required_product_id' => null,
                ];
            }

            $newProduct = (new \Shop\Models\ProductTest())
                ->set('product_type', 'dynamic_group')
                ->set('tracking', ['model_number' => 'magento_test_model_number'])
                ->set('magento_test', true)
                ->set('slug', 'some-slug')
                ->set('title', 'some-title')
                ->set('kit_options', $kit_options_builder);

            $newProduct->save();


            // $newProduct = (new \Shop\Models\ProductTest())
            //     ->set('tracking', ['model_number' => $netsuiteProduct->itemId])
            //     ->set('magento_test', true)
            //     ->set('slug', 'some-slug')
            //     ->set('title', 'some-title')
            //     ->set('kit_options', $kit_options_builder);

            // $newProduct->save();
            $progress->advance();
        }
    }
}
