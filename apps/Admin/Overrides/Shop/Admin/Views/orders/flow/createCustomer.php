<div role="tabpanel" class="tab-pane" id="createCustomer">
	<div class="well well-sm">
		<h2>Create a New Customer</h2>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-xs-6">
				<label>First Name</label>
				<input class="form-control" type="text" id="cuFirstName" placeholder="Customer's first name"/>
			</div>
			<div class="col-lg-4 col-md-4 col-xs-6">
				<label>Last Name</label>
				<input class="form-control" type="text" id="cuLastName" placeholder="Customer's last name"/>
			</div>
			<div class="col-lg-4 col-md-4 col-xs-6">
				<label>Email Address</label>
				<div class="input-group">
					<input class="form-control" type="email" id="cuEmail" placeholder="Customer's Email Address"/>
					<div class="input-group-addon"><i class="fa fa-check"> </i></div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12 col-xs-12">
			<div id="cuValidation">
				<div class="alert alert-success" role="alert">Email is OK</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-6 col-xs-6 col-md-6">
			<a class="btn btn-danger" data-task="deleteTab"><i class="fa fa-angle-double-left"> </i> Back to list of users</a>
		</div>
		<div class="col-lg-6 col-xs-6 col-md-6">
			<a class="btn btn-primary pull-right" data-task="createCustomer"><i class="fa fa-user"> </i> Create customer and proceed</a>
		</div>
	</div>
</div>