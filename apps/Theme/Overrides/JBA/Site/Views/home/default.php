<?php 
$modules = \Modules\Factory::load('homepage-slider', '/');
foreach($modules as $key=> $module) {
	echo $module->render();
}
?>
<div class="main-container col1-layout">
   <div class="content-container">
      <div class="main row clearfix">
         <div class="col-main grid_18">
            <div class="std">
               <tmpl type="modules" name="homepage-mid" />
            </div>
         </div>
      </div>
   </div>
</div>
<?php
   if(!\Audit::instance()->isbot()){
      $prodCursor = (new \Shop\Models\Products)->collection()->find([
         'publication.sales_channels.slug' => \Base::instance()->get('sales_channel'),
         'publication.status' => 'published',
      ]);
      foreach($prodCursor as $doc){
         $path = sprintf("/part/%s\n", $doc['slug']);
         echo <<<HTML
         <a href="{$path}">$path</a>
         HTML;
      }
   }
?>