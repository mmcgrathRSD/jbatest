<?php
echo "<pre>";
print_r($this->app->get('cloudinary'));


foreach($this->app->get('cloudinary') as $log){
    print_r($log);
}