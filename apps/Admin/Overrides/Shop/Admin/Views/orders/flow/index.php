<style>
#orderFlow .tab-content {
background:#fff; 
}
blockquote.csrQuestion {
font-weight:bold;
}
</style>


<div id="orderFlow">
	<h1>Create New Order</h1>
	<div class="row">
		<div class="col-md-2">
			<div id="sideBar"></div>
		</div>
		
		<div class="col-md-10">
			<form class="form-inline">
				<div role="tabpanel">
					<!-- Nav tabs -->
					<ul id="ordertabslist" class="nav nav-tabs" role="tablist">
				    	<li role="presentation" class="active"><a href="#first" aria-controls="home" role="tab" data-toggle="tab">User</a></li>
				    </ul>
				
					<!-- Tab panes -->
					<div id="orderContents" class="tab-content">
			    		<?php echo $this->renderLayout("Shop/Admin/Views::orders/flow/firstStep.php"); ?>	
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<script type="text/javascript">

function processResponseJson(response) {
	console.log(response);
	if( response.result ){
		if( response.data.tab != null){
			$('#ordertabslist').append(response.data.tab);		
		}
		if( response.data.sidebar != null ){
			$('#sideBar').append(response.data.sidebar);		
		}
		
		if( response.data.tabcontent != null ){
			$('#orderContents').append(response.data.tabcontent);	
		}
		
		if( response.data.select == null ){
			$('#ordertabslist a:last').tab('show');
		} else {
			$('#ordertabslist a[href="#'+response.data.select+'"]').tab('show');	
		}
		
	}
}

function deleteTab(tabID){
	var currentTab = $('#ordertabslist a[href="#'+tabID+'"]').closest("li[role='presentation']");	
	var wasActive = currentTab.hasClass("active");
	currentTab.remove();
	if( wasActive ){
		$('#ordertabslist a:first').tab("show");		
	}
	$('#orderContents div#'+tabID).remove();
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
	  success: processResponseJson,
	  dataType: 'json'
	});

	return object.text
}


// validates customer - basic
function validateCreateCustomer(cust, $panel){
	var warnings = [];
	if(typeof cust.first_name == "undefined" || cust.first_name.trim().length == 0 ) {
		warnings.push( "Please, add first name of this customer" );
	}
	if(typeof cust.last_name == "undefined" || cust.last_name.trim().length == 0 ) {
		warnings.push( "Please, add last name of this customer" );
	}
	if(typeof cust.email == "undefined" || cust.email.trim().length == 0 ) {
		warnings.push( "Please, add email address of this customer" );
	}
	
	var $validationDiv = $("div#cuValidation", $panel).html("");
	if(warnings.length){
		var html_text = '<div class="alert alert-danger" role="alert"><ul>';
		for(idx = 0; idx < warnings.length; idx++){
			html_text += "<li>"+warnings[idx]+"</li>";
		}
		html_text += '</ul></div>';
		$validationDiv.append(html_text);
		return false;
	}
	return true;
}

function createCustomerForOrder(e){
	var $panel = $(e.currentTarget).closest("div[role='tabpanel']");
	var obj = {
		'first_name' : $("input#cuFirstName", $panel).val(),
		'last_name' : $("input#cuLastName", $panel).val(),
		'email' : $("input#cuEmail", $panel).val(),		
	}
	if( validateCreateCustomer( obj, $panel ) ){
		$.ajax({
		  type: "POST",
		  url: "./admin/shop/order/flow/addGuestCustomerToOrder",
		  data: { "firstName": obj.first_name, "lastName" : obj.last_name, "email" : obj.email },
		  success: processResponseJson,
		  dataType: 'json'
		});
	}
}


jQuery(document).ready(function() {	

    // step 1, select autocomplete
    jQuery("#customers").select2({
        allowClear: true, 
        placeholder: "Search...",
        multiple: false,
        minimumInputLength: 3,
        ajax: {
            url: "./admin/shop/customers/forSelection?value=email",
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
    
    var $orderFlow = $("div#orderFlow");
    
    jQuery("a[data-url]", $orderFlow).on('click', function(e){
    	e.preventDefault();
    	
		$.ajax({
		  type: "POST",
		  url: $(this).attr("data-url"),
		  data: {},
		  success: processResponseJson,
		  dataType: 'json'
		});
    });
    
    // delete this tab and return to the first one
    $orderFlow.on("click", "a[data-task='deleteTab']", function(e){
    	deleteTab( $(e.currentTarget).closest("div[role='tabpanel']").attr("id"));
    });
    
    
    //createCustomer
    $orderFlow.on('click', 'a[data-task="createCustomer"]', createCustomerForOrder);
}); 
</script>