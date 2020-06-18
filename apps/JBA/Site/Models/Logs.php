<?php 
namespace JBA\Site\Models;

class Logs extends \Dsc\Mongo\Collections\Logs
{
    public $created;
    public $priority;
    public $category;
    public $message;
    public $headers;
    public $body;

    protected $__collection_name = 'common.cloudinary';
    protected $__type = 'common.cloudinary';

    public static function uploadNotification($headers, $body, $category, $message = null){
        $model = new static;
        $model->created = \Dsc\Mongo\Metastamp::getDate( 'now' );
        $model->set('created.microtime', microtime( true ) );

        $model->headers = $headers;
        $model->body = $body;
        $model->category = $category;

        $model->store();
        
        return $model;

    }
}