<?php 
namespace RallyShop\Models;

class Images 
{
    
	
	public  static function product_image($id, array $options = array()) {
		$options['transformation'] = 'nw_product';
	
		return cloudinary_url($id, $options); 
		
	} 
	
	public  static function product_thumb($id,  array $options = array()) {
		$options['transformation'] = 'rsd_thumb';
	
		echo cloudinary_url($id, $options);
	}
	
	
	
	public static function getImagesByTag($tag) {
		$api = new \Cloudinary\Api();
		
		$results = $api->resources_by_tag($tag);
		
		
		return $results;
		
		
	}
	
	public static function getImagesForProduct($tag) {
		
		$part_number = str_replace('_', ' ', $tag);
		
		echo $part_number;
		$product = (new \RallyShop\Models\Products)->setCondition('variants.model_number', $part_number)->getItem();
		
		
		$results = static::getImagesByTag($tag);
		
		$cloudImages = [];
		foreach($results['resources'] as $image) {
			
			$cloudImages[$image['public_id']] = ['slug' => $image['public_id'], 'version' => $image['version'] ];
		}
	
		
		asort($cloudImages);
		
		$product->set('cloudtest', $cloudImages)->save();
		
		
	}
	
	
	
	
	
	
}