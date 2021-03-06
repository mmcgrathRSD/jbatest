<?php

namespace JBAShop\Services;

use League\CLImate\CLImate;

class GoogleProductsFeed
{
	protected $cleanKeys = [
		'title',
		'description',
		'g:manufacturer',
		'g:brand',
		'g:google_product_category',
		'g:product_type'
	];
	protected $path;
	protected $fileName;
	protected $mate;

	public function __construct($path)
	{
		$this->path = $path;
		$this->mate =  new CLImate();
	}

	public function compressFeeds($files = [])
	{
		foreach ($files as $file) {
			// Name of the gz file we're creating
			$gzfile = $file . ".gz";

			// Open the gz file (w9 is the highest compression)
			$fp = gzopen($gzfile, 'w9');

			// Compress the file
			gzwrite($fp, file_get_contents($file));

			// Close the gz file and we're done
			gzclose($fp);
		}
	}

	public function generateFeeds($channel)
	{
		$name = $channel->get('slug') . '_products';
		\JBAShop\Services\Magento::setCloudinaryCNAME("images.{$channel->get('slug')}.com");
		/** @var \MongoCollection $collection */
		$this->mate->blue("Generating {$channel->get('slug')} google product feed.");
		$productsWriter = $this->startXML($name);
		//generate non matrix and matrix_subitems.
		$this->groupAndStandardProductFeed($channel, $productsWriter);
		//now do the same thing-ish for matrix items.
		$this->matrixProductFeed($channel, $productsWriter);
		// lastly do the same for dynamic group items.
		$this->dynamicGroupProductFeed($channel, $productsWriter);

		$this->endXML($productsWriter);

		return $this->fileName;
	}

