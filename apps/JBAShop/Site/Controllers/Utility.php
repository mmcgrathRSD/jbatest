<?php 
namespace JBAShop\Site\Controllers;

class Utility extends \Dsc\Controller
{
    public function currentDateUTC()
    {
        $date = new \DateTime('now', new \DateTimeZone('UTC'));
        return $this->outputJson($date->format('Y-m-d H:i:s'));
    }
}
