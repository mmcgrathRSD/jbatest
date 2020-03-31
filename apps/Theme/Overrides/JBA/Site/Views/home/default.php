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