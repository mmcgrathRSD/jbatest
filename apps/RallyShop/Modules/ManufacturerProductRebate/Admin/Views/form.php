<?php
$flash = \Dsc\Flash::instance();

$item->products = (array)explode(',', $item->products);

$cats =[];

if( count( (array)$item->categories )){
    foreach($item->categories as $cat) {
        $cats[] = ['id' => $cat];
    }
}
$item->categories = $cats;

$flash->store($item->cast());
\Base::instance()->set('flash', $flash);

?>


<?php  echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_products.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_prices.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_inventory.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_tags.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_publication.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_categories.php'); ?>

<hr />

<?php echo \Dsc\System::instance()->get('theme')->renderLayout('Shop/Admin/Views::collections/fields_basics_manufacturers.php'); ?>




<input name="display[output_type]" value="raw" type="hidden">