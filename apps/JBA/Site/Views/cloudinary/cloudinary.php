<?php
echo "<pre>";
$data = $this->app->get('cloudinary');
echo "<b>TOTAL UPLOADED: " . count($data) . "</b><br/><br/>";
foreach($data as $doc){
    $body = json_decode($doc['body']);
    echo 'Time:' . $body->timestamp . "<br/>";
    echo 'Type:' . $body->notification_type . "<br/>";
    echo 'Type:' . $body->public_id . "<br/><br/>";
}

?>
