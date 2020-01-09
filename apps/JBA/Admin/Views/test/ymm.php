


<div class="form-group">
		        <label>Find your car</label>
		        <input id="ymm" type="text" name="__ymms" placeholder="2014 Subaru WRX" class="form-control" />
		        </div>
		        
		        
		        
		        
		        
		        
		        <script>
jQuery(document).ready(function() {


	function processJson(data) {
		$('#ordertabslist').append(data.tab);
		$('#sideBar').append(data.sidebar);
		$('#orderContents').append(data.tabcontent);
		$('#ordertabslist a:last').tab('show')
	}
	
	function clearCustomer() {
		$('.customerStep').remove();
		
	}

	
	
	function selectCustomer(object, container) {
		clearCustomer();
		//user the returned email from selection to load the user object to the form and set customer from order
		$.ajax({
		  type: "POST",
		  url: "./admin/shop/order/flow/getCustomerForOrder",
		  data: {email: object.id},
		  success: processJson,
		  dataType: 'json'
		});

		return object.text
    
	}

    
    jQuery("#ymm").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: false,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/ymm/forSelection?value=title",
            dataType: 'json',
            data: function (term, page) {
                return {
                    q: term
                };
            },
            results: function (data, page) {
                return {results: data.results};
            }
        },
  	  formatSelection: selectCustomer
    
    }); 

}); 
</script>
		        