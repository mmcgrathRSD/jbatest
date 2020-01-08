<a href="#" id="upload_widget_opener">Upload multiple images</a>

<script src="//widget.cloudinary.com/global/all.js" type="text/javascript"></script>  
          
<script type="text/javascript">  
  document.getElementById("upload_widget_opener").addEventListener("click", function(e) {

	  e.preventDefault();
	  
    cloudinary.openUploadWidget({ cloud_name: 'rallysportdirect', upload_preset: 'mdfo7dix'}, 
      function(error, result) { console.log(error, result) });

  }, false);
</script>