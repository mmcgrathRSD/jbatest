<?php

namespace JBAShop\Models;

class YearMakeModels extends \Shop\Models\YearMakeModels
{

	use \Dsc\Traits\Models\Publishable;
	use \Dsc\Traits\Models\Images;
	protected $__collection_name = 'shop.yearmakemodels';
	protected $__type = 'shop.yearmakemodels';

	protected $__translatable = false;
	
	var $vehicle_year = null;
	var $vehicle_make = null;
	var $vehicle_model = null;
	var $vehicle_sub_model = null;
	var $vehicle_engine_size = null;


	protected function fetchConditions()
	{
		parent::fetchConditions();




		$filter_year = $this->getState('filter.vehicle_year');
		if(strlen($filter_year)) {
			$this->setCondition('vehicle_year', $filter_year);
		}

		$filter_vehicle_model = $this->getState('filter.vehicle_model');
		if(strlen($filter_vehicle_model)) {
			$this->setCondition('vehicle_model', $filter_vehicle_model);
		}

		$filter_vehicle_make = $this->getState('filter.vehicle_make');
		if(strlen($filter_vehicle_make)) {
			$this->setCondition('vehicle_make', $filter_vehicle_make);
		} else {
			$this->unsetCondition('vehicle_model');
		}

        $filter_vehicle_sub_model = $this->getState('filter.vehicle_sub_model');
        if(strlen($filter_vehicle_sub_model)) {
            $this->setCondition('vehicle_sub_model', $filter_vehicle_sub_model);
        }
        
        $filter_vehicle_engine_size = $this->getState('filter.vehicle_engine_size');
        if(strlen($filter_vehicle_engine_size)) {
            $this->setCondition('vehicle_engine_size', $filter_vehicle_engine_size);
        }
        
        $filter_in_slugs = $this->getState('filter.in_slugs');
        if(count((array)$filter_in_slugs)) {
            $this->setCondition('slug', array( '$in' => (array)$filter_in_slugs ));
        }

		$this->imagesFetchConditions();
		$this->publishableFetchConditions();

		return $this;
	}

	/*
	 * Returns product by yym
	 */
	public static function getProducts($id) {

	}


	public static function reMapAllProductstoYmms() {

	}

	protected function beforeValidate() {

		if(empty($this->title)) {
			$title = $this->vehicle_year .' '. $this->vehicle_make . ' ' . $this->vehicle_model .' '.  $this->vehicle_sub_model . ' ' . $this->vehicle_engine_size ;
			$this->title = $title;

			$this->slug = $this->generateSlug();
		}


	}

	/**
	 * Helper method for creating select list options
	 *
	 * @param array $query
	 * @return multitype:multitype:string NULL
	 */
	public static function forSelection(array $query=array(), $id_field='_id', $text_field = 'title' )
	{
		$model = new static;

        $cursor = $model->collection()->find($query, [
            'projection' => [
                $text_field => 1,
                $id_field => 1
            ],
            'sort' => [$text_field => 1]
        ]);

		$result = array();
		foreach ($cursor as $doc) {
			$array = array(
					'id' => (string) $doc[$id_field],
					'text' => htmlspecialchars( trim( $doc[$text_field]), ENT_QUOTES ),
			);
			$result[] = $array;
		}

		return $result;
	}
    
    public static function forSelectionDistinct($field, array $query=array(), $sorted = true )
    {
        $data = \JBAShop\Models\YearMakeModels::distinctValues( $field, $sorted, $query);

        $result = array();
        foreach ($data as $item) {
            $array = array(
                    'id' => $item,
                    'text' => $item,
            );
            $result[] = $array;
        }

        return $result;
    }
    
    
    public static function distinctValues( $field, $sorted = true, array $query = array()){
        $data = (new static)->collection()->distinct($field, $query);
        array_walk($data, function(&$item, $key) {
            $item = htmlspecialchars( trim( $item ), ENT_QUOTES );
        });
        
        if( $sorted ){
            sort($data);
        }
        return $data;
    }


	protected function beforeSave()
	{



	}

	/*
	 * SETS PUBLISHED BASED OF PRODUCTS, Should be Run by the Queue as it will probably time out
	 */
	public static function setPublished() {

		$model = new static;

		$ymms = $model->collection()->find();

		foreach ($ymms as $ymm) {

			$ymm = (new static)->bind($ymm);
			$products = new \JBAShop\Models\Products;
			$count = $products->setCondition('ymms.slug', $ymm->slug)->getCount();
			$ymm->set('products_count', $count);
			if($count > 0) {
				$ymm->set('publication.status', 'published');
			} else {
				$ymm->set('publication.status', 'unpublished');
			}

			$ymm->save();
		}

	}

	public static function reMapProductYmms($product_id) {


	}

	public static function getMakes($year) {

		$model = new static;

		$result = $model->collection()->distinct('vehicle_make', array('vehicle_year' =>(string)$year, 'publication.status' => 'published' ));

		//sort the results
		sort($result);
		return $result;

	}

	public static function getModels($year, $make) {
		$model = new static;


		$result = $model->collection()->distinct('vehicle_model', array('vehicle_year' =>(string)$year, 'vehicle_make' =>(string) $make , 'publication.status' => 'published'));

		//sort the results
		sort($result);
		return $result;

	}


	public static function getSubModels($year, $make, $vehiclemodel) {

		$model = new static;


		$cursor = $model->collection()->find(array(
		    'vehicle_year' => (string) $year,
            'vehicle_make' => (string) $make,
            'vehicle_model' => (string) $vehiclemodel,
            'publication.status' => 'published'
        ), [
            'projection' => [
                'vehicle_sub_model' => true,
                'vehicle_engine_size' => true,
                '_id' => false
            ]
        ]);
		
		$docs = [];
		$sub_models = [];
		foreach ($cursor as $doc) {
			$docs[] = $doc;	
			$sub_models[] =  $doc['vehicle_sub_model'];
		}
		
		$dups = array_count_values($sub_models);
		$result = [];
		foreach($docs as $doc) {
			if($dups[$doc['vehicle_sub_model']] > 1) {
			$result[] = $doc['vehicle_sub_model'] . ' | '.$doc['vehicle_engine_size'];
			} else {
				$result[] = $doc['vehicle_sub_model'];
			}
		}
		
	
		
		//sort the results
		sort($result);
		return $result;

	}


	public static function getEngines($year, $make, $vehiclemodel, $sub) {

		$model = new static;


		$result = $model->collection()->distinct('vehicle_engine_size', array('vehicle_year' =>(string)$year, 'vehicle_make' =>(string) $make, 'vehicle_model' =>(string) $vehiclemodel, 'vehicle_sub_model' => (string)$sub , 'publication.status' => 'published'));

		//sort the results
		sort($result);
		return $result;

	}
	
	/*
	 * DELETE THIS WHEN CRAP WHEN TRANSLATING STOPS BREAKING IT
	 *
	 */
	public function getItems($refresh=false)
	{
		if (is_null($this->getState('list.sort')))
		{
			if (!empty($this->getParam('sort'))) {
				$this->setState('list.sort', $this->getParam('sort'));
			} else {
				$this->setState('list.sort', $this->__config['default_sort']);
			}
		}
		$this->setParam('sort', $this->getState('list.sort'));
	
		if ($this->getState('list.limit'))
		{
			$this->setParam('limit', $this->getState('list.limit'));
		}
	
		// TODO Store the state
		// TODO Implement caching
		return $this->fetchItems();
	}




}