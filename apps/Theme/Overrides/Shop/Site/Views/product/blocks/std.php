<div class="short-description" itemprop="description">
    <div class="std">
        <?php echo $item->description; ?>
        <div class="marginTop">
        <?php
        echo $item->getProp65Warning();
        ?>
        </div>
        <div class="col-xs-12">
      <strong>
         <span class="header">Carb Exempt: [
	         <div class="textModalBody">
	            <h3>Carb Restricted Shipping</h3>
	            <br>
	            If an Item is CARB exempt it means that it has undergone an engineering evaluation by CARB (California Air Recourses Board) and is shown to not increase vehicle emissions. If an item is CARB Exempt it will have an EO (Executive Order) number associated with it. Even if an item has an EO number it is not guaranteed to be CARB exempt for your vehicle. It is important to confirm that your Year Make and Model fall under the CARB EO number associated with the part. That can be confirmed either by checking the CARB website or the manufacturers website.
	         </div>
	         <a href="#"  data-toggle="modal" data-target="#textModal" class="textModal">?</a> ]
         </span>
      </strong>
      <span class="content">No</span>
   </div>
    </div>
</div>