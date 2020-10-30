<div class="short-description" itemprop="description">
    <div class="std">
        <?php echo $item->description; ?>
        <div class="marginTop">
        <?php
        echo $item->getProp65Warning();
        ?>
        </div>
         <?php if($item->carbEOCat()) :?>
            <?php if(empty(trim($item->{'policies.carb_eo'}))) :?>
               <div class="">

                  <strong>
                     <span class="header">Carb Exempt: No [
                        <a href="#"  data-toggle="modal" data-target="#carbModal" class="carbModal">?</a> ]
                        <div class="carbModalBody">
                           <h3>Carb Restricted Shipping</h3>
                           <br>
                           If an Item is CARB exempt it means that it has undergone an engineering evaluation by CARB (California Air Recourses Board) and is shown to not increase vehicle emissions. If an item is CARB Exempt it will have an EO (Executive Order) number associated with it. Even if an item has an EO number it is not guaranteed to be CARB exempt for your vehicle. It is important to confirm that your Year Make and Model fall under the CARB EO number associated with the part. That can be confirmed either by checking the CARB website or the manufacturers website. <a href="#" class="closeCarbModal">X Close</a>
                        </div>
                        
                     </span>
                  </strong>
               </div>
            <?php else:?>
               <div class="">
                  <strong><span class="header">Carb Exempt:</span></strong>
                  <span class="content">Yes</span>
               </div>
               <div class="">
                  <strong><span class="header">Carb E.O. Number:</span></strong>
                  <span class="content"><?php echo $item->{'policies.carb_eo'}; ?></span>
               </div>
            <?php endif;?>
         <?php endif;?>
    </div>
</div>