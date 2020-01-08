<?php 
namespace RallySport\Site\Controllers;

class Products extends \Dsc\Controller
{
    public function reroute()
    {
        $url = '/';
        if (!empty($_GET['pn'])) {
            $product = (new \RallyShop\Models\Products())
                ->setCondition('tracking.model_number', $_GET['pn'])
                ->getItem()
            ;

            unset($_GET['pn']);

            if (!empty($product)) {
                $url .= 'shop/product/' . $product->slug;
            }
        }

        $url .= '?' . http_build_query($_GET);
        $this->app->reroute($url);
    }
}