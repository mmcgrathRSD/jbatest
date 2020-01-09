<?php 
namespace JBA\Admin\Controllers;

class Testing extends \Admin\Controllers\BaseAuth 
{

   public function testing() {
   	
  
	try {

		
	//	$product = (new \RallyShop\Models\Products)->setState('filter.slug', 'cobb-lightweight-crank-pulley-blue')->getItem()->updateReviewCounts();
		
	
		$docs = (new \JBAShop\Models\Products)->collection()->find( array('review_rating_counts' => array('$exists' => false) ) );
		 foreach($docs as $doc) {
		 \Dsc\Queue::task('\RallyShop\Models\Products::doUpdateReviewCounts', array('id' => $doc['_id'] ));
		 } 
		
		
		
		
		//$product = (new \RallyShop\Models\Categories)->collection()->distinct('_id',array('product_specs' => array('$size' => 0)));

		//var_dump($product);
		
		/*$backup = \Dsc\Mongo\Helper::getCollection( 'common.categories_copy3' );
		
		$docs = $backup->find(array('_id' => array('$in' => $product), 'product_specs' => array('$not' => array('$size' => 0) )));
		
		foreach ($docs as $doc) {
			$cat = (new \RallyShop\Models\Categories)->setCondition('_id', $doc['_id'])->getItem();
			$cat->set('product_specs', $doc['product_specs']);
			$cat->save();
			set_time_limit ( 20 );
		}*/
		
		//find the categories from the backups 
		
		
		
		

		
		
		
		
		
		
		
		
		/*$docs = (new \Assets\Models\Assets)->collection()->find(array('storage' => 'cloudfiles'))->skip(14741);
		foreach($docs as $doc) {
			\Dsc\Queue::task('\Assets\Models\Storage\CloudFiles::gridfsToCDNThumbs', array('slug' => $doc['slug'] ));
		} */
		
		
		//\RallyShop\Models\Orders::buildShippingPackages('5509dd58814981263c8b4570');
		
		/*$cats = (new \RallyShop\Models\Categories)->collection()->find();
		
		foreach($cats as $doc) {
			\RallyShop\Models\Categories::findImageFromProduct($doc['_id']);
		}
	   */

	//	\Dsc\Queue::task('\RallyShop\Models\Orders::toXml', array('id' => '5509dd58814981263c8b4570' ), array('batch' => 'local'));

			

	} catch (\Exception $e) {
		echo $e->getMessage(); die();
	}
   
   	
   }
   
   public function ymm() {
       $view = \Dsc\System::instance()->get('theme');
       echo $view->renderTheme('JBA/Admin/Views::test/ymm.php');
   }
   
   
}