<a href="#" id="upload_widget_opener">Upload multiple images</a>

<script src="//widget.cloudinary.com/global/all.js" type="text/javascript"></script>  
          
<script type="text/javascript">  
  document.getElementById("upload_widget_opener").addEventListener("click", function(e) {

	  e.preventDefault();
	  
    cloudinary.openUploadWidget({ cloud_name: 'rallysportdirect', upload_preset: '<?php echo \Base::instance()->get('cloudinary.preset_1');?>'}, 
      function(error, result) { console.log(error, result) });

  }, false);
</script>