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

	public function __construct($path) {
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
	
	public function generateFeeds($name = 'products')
	{
		$mate = new CLImate();
		/** @var \MongoDb $mongo */
		$mongo = \Dsc\System::instance()->get('mongo');
	
		/** @var \MongoCollection $collection */
		$categoryCollection = \Dsc\Mongo\Helper::getCollection('common.categories');
		/** @var \MongoCollection $collection */
		$productCollection = \Dsc\Mongo\Helper::getCollection('shop.products');
		//restrict categories to sales_channel/global
		$categoriesCursor = $categoryCollection->find([
			'gm_product_category' => [
				'$exists' => true,
				'$ne'     => ''
			],
			'$or' => [
				['sales_channels.0' => ['$exists' => false]],
				['sales_channels.slug' => \Base::instance()->get('sales_channel')]
			]
		]);
		//we do this to stop the category cursor from timing out
		$categories = [];
		foreach ($categoriesCursor as $categoryDoc)  {
		    $categories[] = $categoryDoc;
		}
		$productIds = [];
		$productsWriter = $this->startXML($name);
		$progress = $mate->progress()->total(count($categories));

		//for each category collect all products and generate xml feed entries
		foreach ($categories as $category) {
			$progress->advance(0, $category['title']);
			$googleProductCategory = $category['gm_product_category'];
			$query = [
			    'publication.sales_channels.slug' => \Base::instance()->get('sales_channel'),
				'categories.0.id'    => new \MongoDB\BSON\ObjectID((string) $category['_id'])
			];

			$products = $productCollection->find($query);
			foreach ($products as $data) {
				/** @var \JBAShop\Models\Products $product */
				$product = (new \JBAShop\Models\Products())->bind($data);
				$additionalItemInfo = [];

				if(!empty($product->specs)){//if the item has specs then we will try and attach apparel data.
					$specs =  array_map('strtolower', $product->specs);//lowercase all attributes stored in specs.
					$additionalItemInfo = array_filter([
						'g:gender' => $specs['Gender'] ?? 'unisex',//set gender, if no spec is set we default to unisex.
						'g:color' => $specs['Color'],//if this isn't set then that's on data team I guess.
						'g:age_group' => $specs['Age Group'] ?? 'adult',//set to adult if no age group set.
						'g:size' => in_array($specs['Size'], ['xxs', 'xs', 's', 'm', 'l', 'xl', 'xxl', 'xxxl']) ? $specs['Size'] : null,//once again if this isn't set it's on the data team.
					]);// remove any empty values from array.
				}

				$productId = (string) $product->id;

				if (in_array($productId, $productIds)) {
					continue;
				}

				if($product->get('product_type') === 'group'){
					$additionalItemInfo['g:group_item'] = 'yes';
				}

				$productIds[] = $productId;
				$modelNumber = $product->get('tracking.model_number');
				$gtin = $product->get('tracking.upc');
				$mpn = trim(substr($modelNumber, strpos($modelNumber, ' ')));
				$title = $product->title;
				$link = $product->generateStandardURL(true);

				if(strtolower($product->get('manufacturer.title')) == 'cobb tuning') {
				    $price = $product->price(null, null, null, 'map');
				} else {
				    $price = $product->price();
				}
				
				$stock = ($product->inventory() > 0 || $product->getDropshipItem()) ? 'in stock' : 'out of stock';//if the inventory is in stock or if it's a dropship only item
				$gidentifier_exists = 'yes';
				if(empty(trim($gtin)) || !$this->isValidBarcode($gtin)) {
				    $gidentifier_exists = 'no';
                    $gtin = null;
				}
				
				$item = array_merge([
					'title'                     => $title,
					'link'                      => $link,
					'description'               => preg_replace('/(\s)+/', ' ', $product->get('short_description')),
					'g:id'                      => $modelNumber,
				    'g:identifier_exists'       => $gidentifier_exists,
				    'g:gtin'                    => $gtin,
					'g:mpn'                     => $mpn, //TODO: Get with David to see why they don't send this info
					'g:price'                   => $price,
					'g:image_link'              => $product->productFeedsImage(),
					'g:manufacturer'            => $product->get('manufacturer.title'),//See line 136
					'g:brand'                   => $product->get('manufacturer.title'),
					'g:condition'               => 'new',
					'g:availability'            => $stock,
					'g:google_product_category' => $googleProductCategory,//According to David "Looks like we are populating the category with the above data for all parts/feeds." RSD has something similar to this so we will utilize that functionality. 
					'g:product_type'            => $googleProductCategory,//From the sample xml provided by David data this value falls under the same rules as above.
					'g:shipping_weight'         => !empty($product->get('shipping.weight')) ? $product->get('shipping.weight') : 1
				], $additionalItemInfo);

				if ($item['g:price'] == 0) {
					continue;
				}

				$this->addItem($productsWriter, $item);
			}
			$progress->advance(1);
		}

		$this->endXML($productsWriter);

		return $this->fileName;
	}

	function isValidBarcode($barcode) {
        //checks validity of: GTIN-8, GTIN-12, GTIN-13, GTIN-14, GSIN, SSCC
        //see: http://www.gs1.org/how-calculate-check-digit-manually
        $barcode = (string) $barcode;
        //we accept only digits
        if (!preg_match("/^[0-9]+$/", $barcode)) {
            return false;
        }
        //check valid lengths:
        $l = strlen($barcode);
        if(!in_array($l, [8,12,13,14,17,18])){
        	return false;	
        }
        if($l === 8){//if the length is 8 convert to 12 digit
			$barcode = $this->convertUpc8To12($barcode);
        }
        //get check digit
        $check = substr($barcode, -1);
        $barcode = substr($barcode, 0, -1);
        $sum_even = $sum_odd = 0;
        $even = true;
        while(strlen($barcode)>0) {
            $digit = substr($barcode, -1);
            if($even)
                $sum_even += 3 * $digit;
            else 
                $sum_odd += $digit;
            $even = !$even;
            $barcode = substr($barcode, 0, -1);
        }
        $sum = $sum_even + $sum_odd;
        $sum_rounded_up = ceil($sum/10) * 10;
        return ($check == ($sum_rounded_up - $sum));
    }
	
	/**
	 * @param $name
	 * @return \XMLWriter
	 */
	protected function startXML($name)
	{
		//set the $fileName so we can return the full path to file.
		$this->fileName = $this->path."{$name}.xml";
		$writer = new \XMLWriter();
		$writer->openUri($this->fileName);
		$writer->setIndent(true);
		$writer->startDocument('1.0','UTF-8');
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
	protected function addItem(\XMLWriter $writer, array $array)
	{
		$writer->startElement('item');
		foreach ($array as $k => $v) {
			if (in_array($k, $this->cleanKeys)) {
				$v = $this->cleanString($v);
			}
			$writer->writeElement($k, $v);
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
	private function convertUpc8To12($upc){
		$mid6 = str_split(substr($upc, 1, -1));//get the inner digits
    	if(in_array($mid6[5], ["0","1","2"])){//if the last digit is in (0,1,2)
		    $mfrnum = $mid6[0] . $mid6[1] . $mid6[5] . "00";
		    $itemnum = "00" . $mid6[2] . $mid6[3] . $mid6[4];
		}else if($mid6[5] === '3'){//if the last digit is 3
		    $mfrnum = $mid6[0] . $mid6[1] . $mid6[2] . "00";
		    $itemnum = "000" . $mid6[3] . $mid6[4];
		}elseif($mid6[5] === '4'){//if the last digit is 4
		    $mfrnum = $mid6[0] . $mid6[1] . $mid6[2]  . $mid6[3] . "0";
		    $itemnum = "0000" . $mid6[4];
		}else{//All other values fall under this same scenerio
		    $mfrnum = $mid6[0] . $mid6[1] . $mid6[2] . $mid6[3] . $mid6[4];
		    $itemnum = "0000" . $mid6[5];
		}
		return substr($upc, 0, 1) . $mfrnum . $itemnum . substr($upc, -1);// now glue it all together with the first and last digits from the 8 digit upc.
	}
}
