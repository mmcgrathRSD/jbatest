<?php if ($item->get('policies.returns')) : ?>
    <div class="col-xs-12">
      	<span id="returns" itemprop="returns" ><strong><span id="returns" itemprop="returns" class="header">Return Policy:</span></strong>
      	<div class="textModalBody">
                <h3>RallySport Guarantee - 30 Days</h3><br>
                Items that are covered under the RallySport Guarantee offer a 30 day no questions asked return policy. <br/> <br/>
                RallySport Guarantee items may be returned within 30 days of the original invoice date and must include all the original parts and contents for a refund. <br/> <br/>
                The RallySport Guarantee does not apply to products that have been modified, damaged, misused or abused. All returns require an RMA (Return Merchandise Authorization) Number, and freight charges are strictly non-refundable. For more information regarding our freight refund policy, please refer to our return policy page found <a href="pages/returns">HERE</a>.
        </div>
         <span class="content">
             <?php switch  ($item->get('policies.returns')) {
                case 'rsd':
                    echo '<a href="#" data-toggle="modal" data-target="#textModal" class="textModal"><small>RallySport Guarantee</small></a>';
                break;
                case 'standard':
                    echo "<small>Standard</small>";
                    break;
                default:
                    echo "<small>Not Specified</small>";
                    break;
                    ;
                break;
            }?>
         </span>
     </span>
  </div>
<?php endif;?>