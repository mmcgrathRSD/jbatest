<?php 
$img = array();
$img[] = $item->{'img_1'};
$img[] = $item->{'img_2'};
$img[] = $item->{'img_3'};
$img[] = $item->{'img_4'};
$img[] = $item->{'img_5'};
$img[] = $item->{'img_6'};

$imgLink = array();
$imgLink[] = $item->{'imgLink_1'};
$imgLink[] = $item->{'imgLink_2'};
$imgLink[] = $item->{'imgLink_3'};
$imgLink[] = $item->{'imgLink_4'};
$imgLink[] = $item->{'imgLink_5'};
$imgLink[] = $item->{'imgLink_6'};

$module1 = $item->{'img_bg'};
$module2 = $item->{'title'};
$module3 = $item->{'desc'};
$module4 = $item->{'img_bg_mobile'};

?>

<div class="marketingImgBox">
    <h2>
        Module
    </h2>
    Desktop BG Image:
    <input name="img_bg" class="form-control" type="text" value="<?php if(isset($module1)) { echo $module1; }  ?>">
    Desktop Title:
    <input name="title" class="form-control" type="text" value="<?php if(isset($module3)) { echo $module2; }  ?>">
    Desktop Description:
    <input name="desc" class="form-control" type="text" value="<?php if(isset($module3)) { echo $module3; }  ?>">
    Mobile BG Image:
    <input name="img_bg_mobile" class="form-control" type="text" value="<?php if(isset($module4)) { echo $module4; }  ?>">
</div>

<div class="marketingImgBox">
    <h2>
        Slot 1
    </h2>
    Image:
    <input name="img_1" class="form-control" type="text" value="<?php if(isset($img[0])) { echo $img[0]; }  ?>">
    Link:
    <input name="imgLink_1" class="form-control" type="text" value="<?php if(isset($imgLink[0])) { echo $imgLink[0]; }  ?>">
    
</div>

<div class="marketingImgBox">
    <h2>
        Slot 2
    </h2>
    Image:
    <input name="img_2" class="form-control" type="text" value="<?php if(isset($img[1])) { echo $img[1]; }  ?>">
    Link:
    <input name="imgLink_2" class="form-control" type="text" value="<?php if(isset($imgLink[1])) { echo $imgLink[1]; }  ?>">
      
</div>

<div class="marketingImgBox">
    <h2>
        Slot 3
    </h2>
    Image:
    <input name="img_3" class="form-control" type="text" value="<?php if(isset($img[2])) { echo $img[2]; }  ?>">
    Link:
    <input name="imgLink_3" class="form-control" type="text" value="<?php if(isset($imgLink[2])) { echo $imgLink[2]; }  ?>">
     
</div>

<div class="marketingImgBox">
    <h2>
        Slot 4
    </h2>
    Image:
    <input name="img_4" class="form-control" type="text" value="<?php if(isset($img[3])) { echo $img[3]; }  ?>">
    Link:
    <input name="imgLink_4" class="form-control" type="text" value="<?php if(isset($imgLink[3])) { echo $imgLink[3]; }  ?>">
    
</div>

<div class="marketingImgBox">
    <h2>
        Slot 5
    </h2>
    Image:
    <input name="img_5" class="form-control" type="text" value="<?php if(isset($img[4])) { echo $img[4]; }  ?>">
    Link:
    <input name="imgLink_5" class="form-control" type="text" value="<?php if(isset($imgLink[4])) { echo $imgLink[4]; }  ?>">
     
</div>

<div class="marketingImgBox">
    <h2>
        Slot 6
    </h2>
    Image:
    <input name="img_6" class="form-control" type="text" value="<?php if(isset($img[5])) { echo $img[5]; }  ?>">
    Link:
    <input name="imgLink_6" class="form-control" type="text" value="<?php if(isset($imgLink[5])) { echo $imgLink[5]; }  ?>">
    
</div>



<style>
    .marketUpdating, .marketUpdated, .marketUpdate {
        display: none;
    }
    .marketingImgBox {
        padding: 20px;
        background-color: #3a3633;
        border:solid 0px #000000;
        color: #fff;
        margin-bottom: 20px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-box-shadow: 0px 1px 5px #000000;
        -webkit-box-shadow: 0px 1px 5px #000000;
        box-shadow: 0px 1px 5px #000000;
    }
    
    .marketingImgBox h2 {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    
    .marketingImgBox input {
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }
    
    .module-content .row {
        margin-bottom: 30px;
    }
</style>