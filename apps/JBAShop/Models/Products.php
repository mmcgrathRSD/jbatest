<?php
namespace JBAShop\Models;

class Products extends \Shop\Models\Products
{
	public function generateCanonicalURL($absolute = true){
		$path = $absolute ? \Base::instance()->get('SCHEME') . "://" . \Base::instance()->get('HOST') : '';

		return  $path . '/part/' . $this->get('slug');
	}

	public function generateAncestorCategoryUrls($salesChannel){
		//get all the categories from the product
		$productCategories = \Dsc\ArrayHelper::getColumn($this->categories, 'slug');
		$categoryUrls = [];
		//for each product category build the hierarchal category url for the product.
		foreach($productCategories as $key => $productCategory){
			//get hierarchy
			$categoryHierarchyTree = (new \Shop\Models\Categories)->collection()->aggregate([
				[
					'$match' => [
						'slug' => $productCategory,
						'$or' => [
							['sales_channels' => ['exists' => false]],
							['sales_channels.slug' => $salesChannel]
						]
					]
				],
				[
					'$graphLookup' =>[
						'from' => 'common.categories',
						'startWith' => '$parent',
						'connectFromField' => 'parent',
						'connectToField' => '_id',
						'as' => 'ancestor_lookup',
						'maxDepth' => (int)\Base::instance()->get('analytics.max_category_ancestor_depth', 5),
						'depthField' => 'lookup_depth',
						'restrictSearchWithMatch' => [
							'$or' => [
								['sales_channels.0' => ['exists' => false]],
								['sales_channels.slug' => $salesChannel]
							]
						]
					]
				],
				[
					'$project' => [
						"_id" => 0,
						"slug" => 1,
						'lookup_depth' => 1,
						'ancestor_lookup._id' => 1,
						'ancestor_lookup.slug' => 1,
						'ancestor_lookup.lookup_depth' => 1
					]
				],
				[
					'$limit' => 1
				]
			])->toArray();
			//If we have a tree build the url
			if(!empty($categoryHierarchyTree[0])){
				//get the slugs from lookup
				$slugs = \Dsc\ArrayHelper::getColumn($categoryHierarchyTree[0]['ancestor_lookup'], 'slug');
				//push the bottom most category we used to search by to the array.
				array_push($slugs, $productCategory);
				//while we have slugs build up the hierarchal urls and remove the last child after generating.
				while(!empty($slugs)){
					///build url
					$categoryUrls[] = "/part/" . implode('/', $slugs) . "/{$this->slug}";
					//remove the bottom most category.
					array_pop($slugs);
				}
			}
		}
		//return the category urls that we generated based on ancestry.
		return array_unique($categoryUrls);
	}
}

