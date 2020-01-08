<?php header('location: /shop/account'); ?>
<?php $identity = $this->auth->getIdentity(); ?>



<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
<h2>CHANGE AVATAR</h2>
</div>
<div class="col-lg-9 col-md-9 col-sm-12 col-xs-12 paddingTop paddingBottom">

<form method="post" enctype="multipart/form-data">
 <label for="avatar">( 250x 250 recommended )</label>
<input name="avatar" type="file" class="form-control">
<br/>
<button type="submit" class="btn btn-primary pull-right" >Submit</button>
</form>

<?php if($url = $identity->profilePicture()) : ?>
<img src="<?php echo $url; ?>" class="img-responsive" style="max-width:250px;">
<?php endif;?>

</div>