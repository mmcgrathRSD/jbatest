<div class="product-tabs-container clearfix">
    <ul class="product-tabs clearfix">
        <li class="active"><a href="#" class="jba_tabs" data-tab="product_tabs_description_tabbed_contents">Description</a></li>
        <li><a href="#" class="jba_tabs" data-tab="product_tabs_additional_tabbed_contents">Additional</a></li>
    </ul>
    <div class="product-tabs-content tabs-content std" id="product_tabs_description_tabbed_contents" style="">
        <h2>Details</h2>
        <div class="std">
            <h4 align="center"><?php echo $item->title; ?></h4>
            <div class="container">
            <?php echo $item->copy; ?>
            </div>
            <div id="clear"></div>
            <?php echo $this->renderView ( 'Shop/Site/Views::product/blocks/new_confirmed_fitment_inner.php' ); ?>
        </div>
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
                    <?php if(!empty($item->get('policies.warranty_period'))) : ?>
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
</div>