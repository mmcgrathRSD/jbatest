<?php
if(!$master_search) {
    $instance_id = '_'.$item->id;
}

if(!empty($item)) {
    $type = $item->type();
}
?><div class="col-xs-12 well"> <div class="empty_template_query clearfix"><h3>Search term: <b><span></span></b></h3></div><div class="empty_template_parameters clearfix"></div><div id="empty_clear_all<?php echo $instance_id; ?>" class="marginTop"></div></div>