	private function groupAndStandardProductFeed($channel, $productsWriter)
	{
		$productCollection = \Shop\Models\Products::collection();
		$query = [
			'policies.group_only' => ['$ne' => 1],
			'product_type' => [
				'$in' => [
					'group',
					'standard',
					''
				]
			],
			'$or' => [
				['google_image' => ['$exists' => true]],
				['featured_image.slug' => ['$exists' => true]]
			],
			'publication.sales_channels.slug' => $channel->get('slug'),
			'publication.status'    => 'published'
		];
		$products = $productCollection->find($query, [
			'sort' => ['tracking.model_number' => 1],
			'batchSize' => 50,
			'noCursorTimeout' => true
		]);

		$this->mate->yellow("Standard products.");
		$progress = $this->mate->progress()->total($productCollection->count($query));

		foreach ($products as $data) {
			try {
				$progress->advance(0, $data['title']);
				/** @var \JBAShop\Models\Products $product */
				$product = (new \JBAShop\Models\Products())->bind($data);
				$additionalItemInfo = [];

				if (!empty($product->specs)) { //if the item has specs then we will try and attach apparel data.
					$specs =  array_map('strtolower', $product->specs); //lowercase all attributes stored in specs.
					$additionalItemInfo = array_filter([
						'g:gender' => $specs['Gender'] ?? 'unisex', //set gender, if no spec is set we default to unisex.
						'g:color' => $specs['Color'] ?? null, //if this isn't set then that's on data team I guess.
						'g:age_group' => $specs['Age Group'] ?? 'adult', //set to adult if no age group set.
						'g:size' => in_array($specs['Size'], ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl']) ? $specs['Size'] : null, //once again if this isn't set it's on the data team.
					]); // remove any empty values from array.
				}

				$googleProductCategory = $product->get('gm_product_category');

				if ($product->get('product_type') === 'group') {
					$additionalItemInfo['g:is_bundle'] = 'yes';
				}

				$modelNumber = $product->get('tracking.model_number');
				$gtin = $product->get('tracking.upc');
				$mpn = $product->get('tracking.oem_model_number');
				$title = $product->get('title');
				$link = 'https://' . $channel->get('domain') . $product->generateCanonicalURL(false);

				if (strtolower($product->get('manufacturer.title')) == 'cobb tuning') {
					$price = $product->price(null, null, null, 'map');
				} else {
					$price = $product->price();
				}

				$stock = ($product->inventory() > 0 || $product->getDropshipItem()) ? 'in stock' : 'out of stock'; //if the inventory is in stock or if it's a dropship only item
				$gidentifier_exists = 'yes';

				if (empty(trim($gtin)) || !$this->isValidBarcode($gtin)) {
					//If there is not an identifier don't include the value in the array
					$gidentifier_exists = null;
					$gtin = null;
				}

				//if has flat_shipping fee add shipping data;
				if ($product->get('shipping.is_large_freight_item')) {
					$additionalItemInfo = array_merge(['g:shipping' => [
						'g:price' => number_format((float) $product->getHandlingFee(), 2, '.',  ''), //google doesn't want the ,'s
					]], $additionalItemInfo);
				}

				//merge all non null values
				$item = array_merge(array_filter([
					'title'                     => $title,
					'link'                      => $link,
					'description'               => preg_replace('/(\s)+/', ' ', strip_tags($product->get('description'))),
					'g:id'                      => $modelNumber,
					'g:identifier_exists'       => $gidentifier_exists,
					'g:gtin'                    => $gtin,
					'g:mpn'                     => $mpn,
					'g:price'                   => $price,
					'g:image_link'              =>  \cloudinary_url(!empty($product->get('google_image')) ? $product->get('google_image') : $product->get('featured_image.slug'), [
						'type' => !empty($product->get('google_image')) ? 'upload' : 'private',
						'fetch_format' => 'auto',
						'sign_url' => true,
						'secure' => true,
						'transformation' => \Base::instance()->get('cloudinary.category')
					]),
					'g:manufacturer'            => $product->get('manufacturer.title'), //See line 136
					'g:brand'                   => $product->get('manufacturer.title'),
					'g:condition'               => 'new',
					'g:availability'            => $stock,
					'g:google_product_category' => $googleProductCategory, //According to David "Looks like we are populating the category with the above data for all parts/feeds." RSD has something similar to this so we will utilize that functionality.
					'g:product_type'            => $googleProductCategory, //From the sample xml provided by David data this value falls under the same rules as above.
					'g:shipping_weight'         => !empty($product->get('shipping.weight')) ? $product->get('shipping.weight') : 1
				]), $additionalItemInfo);

				if ($item['g:price'] == 0) {
					$this->mate->red("Price is zero: {$modelNumber}");
					continue;
				}

				$this->addItem($productsWriter, $item);
				$progress->advance(1, $data['title']);
			} catch (\Exception $e) {
				//do nothing
			}
		}
	}

	private function matrixProductFeed($channel, $productsWriter)
	{
		/** @var \Shop\Models\Products $product */
		$productCollection = \Shop\Models\Products::collection();
		$query = [
			'policies.group_only' => ['$ne' => 1],
			'product_type' => 'matrix',
			'$or' => [
				['google_image' => ['$exists' => true]],
				['featured_image.slug' => ['$exists' => true]]
			],
			'publication.sales_channels.slug' => $channel->get('slug'),
			'publication.status'    => 'published'
		];
		$productsCursor = $productCollection->find($query, [
			'sort' => ['tracking.model_number' => 1],
			'batchSize' => 50,
			'noCursorTimeout' => true
		]);
		$progress = $this->mate->progress()->total($productCollection->count($query));
		$this->mate->cyan("Matrix products.");

		//for each matrix 
		foreach ($productsCursor as $data) {
			try {
				$progress->advance(0, $data['title']);
				//get the matrix product model
				$matrix = (new \Shop\Models\Products)->bind($data);
				//loop through variants and build feed items.
				foreach ($matrix->sortVariantsByPrice() as $variant) {
					//Find matrix_subitems from variant.
					/** @var \JBAShop\Models\Products $product */
					$product = (new \JBAShop\Models\Products())
						->setCondition('tracking.model_number', $variant['model_number'])
						->setCondition('publication.status', 'published')
						->setCondition('publication.sales_channels.slug', \Base::instance()->get('sales_channel'))
						->setCondition('product_type', 'matrix_subitem')
						->getItem();
					//if the product was not found continue
					if (empty($product)) {
						continue;
					}

					$additionalItemInfo = [];

					if (!empty($product->specs)) { //if the item has specs then we will try and attach apparel data.
						$specs =  array_map('strtolower', $product->specs); //lowercase all attributes stored in specs.
						$additionalItemInfo = array_filter([
							'g:gender' => $specs['Gender'] ?? 'unisex', //set gender, if no spec is set we default to unisex.
							'g:color' => $specs['Color'] ?? null, //if this isn't set then that's on data team I guess.
							'g:age_group' => $specs['Age Group'] ?? 'adult', //set to adult if no age group set.
							'g:size' => in_array($specs['Size'], ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl']) ? $specs['Size'] : null, //once again if this isn't set it's on the data team.
						]); // remove any empty values from array.
					}

					$googleProductCategory = $product->get('gm_product_category');
					$additionalItemInfo['g:item_group_id'] = $matrix->get('tracking.model_number');
					$modelNumber = $product->get('tracking.model_number');
					$gtin = $product->get('tracking.upc');
					$mpn = $product->get('tracking.oem_model_number');
					$title = $product->get('title');
					$link = "https://{$channel->get('domain')}/part/{$matrix->get('slug')}";

					if (strtolower($product->get('manufacturer.title')) == 'cobb tuning') {
						$price = $product->price(null, null, null, 'map');
					} else {
						$price = $product->price();
					}

					$stock = ($product->inventory() > 0 || $product->getDropshipItem()) ? 'in stock' : 'out of stock'; //if the inventory is in stock or if it's a dropship only item
					$gidentifier_exists = 'yes';

					if (empty(trim($gtin)) || !$this->isValidBarcode($gtin)) {
						//If there is not an identifier don't include the value in the array
						$gidentifier_exists = null;
						$gtin = null;
					}

					//if has flat_shipping fee add shipping data;
					if ($product->get('shipping.is_large_freight_item')) {
						$additionalItemInfo = array_merge(['g:shipping' => [
							'g:price' => number_format((float) $product->getHandlingFee(), 2, '.',  ''), //google doesn't want the ,'s
						]], $additionalItemInfo);
					}

					//check the product for any image we can use for feeds
					if (!empty($product->get('google_image'))) {
						$imgUrl = $product->get('google_image');
						$imgType = 'upload';
					} else {
						$imgUrl = $product->get('featured_image.slug');
						$imgType = 'private';
					}

					//if no child has an img use the matrix parent image.
					if (empty($imgUrl) && !empty($matrix->get('google_image'))) {
						$imgUrl =  $matrix->get('google_image');
						$imgType = 'upload';
					} else if(empty($imgUrl)) {
						$imgUrl =  $matrix->get('featured_image.slug');
						$imgType = 'private';
					}

					if(empty($imgUrl)) {
						$this->mate->red("No Image: {$modelNumber}");
						continue;
					}

					//merge all non null values
					$item = array_merge(array_filter([
						'title'                     => $title,
						'link'                      => $link,
						'description'               => preg_replace('/(\s)+/', ' ', strip_tags($product->get('description'))),
						'g:id'                      => $modelNumber,
						'g:identifier_exists'       => $gidentifier_exists,
						'g:gtin'                    => $gtin,
						'g:mpn'                     => $mpn,
						'g:price'                   => $price,
						'g:image_link'              =>  \cloudinary_url($imgUrl, [
							'type' => $imgType,
							'fetch_format' => 'auto',
							'sign_url' => true,
							'secure' => true,
							'transformation' => \Base::instance()->get('cloudinary.category')
						]),
						'g:manufacturer'            => $product->get('manufacturer.title'), //See line 136
						'g:brand'                   => $product->get('manufacturer.title'),
						'g:condition'               => 'new',
						'g:availability'            => $stock,
						'g:google_product_category' => $googleProductCategory, //According to David "Looks like we are populating the category with the above data for all parts/feeds." RSD has something similar to this so we will utilize that functionality.
						'g:product_type'            => $googleProductCategory, //From the sample xml provided by David data this value falls under the same rules as above.
						'g:shipping_weight'         => !empty($product->get('shipping.weight')) ? $product->get('shipping.weight') : 1
					]), $additionalItemInfo);

					if ($item['g:price'] == 0) {
						$this->mate->red("Price is zero: {$modelNumber}");
						continue;
					}

					$this->addItem($productsWriter, $item);
				}

				$progress->advance(1, $data['title']);
			} catch (\Exception $e) {
				//do nothing
			}
		}
		$this->mate->backgroundLightGreen()->black("    DONE    ");
	}
	
	private function dynamicGroupProductFeed($channel, $productsWriter)
	{
		/** @var \JBAShop\Models\Products $product */
		$productCollection = \JBAShop\Models\Products::collection();
		$query = [
			'product_type' => 'dynamic_group',
			'$or' => [
				['google_image' => ['$exists' => true]],
				['featured_image.slug' => ['$exists' => true]]
			],
			'publication.sales_channels.slug' => $channel->get('slug'),
			'publication.status'    => 'published'
		];
		$productsCursor = $productCollection->find($query, [
			'sort' => ['tracking.model_number' => 1],
			'batchSize' => 50,
			'noCursorTimeout' => true
		]);
		$progress = $this->mate->progress()->total($productCollection->count($query));
		$this->mate->magenta("Dynamic group products.");

		//for each matrix 
		foreach ($productsCursor as $data) {
			try {
				$progress->advance(0, $data['title']);
				//get the matrix product model
				$dynamicGroup = (new \JBAShop\Models\Products())->bind($data);
				//loop through variants and build feed items.
				foreach (\Dsc\ArrayHelper::getColumn($dynamicGroup->get('kit_options'), 'values') as $kitOptions) {
					foreach ($kitOptions as $kitOption) {
						//Find matrix_subitems from variant.
						/** @var \JBAShop\Models\Products $product */
						$product = (new \JBAShop\Models\Products())
							->setCondition('tracking.model_number', $kitOption['model_number'])
							->setCondition('publication.status', 'published')
							->setCondition('publication.sales_channels.slug', \Base::instance()->get('sales_channel'))
							->setCondition('policies.group_only', ['$in' => [1, '1']])
							->getItem();
						//if the product was not found continue
						if (empty($product)) {
							continue;
						}

						$additionalItemInfo = [];

						if (!empty($product->specs)) { //if the item has specs then we will try and attach apparel data.
							$specs =  array_map('strtolower', $product->specs); //lowercase all attributes stored in specs.
							$additionalItemInfo = array_filter([
								'g:gender' => $specs['Gender'] ?? 'unisex', //set gender, if no spec is set we default to unisex.
								'g:color' => $specs['Color'] ?? null, //if this isn't set then that's on data team I guess.
								'g:age_group' => $specs['Age Group'] ?? 'adult', //set to adult if no age group set.
								'g:size' => in_array($specs['Size'], ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl']) ? $specs['Size'] : null, //once again if this isn't set it's on the data team.
							]); // remove any empty values from array.
						}

						$googleProductCategory = $product->get('gm_product_category');
						$additionalItemInfo['g:item_group_id'] = $dynamicGroup->get('tracking.model_number');
						$modelNumber = $product->get('tracking.model_number');
						$gtin = $product->get('tracking.upc');
						$mpn = $product->get('tracking.oem_model_number');
						$title = $product->get('title');
						$link = 'https://' . $channel->get('domain') . $dynamicGroup->generateCanonicalURL(false);

						if (strtolower($product->get('manufacturer.title')) == 'cobb tuning') {
							$price = $product->price(null, null, null, 'map');
						} else {
							$price = $product->price();
						}

						$stock = ($product->inventory() > 0 || $product->getDropshipItem()) ? 'in stock' : 'out of stock'; //if the inventory is in stock or if it's a dropship only item
						$gidentifier_exists = 'yes';

						if (empty(trim($gtin)) || !$this->isValidBarcode($gtin)) {
							//If there is not an identifier don't include the value in the array
							$gidentifier_exists = null;
							$gtin = null;
						}

						//if has flat_shipping fee add shipping data;
						if ($product->get('shipping.is_large_freight_item')) {
							$additionalItemInfo = array_merge(['g:shipping' => [
								'g:price' => number_format((float) $product->getHandlingFee(), 2, '.',  ''), //google doesn't want the ,'s
							]], $additionalItemInfo);
						}

						//check the product for any image we can use for feeds
						if (!empty($product->get('google_image'))) {
							$imgUrl = $product->get('google_image');
							$imgType = 'upload';
						} else {
							$imgUrl = $product->get('featured_image.slug');
							$imgType = 'private';
						}

						//if no child has an img use the matrix parent image.
						if (empty($imgUrl) && !empty($dynamicGroup->get('google_image'))) {
							$imgUrl =  $dynamicGroup->get('google_image');
							$imgType = 'upload';
						} else if(empty($imgUrl)) {
							$imgUrl =  $dynamicGroup->get('featured_image.slug');
							$imgType = 'private';
						}

						//merge all non null values
						$item = array_merge(array_filter([
							'title'                     => $title,
							'link'                      => $link,
							'description'               => preg_replace('/(\s)+/', ' ', strip_tags($product->get('description'))),
							'g:id'                      => $modelNumber,
							'g:identifier_exists'       => $gidentifier_exists,
							'g:gtin'                    => $gtin,
							'g:mpn'                     => $mpn,
							'g:price'                   => $price,
							'g:image_link'              =>  \cloudinary_url($imgUrl, [
								'type' => $imgType,
								'fetch_format' => 'auto',
								'sign_url' => true,
								'secure' => true,
								'transformation' => \Base::instance()->get('cloudinary.category')
							]),
							'g:manufacturer'            => $product->get('manufacturer.title'), //See line 136
							'g:brand'                   => $product->get('manufacturer.title'),
							'g:condition'               => 'new',
							'g:availability'            => $stock,
							'g:google_product_category' => $googleProductCategory, //According to David "Looks like we are populating the category with the above data for all parts/feeds." RSD has something similar to this so we will utilize that functionality.
							'g:product_type'            => $googleProductCategory, //From the sample xml provided by David data this value falls under the same rules as above.
							'g:shipping_weight'         => !empty($product->get('shipping.weight')) ? $product->get('shipping.weight') : 1
						]), $additionalItemInfo);

						if ($item['g:price'] == 0) {
							$this->mate->red("Price is zero: {$modelNumber}");
							continue;
						}

						$this->addItem($productsWriter, $item);
					}
				}

				$progress->advance(1, $data['title']);
			} catch (\Exception $e) {
				//do nothing
			}
		}
		$this->mate->backgroundLightGreen()->black("    DONE    ");
	}

	function isValidBarcode($barcode)
	{
		//checks validity of: GTIN-8, GTIN-12, GTIN-13, GTIN-14, GSIN, SSCC
		//see: http://www.gs1.org/how-calculate-check-digit-manually
		$barcode = (string) $barcode;
		//we accept only digits
		if (!preg_match("/^[0-9]+$/", $barcode)) {
			return false;
		}
		//check valid lengths:
		$l = strlen($barcode);
		if (!in_array($l, [8, 12, 13, 14, 17, 18])) {
			return false;
		}
		if ($l === 8) { //if the length is 8 convert to 12 digit
			$barcode = $this->convertUpc8To12($barcode);
		}
		//get check digit
		$check = substr($barcode, -1);
		$barcode = substr($barcode, 0, -1);
		$sum_even = $sum_odd = 0;
		$even = true;
		while (strlen($barcode) > 0) {
			$digit = substr($barcode, -1);
			if ($even)
				$sum_even += 3 * $digit;
			else
				$sum_odd += $digit;
			$even = !$even;
			$barcode = substr($barcode, 0, -1);
		}
		$sum = $sum_even + $sum_odd;
		$sum_rounded_up = ceil($sum / 10) * 10;
		return ($check == ($sum_rounded_up - $sum));
	}

	/**
	 * @param $name
	 * @return \XMLWriter
	 */
	protected function startXML($name)
	{
		//set the $fileName so we can return the full path to file.
		$this->fileName = $this->path . "{$name}.xml";
		$writer = new \XMLWriter();
		$writer->openUri($this->fileName);
		$writer->setIndent(true);
		$writer->startDocument('1.0', 'UTF-8');
		$writer->startElement('rss');
		$writer->writeAttribute('version', '2.0');
		$writer->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
		$writer->startElement('channel');

		return $writer;
	}

	/**
	 * @param \XMLWriter $writer
	 */
	protected function endXML(\XMLWriter $writer)
	{
		$writer->endElement();
		$writer->endDocument();
		$writer->flush();
	}

	/**
	 * @param \XMLWriter $writer
	 * @param array $array
	 */
	protected function addItem(\XMLWriter $writer, array $array, $elementName = 'item')
	{
		$writer->startElement($elementName);
		foreach ($array as $k => $v) {
			if (in_array($k, $this->cleanKeys)) {
				$v = $this->cleanString($v);
				$writer->writeElement($k, $v);
			} else if (is_array($v)) {
				$this->addItem($writer, $v, $k);
			} else {
				$writer->writeElement($k, $v);
			}
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

	/**
	 * @param $upc
	 * @return string
	 */
	private function convertUpc8To12($upc)
	{
		$mid6 = str_split(substr($upc, 1, -1)); //get the inner digits
		if (in_array($mid6[5], ["0", "1", "2"])) { //if the last digit is in (0,1,2)
			$mfrnum = $mid6[0] . $mid6[1] . $mid6[5] . "00";
			$itemnum = "00" . $mid6[2] . $mid6[3] . $mid6[4];
		} else if ($mid6[5] === '3') { //if the last digit is 3
			$mfrnum = $mid6[0] . $mid6[1] . $mid6[2] . "00";
			$itemnum = "000" . $mid6[3] . $mid6[4];
		} elseif ($mid6[5] === '4') { //if the last digit is 4
			$mfrnum = $mid6[0] . $mid6[1] . $mid6[2]  . $mid6[3] . "0";
			$itemnum = "0000" . $mid6[4];
		} else { //All other values fall under this same scenerio
			$mfrnum = $mid6[0] . $mid6[1] . $mid6[2] . $mid6[3] . $mid6[4];
			$itemnum = "0000" . $mid6[5];
		}
		return substr($upc, 0, 1) . $mfrnum . $itemnum . substr($upc, -1); // now glue it all together with the first and last digits from the 8 digit upc.
	}
}
