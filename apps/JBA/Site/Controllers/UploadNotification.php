<?php 
namespace JBA\Site\Controllers;

use JBA\Site\Models\Logs;

class UploadNotification extends \Dsc\Controller
{

    public function index()
    {
        $logs = (new \JBA\Site\Models\Logs)->setCondition('category', 'cloudinary')->getItems();
        
        $this->app->set('cloudinary', $logs);
        
        echo $this->theme->render('JBA\Site\Views::cloudinary/cloudinary.php');
    }

    public function recieve(){
        $headers = $this->app->get('HEADERS');
        $body = $this->app->get('BODY');


        $this->logMessage($headers, $body);

        $this->outputJson([
            'message' => 'ok',
        ]);
    }

    public function logMessage($headers, $body){
        $log = \JBA\Site\Models\Logs::uploadNotification($headers, $body, 'cloudinary');
    }
}