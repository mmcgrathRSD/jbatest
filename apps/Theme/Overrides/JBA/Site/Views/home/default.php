<?php 
   $modules = \Modules\Factory::load('homepage-slider', '/');
   foreach($modules as $key=> $module) {
      echo $module->render();
   }

   $pageOverride = \Shop\Models\Settings::fetch()->get('home.page_override');
   if (!empty($pageOverride)) {
      $page = (new \Pages\Models\Pages)
         ->populateState()
         ->setState('filter.type', true)
         ->setState('filter.slug', $pageOverride)
      ->setState('filter.publication_status', 'published')
      ->getItem()
      ;
         
      if (!empty($page->id) && !empty($page->copy)) {
         echo $page->copy;
         return;
      }
   }
?>
<div class="main-container col1-layout">
   <div class="content-container">
      <div class="main row clearfix">
         <div class="col-main grid_18">
            <div class="std">
            <tmpl type="modules" name="homepage-top-zone" />
               <?php if(!empty($metaDataOverride['h1'])) : ?>
                  <div class="container"><h1><?php echo $metaDataOverride['h1']; ?></h1></div>
               <?php endif; ?>
               <tmpl type="modules" name="homepage-mid-slider" />
               <?php if(!empty($metaDataOverride['topheadercopy'])) : ?>
                  <div class="marginTop marginBottom"><?php echo $metaDataOverride['topheadercopy']; ?></div>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>
