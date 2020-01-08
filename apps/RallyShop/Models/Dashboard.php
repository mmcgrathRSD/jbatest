<?php
namespace RallyShop\Models;

class Dashboard extends \Shop\Models\Dashboard
{
    public function fetchTotalSales($start=null, $end=null)
    {
        $model = (new \RallyShop\Models\Orders)
            ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
            ->setState('filter.financial_status', array( \Shop\Constants\OrderFinancialStatus::paid, \Shop\Constants\OrderFinancialStatus::authorized ) );
        
        if (!empty($start)) {
        	$model->setState('filter.created_after', $start);
        }
        
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
        
        $conditions = $model->conditions();
    
        $agg = \Shop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                	'_id' => null,
                    'total' => array( '$sum' => '$grand_total' ),
                    'count' => array( '$sum' => 1 )
                )
            )
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
        
        $return = array(
            'total'=>0,
            'count'=>0,
        );
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $return = $agg['result'][0];
        }

        return $return;
    } 
    
    public function fetchTopSellers($start=null, $end=null)
    {
        $model = (new \RallyShop\Models\Orders)
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ->setState('filter.financial_status', array( \Shop\Constants\OrderFinancialStatus::paid, \Shop\Constants\OrderFinancialStatus::authorized ) );
    
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
    
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
    
        $conditions = $model->conditions();
    
        $agg = \RallyShop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$unwind' => '$items'
            ),
            array(
                '$group' => array(
                    '_id' => '$items.product_id',
                    'total' => array( '$sum' => '$items.quantity' ),
                )
            ),
            array(
                '$sort' => array( 'total' => -1 )
            ),
            array(
                '$limit' => 5
            ),
            
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
    
        $items = array();
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            foreach ($agg['result'] as $result) 
            {
                $product = (new \RallyShop\Models\Products)->setState('filter.id', $result['_id'])->getItem();
                if (!empty($product->id)) 
                {
                    $product->__total = $result['total'];
                    $items[] = $product;
                }
            }
        }
    
        return $items;
    }

    public function fetchSalesData($start=null, $end=null)
    {
        $model = (new \RallyShop\Models\Orders)
        ->setState('filter.status_excludes', \Shop\Constants\OrderStatus::cancelled)
        ->setState('filter.financial_status', array( \Shop\Constants\OrderFinancialStatus::paid, \Shop\Constants\OrderFinancialStatus::authorized ) );
    
        if (!empty($start)) {
            $model->setState('filter.created_after', $start);
        }
    
        if (!empty($end)) {
            $model->setState('filter.created_before', $end);
        }
    
        $conditions = $model->conditions();
    
        $agg = \RallyShop\Models\Orders::collection()->aggregate(array(
            array(
                '$match' => $conditions
            ),
            array(
                '$group' => array(
                    '_id' => null,
                    'total' => array( '$sum' => '$grand_total' ),
                    'count' => array( '$sum' => 1 )
                )
            )
        ));
    
        //\Dsc\System::addMessage( \Dsc\Debug::dump($conditions) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($start) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($end) );
        //\Dsc\System::addMessage( \Dsc\Debug::dump($agg) );
    
        $total = 0;
        if (!empty($agg['ok']) && !empty($agg['result']))
        {
            $total = (float) $agg['result'][0]['total'];
        }
    
        return (float) $total;
    }
    
    public function fetchConversions($start=null, $end=null) 
    {
        
        return [];
    }
    
    /**
     * Actually returns total visitors.
     * TODO Rename function
     *
     * @param string $start
     * @param string $end
     * @return multitype:number
     */
    public function fetchTotalVisitors($start=null, $end=null)
    {
        $conditions = array(
            'action' => 'Visited Site'
        );
    
        if (!empty($start)) {
            $conditions['created'] = array('$gte' => strtotime($start));
        }
    
        if (!empty($end)) {
            if (empty($conditions['created'])) {
                $conditions['created'] = array('$lt' => strtotime($end));
            } else {
                $conditions['created']['$lt'] = strtotime($end);
            }
    
        }
    
        $return = \Activity\Models\Actions::collection()->count($conditions);
    
        return $return;
    }    
    
}