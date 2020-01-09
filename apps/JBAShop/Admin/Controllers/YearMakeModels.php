<?php
namespace RallyShop\Admin\Controllers;

class YearMakeModels extends \Shop\Admin\Controllers\YearMakeModels
{
    public function filterYmmAjax()
    {
        $ymmYear = trim($this->inputfilter->clean( $this->app->get('POST.year'), 'int'));
        $ymmMake = trim($this->inputfilter->clean( $this->app->get('POST.make'), 'string'));
        $ymmModel = trim($this->inputfilter->clean( $this->app->get('POST.model'), 'string'));
        $ymmSubModel = trim($this->inputfilter->clean( $this->app->get('POST.subModel'), 'string'));
        $ymmEngineSize = trim($this->inputfilter->clean( $this->app->get('POST.engine'), 'string'));
        
        if (empty($ymmYear) && empty($ymmMake) && empty($ymmModel) && empty($ymmSubModel) && empty($ymmEngineSize)) {
            return $this->outputJson(array());
        }
        
        $model = $this->getModel();
        $model
            ->setState('filter.vehicle_year', $ymmYear)
            ->setState('filter.vehicle_make', $ymmMake)
            ->setState('filter.vehicle_model', $ymmModel)
            ->setState('filter.vehicle_sub_model', $ymmSubModel)
            ->setState('filter.vehicle_engine_size', $ymmEngineSize);
        
        $items = $model->getItems();
        $response = [];
        if (count((array) $items )) {
            foreach( $items as $item ) {
                $response [] = [
                    'slug'     => $item['slug'],
                    'year'     => $item['vehicle_year'],
                    'make'     => $item['vehicle_make'],
                    'model'    => $item['vehicle_model'],
                    'subModel' => $item['vehicle_sub_model'],
                    'engine'   => $item['vehicle_engine_size']
                ];
            }
        }
        
        return $this->outputJson($response);       
    }
}