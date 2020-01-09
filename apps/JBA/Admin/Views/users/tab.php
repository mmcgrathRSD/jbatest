<div class="form-group">
	<label>Notes</label>
	<textarea  name="rsd[notes]" class="form-control" value="<?php echo $flash->old('rsd.notes'); ?>" placeholder="Notes"></textarea>
</div>

<div class="form-group">
	<label>Title</label>
	<input type="text" name="rsd[title]" class="form-control" value="<?php echo $flash->old('rsd.title'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Title Description</label>
	<input type="text" name="rsd[title_description]" class="form-control" value="<?php echo $flash->old('rsd.title_description'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Role</label>
	<input type="text" name="rsd[job]" class="form-control" value="<?php echo $flash->old('rsd.job'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Supervisor Email</label>
	<input type="text" name="rsd[supervisor_email]" class="form-control" value="<?php echo $flash->old('rsd.supervisor_email'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Status Report Update Email</label>
	<input type="text" name="rsd[status_report_email]" class="form-control" value="<?php echo $flash->old('rsd.status_report_email'); ?>" placeholder="Employee Title">
</div>
<fieldset>
<legend>Payroll / HR</legend>
<div class="form-group">
	<label>Start Date</label>
	<input type="text" name="rsd[start_date]" class="form-control" value="<?php echo $flash->old('rsd.start_date'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>PTO days</label>
	<input type="text" name="rsd[pto_days]" class="form-control" value="<?php echo $flash->old('rsd.title'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Birthday Day</label>
	<input type="text" name="rsd[birthday]" class="form-control" value="<?php echo $flash->old('rsd.birthday'); ?>" placeholder="Employee Title">
</div>
</fieldset>

<fieldset>
<legend>Contact</legend>
<div class="form-group">
	<label>Phone Number</label>
	<input type="text" name="rsd[phone_number]" class="form-control" value="<?php echo $flash->old('rsd.phone_number'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>Office Number</label>
	<input type="text" name="rsd[phone_number]" class="form-control" value="<?php echo $flash->old('rsd.office_number'); ?>" placeholder="Employee Title">
</div>
<div class="form-group">
	<label>E-mail</label>
	<input type="text" name="rsd[phone_number]" class="form-control" value="<?php echo $flash->old('rsd.email'); ?>" placeholder="Employee Title">
</div>
</fieldset>

<fieldset>
	<legend>GP</legend>

	<div class="form-group">
		<label>Sales ID</label>
		<input type="text" name="gp[sales_id]" value="<?php echo $flash->old('gp.sales_id'); ?>" class="form-control" autocomplete="off" <?php echo empty($flash->old('gp.sales_id')) ? '' : 'disabled' ?> />
	</div>
</fieldset>