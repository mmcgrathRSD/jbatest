<?php if(!empty($item->specs)) : ?>
<h2>Specifications</h2>
<div class="aamp_single_review">
<?php if (!empty($item->get('manufacturer.title'))) : ?>
    <div class="col-xs-4">
        <strong>Brand:</strong>
        <span class="grey"><span itemprop="brand"><?php echo $item->get('manufacturer.title'); ?></span></span>
    </div>

<?php endif ; ?>
			
			
<?php if($item->carbEOCat()) :?>
<?php if(empty(trim($item->{'policies.carb_eo'}))) :?>
    <div class="col-xs-4">
        <strong>Carb Exempt: [<button on="tap:carb-lightbox"
      role="button"
      tabindex="1"
      id="aamp_carb_modal_button"
      class="aamp_modal_button"></button><label for="aamp_carb_modal_button" class="regular_anchor">?</label>]</strong>
<amp-lightbox id="carb-lightbox"
	class="aamp_modal_container"
	layout="nodisplay">
	<div class="aamp_modal_body">
		<div class="aamp_modal_close"
			on="tap:carb-lightbox.close"
			role="button"
			tabindex="1">
			<i class="fa fa-times-circle-o" aria-hidden="true"></i>
		</div>
		<h3>Carb Restricted Shipping</h3><br>
        If an Item is CARB exempt it means that it has undergone an engineering evaluation by CARB (California Air Recourses Board) and is shown to not increase vehicle emissions. If an item is CARB Exempt it will have an EO (Executive Order) number associated with it. Even if an item has an EO number it is not guaranteed to be CARB exempt for your vehicle. It is important to confirm that your Year Make and Model fall under the CARB EO number associated with the part. That can be confirmed either by checking the CARB website or the manufacturers website.
	</div>
	
</amp-lightbox>

        <span class="grey">No</span>
    </div>
<?php else:?>
    <div class="col-xs-4">
        <strong>Carb Exempt:</strong>
        <span class="grey">Yes</span>
    </div>
    <div class="col-xs-4">
        <strong>Carb E.O. Number:</strong>
        <span class="grey"><?php echo $item->{'policies.carb_eo'}; ?></span>
    </div>
<?php endif;?>
<?php endif;?>
			
			
			
			
			
         <?php foreach($item->specs as $key => $value) :?>
         	<?php  
         	if(!empty($key) && !empty($value))  :
         	if(strlen($value)) : ?>
				<div class="col-xs-4">
					<strong><?php echo $key; ?>:</strong>
					<span class="grey"><?php echo  $value; ?></span>
				</div>
			<?php endif;?>
			<?php endif;?>
          <?php endforeach; ?>

			   <hr class="clear" />

           <?php if ($item->get('policies.warranty_period')) : ?>
				<div class="col-xs-4"> 
					<strong>Mfgr. Warranty:</strong>
					<span class="grey">
					<?php echo $item->get('policies.warranty_period') ;?>
					</span>
				</div>

           <?php endif;?>
			<div class="col-xs-4"> 
					<strong>Condition:</strong>
					<link itemprop="itemCondition" href="http://schema.org/OfferItemCondition" content="http://schema.org/NewCondition">
					<span class="grey">
					New Product
					</span>
				</div>
           
            
                 
           <?php if ($item->get('policies.returns')) : ?>
            <div class="col-xs-4">
                <strong>Return Policy:</strong>
                <?php switch  ($item->get('policies.returns')) {
                    case 'rsd':
                        echo '<button on="tap:returns-lightbox"
          role="button"
          tabindex="2"
          id="aamp_returns_modal_button"
          class="aamp_modal_button"></button><label for="aamp_returns_modal_button" class="regular_anchor">RallySport Guarantee</label>';
                    break;
                    case 'standard':
                        echo '<span class="grey">Standard</span>';
                        break;
                    default:
                        echo '<span class="grey">Not Specified</span>';
                        break;
                        ;
                    break;
                }?>
            </div>
        <amp-lightbox id="returns-lightbox"
            class="aamp_modal_container"
            layout="nodisplay">
            <div class="aamp_modal_body">
                <div class="aamp_modal_close"
                    on="tap:returns-lightbox.close"
                    role="button"
                    tabindex="2">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                </div>
                <h3>RallySport Guarantee - 30 Days</h3><br>
                        Items that are covered under the RallySport Guarantee offer a 30 day no questions asked return policy. <br/> <br/>
                        RallySport Guarantee items may be returned within 30 days of the original invoice date and must include all the original parts and contents for a refund. <br/> <br/>
                        The RallySport Guarantee does not apply to products that have been modified, damaged, misused or abused. All returns require an RMA (Return Merchandise Authorization) Number, and freight charges are strictly non-refundable. For more information regarding our freight refund policy, please refer to our return policy page found <a href="pages/returns">HERE</a>.
            </div>

        </amp-lightbox>
        <?php endif;?>
      <?php endif;?>	
      
      
<?php if ( $item->install_instructions ) : ?>

        <a href="<?php echo $item->install_instructions; ?>" class="btn btn-default btn-block" target="_blank">INSTALLATION INSTRUCTIONS</a><br/>

<?php endif; ?>
<?php if(!empty($kitSpecsSingle) || !empty($kitSpecsMulti)) : ?>
    <div class="pdpSectionTitle" <?php $mobile = \Audit::instance()->isMobile(); if($mobile) : ?>data-toggle="SpecsToggle"<?php endif; ?>>
            <i class="fa fa-chevron-down pull-right hidden footerChevron">&nbsp;</i><span>Specifications</span>
    </div>
    <div class="row pdpSection collapseContent" id="collapseSpecsToggle">
    <?php foreach ($kitSpecsSingle as $value): ?>
        <?php if(!empty($value['specs'])) : ?>
            <div class="col-md-4 col-xs-6 kitSpecs kitSpecs_<?php echo $value['kit-id']; ?>" id="kitSpec_<?php echo $value['id']; ?>">
                <h5>
                    <strong><?php echo $value['title']; ?></strong>
                </h5>
                <?php foreach ($value['specs'] as $key => $specs) : ?>
                    <?php if(!empty($specs)) : ?>
                        <strong>
                            <?php echo $key; ?>

                        </strong>
                        <span class="color: grey;">
                            <?php echo $specs; ?>
                        </span><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    <?php foreach ($kitSpecsMulti as $value): ?>
        <?php if(!empty($value['specs'])) : ?>
            <div class="col-md-4 col-xs-6 kitSpecs kitSpecs_<?php echo $value['kit-id']; ?>" style="display: none;" id="kitSpec_<?php echo $value['id']; ?>">
                <h5>
                    <strong><?php echo $value['title']; ?></strong>
                </h5>
                <?php foreach ($value['specs'] as $key => $specs) : ?>
                    <?php if(!empty($specs)) : ?>
                        <strong>
                            <?php echo $key; ?>

                        </strong>
                        <span class="grey">
                            <?php echo $specs; ?>
                        </span><br />
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
    </div>
<?php endif; ?>
	<div class="clear"></div>
</div>
<div class="clear">
	
</div>