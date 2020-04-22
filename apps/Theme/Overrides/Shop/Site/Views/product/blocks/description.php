<div class="product-tabs-container clearfix">
    <ul class="product-tabs clearfix">
        <li id="product_tabs_description_tabbed" class="first active"><a href="javascript:void(0)">Description</a></li>
        <li id="product_tabs_additional_tabbed" class="last"><a href="javascript:void(0)">Additional</a></li>
    </ul>
    <h2 id="product_acc_description_tabbed" class="tab-heading active"><a href="#">Description</a></h2>
    <div class="product-tabs-content tabs-content std" id="product_tabs_description_tabbed_contents" style="">
        <h2>Details</h2>
        <div class="std">
            <h4 align="center"><?php echo $item->title; ?></h4>
            <div class="container">
            <?php echo $item->copy; ?>
            </div>
            <div id="clear"></div>
            <?php //West told me to remove the 'this part fits' section ?>
        </div>
    </div>
    <h2 id="product_acc_additional_tabbed" class="tab-heading"><a href="#">Additional</a></h2>
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
                <td class="data last">These knobs come with a red base which is to be removed prior to installation.  The inner trim ring can be swapped out due to the indicator pointer that come with these as well.</td>
            </tr>
            <tr class="last even">
                <th class="label">Additional Warranty Information</th>
                <td class="data last">None</td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">decorateTable('product-attribute-specs-table')</script>
    </div>
</div>