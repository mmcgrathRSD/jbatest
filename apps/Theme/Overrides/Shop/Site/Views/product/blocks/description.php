<?php
    $cache = \Cache::instance();
    if (!$cache->exists('product_ymms_.' . (string) $item->id, $ymmMapping )) {
        $ymmMapping = $item->getYMMMapping();
        $cache->set('product_ymms_.' . (string) $item->id, $ymmMapping, 86400);
    }
?>


<div class="product-tabs-container clearfix">
    <ul class="product-tabs clearfix">
        <li class="active"><a href="#" class="jba_tabs" data-tab="product_tabs_description_tabbed_contents">Description</a></li>
        <li><a href="#" class="jba_tabs" data-tab="product_tabs_additional_tabbed_contents">Additional</a></li>
        <?php if (!empty($ymmMapping)) : ?><li><a href="#" class="jba_tabs" data-tab="product_tabs_fitment_tabbed_contents">Fitment</a></li><?php endif; ?>
    </ul>
    <div class="product-tabs-content tabs-content std" id="product_tabs_description_tabbed_contents" style="">
        <h2>Details</h2>
        <div class="std">
            <div class="container">
            <?php echo $item->copy; ?>
            </div>
            <div id="clear"></div>
        </div>
        <?php if(!empty($kitSpecsSingle) || !empty($kitSpecsMulti)) : ?>
<div class="row real-clearfix description_section">
   <?php foreach ($kitSpecsSingle as $value): ?>
   <?php if(!empty($value['copy'])) : ?>
   <div class="kit_description" id="kit_description_<?php echo $value['id']; ?>">
      <div class="col-xs-12">
      	<?php echo strip_tags($value['copy'], '<br><br/><a></a><p></p><ul class="part_list"><ol><li>'); ?>
      </div>
      <?php if(!empty($item->secondaryH2)) : ?>
    <h2 class="block marginTopNone desc_h2"><?php echo $item->secondaryH2; ?></h2>
    <?php endif; ?>
   </div>
   <?php endif; ?>
   <?php endforeach; ?>
   <?php foreach ($kitSpecsMulti as $value): ?>
   <?php if(!empty($value['copy'])) : ?>
   <div class="kit_description" style="display: none;" id="kit_description_<?php echo $value['id']; ?>">
      <div class="col-xs-12">
      	<?php echo strip_tags($value['copy'], '<br><br/><a></a><p></p><ul class="part_list"><ol><li>'); ?>
      </div>
      <?php if(!empty($item->secondaryH2)) : ?>
    <h2 class="block marginTopNone desc_h2"><?php echo $item->secondaryH2; ?></h2>
    <?php endif; ?>
   </div>
   <?php endif; ?>
   <?php endforeach; ?>
</div>
<?php endif; ?>
    </div>
    <div class="product-tabs-content tabs-content " id="product_tabs_additional_tabbed_contents" style="display: none;">
        <h2>Additional Information</h2>
        <table class="data-table" id="product-attribute-specs-table">
            <colgroup>
            <col width="25%">
            <col>
            </colgroup>
            <tbody>
            <tr class="first odd">
                <th class="label">Installation Instructions</th>
                <td class="data last">
                    <?php if(!empty($item->get('install_instructions'))) : ?>
                        <a href="<?php echo $item->get('install_instructions') ;?>" target="_blank">Link</a>
                    <?php else : ?>
                        None
                    <?php endif; ?>
                </td>
            </tr>
            <tr class="last even">
                <th class="label">Additional Warranty Information</th>
                <td class="data last">
                <?php if ($item->get('policies.warranty_period')) : ?>
                    <?php echo $item->get('policies.warranty_period') ;?>
                <?php else : ?>
                    None
                <?php endif;?>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">decorateTable('product-attribute-specs-table')</script>
    </div>
    <?php if (!empty($ymmMapping)) : ?>
    <div class="product-tabs-content tabs-content " id="product_tabs_fitment_tabbed_contents" style="display: none;">
        <div class="std">
            <div class="container">
            <?php echo $this->renderView ( 'Shop/Site/Views::product/blocks/new_confirmed_fitment_inner.php' ); ?>
            </div>
            <div id="clear"></div>
        </div>
    </div>
    <?php endif; ?>
</div>