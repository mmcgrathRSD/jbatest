<?php
//$item->updateReviewCounts();

//AUSTIN - (Here's my disclaimer: this is messy) using NR's electronic giftcard view for now given they will be the same
if($item->get('tracking.model_number') == 'RSD 50100') {
	echo $this->renderView ( 'Shop/Site/Views::product/product_types/giftcard_electronic.php' );
} elseif($item->get('tracking.model_number') == 'RSD 50100') {
	echo $this->renderView ( 'Shop/Site/Views::product/product_types/giftcard_phyiscal.php' );
} else {
	if($discontinued)  {
		echo $this->renderView ( 'Shop/Site/Views::product/product_types/standard.php' );
	} elseif($item->is_egiftcard) {
		echo $this->renderView ( 'Shop/Site/Views::product/product_types/giftcard_electronic.php' );
	}else {

	    switch ($item->{'product_type'}) {
            case 'dynamic_group':
                echo $this->renderView ( 'Shop/Site/Views::product/product_types/dynamic_group.php' );
                break;
			default:
				echo $this->renderView ( 'Shop/Site/Views::product/product_types/standard.php' );
				break;
		}
	}
}
 ?>