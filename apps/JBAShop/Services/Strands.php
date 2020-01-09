<?php
namespace RallyShop\Services;

class Strands
{
	protected $cleanKeys = [
		'title',
		'description',
		'manufacturer',
		'brand',
		'product_type'
	];
	protected $path = '/home/chris/rallysportdirect.com/public/';

	public function __construct($path = '/home/chris/rallysportdirect.com/public/') {
		$this->path = $path;
	}
	
	public function compressFeeds($files = [])
	{
		
		foreach ($files as $file) {
			// Name of the gz file we're creating
			$gzfile = $file.".gz";
			
			// Open the gz file (w9 is the highest compression)
			$fp = gzopen ($gzfile, 'w9');
			
			// Compress the file
			gzwrite ($fp, file_get_contents($file));
			
			// Close the gz file and we're done
			gzclose($fp);
		}
		
	}
	
	public function generateFeeds()
	{
		/** @var \MongoDb $mongo */
		$mongo = \Dsc\System::instance()->get('mongo');

		/** @var \MongoCollection $collection */
		$categoryCollection = \Dsc\Mongo\Helper::getCollection('common.categories');

		/** @var \MongoCollection $collection */
		$productCollection = \Dsc\Mongo\Helper::getCollection('shop.products');

		$categories = $categoryCollection->find([
			'publication.status' => 'published',
			'gm_product_category' => [
				'$exists' => true,
				'$ne'     => ''
			]
		]);

		$productIds = [];

		$productsWriter = $this->startXML('rsd_products');

		foreach ($categories as $category) {
			$googleProductCategory = $category['gm_product_category'];

			$query = [
				'publication.status' => 'published',
				'inventory_count'    => ['$gt' => 0],
				'categories.0.id'    => new \MongoDB\BSON\ObjectID((string) $category['_id'])
			];

			$products = $productCollection->find($query);

			$categoryTree = $category['title'];

			if (!empty($category['parent'])) {
				$parentCategory = $categoryCollection->findOne(['_id' => new \MongoDB\BSON\ObjectID((string) $category['parent'])], [
                    'projection' => [
                        'title' => true
                    ]
                ]);
				if (!empty($parentCategory)) {
					if ($parentCategory['title'] == 'Clothing and Accessories') {
						continue;
					}

					$categoryTree = $parentCategory['title'] . ' > ' . $categoryTree;
				}
			}

			foreach ($products as $data) {
				/** @var \RallyShop\Models\Products $product */
				$product = (new \RallyShop\Models\Products())->bind($data);

				$productId = (string) $product->id;
				if (in_array($productId, $productIds)) {
					continue;
				}

				$productIds[] = $productId;

				$modelNumber = $product->{'tracking.model_number'};
				$mpn = trim(substr($modelNumber, strpos($modelNumber, ' ')));

				$title = $product->title;
				$link = 'http://www.rallysportdirect.com/shop/product/' . $product->slug ;

				$item = [
					'title'                     => $title,
					'link'                      => $link,
					'description'               => preg_replace('/(\s)+/', ' ', $product->get('copy')),
					'id'                      => $modelNumber,
					'man_model_number'                     => $mpn,
					'price'                   => $product->price(),
					'image_link'              => $product->featuredImage(),
					'manufacturer'            => $product->get('manufacturer.title'),
					'brand'                   => $product->get('manufacturer.title'),
					'category'                => $category['title'] 
				];

				$this->addItem($productsWriter, $item, $product);

			}
		}

		$this->endXML($productsWriter);
	
	}

	
	
	/**
	 * @param $name
	 * @return \XMLWriter
	 */
	protected function startXML($name)
	{
		$writer = new \XMLWriter();
		$writer->openUri($this->path."{$name}.xml");
		$writer->setIndent(true);
		$writer->startDocument('1.0','UTF-8');

		$writer->startElement('sbs-catalog');
		$writer->writeAttribute('version', '1.0');
		
		$writer->writeElement('company', 'rallysportdirect');
		$writer->writeElement('link', 'http://www.rallysportdirect.com');
		$writer->writeElement('description', 'Rallysport Direct Preformance parts');
		
		return $writer;
	}

	/**
	 * @param \XMLWriter $writer
	 */
	protected function endXML(\XMLWriter $writer)
	{
		$writer->endElement(); //sbs-catalog
		$writer->endDocument();
		$writer->flush();
	}

	/**
	 * @param \XMLWriter $writer
	 * @param array $array
	 */
	protected function addItem(\XMLWriter $writer, array $array, $product)
	{
		$writer->startElement('item');
		foreach ($array as $k => $v) {
			if (in_array($k, $this->cleanKeys)) {
				$v = $this->cleanString($v);
			}

			$writer->writeElement($k, $v);
		}
		if(!empty($product->ymms)) {
			foreach($product->ymms as $ymm) {
				$writer->writeElement('ymm', $ymm['slug']);
			}
		}
		if(!empty($product->get('review_rating_counts.total'))) {
				$writer->writeElement('review_rating_count_total', $product->get('review_rating_counts.total'));
		}
		if(!empty($product->get('review_rating_counts.overall'))) {
			$writer->writeElement('review_rating_count_overall', $product->get('review_rating_counts.overall'));
		}
		
		
		
		$writer->endElement();
	}

	/**
	 * @param $string
	 * @return string
	 */
	protected function cleanString($string)
	{
		$string = preg_replace('/[^\x{0009}\x{000a}\x{000d}\x{0020}-\x{D7FF}\x{E000}-\x{FFFD}]+/u', ' ', $string);
		return html_entity_decode(htmlentities(html_entity_decode(strip_tags($string))), ENT_XML1 | ENT_SUBSTITUTE);
	}
}
