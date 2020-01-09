<div class="form-group">
	<label>Screen Name</label>
	<input type="text" name="profile[screen_name]" class="form-control" value="<?php echo $flash->old('profile.screen_name'); ?>" placeholder="Employee Title">
</div>

<div class="form-group">
	<label>Social Role</label>
	<select name="profile[social_role]" class=" form-control">
	<option value='user' <?php if ($flash->old('profile.social_role') == 'user') { echo 'selected="selected" ';}?>>user</option>
	<option value='staff' <?php if ($flash->old('profile.social_role') == 'staff') { echo 'selected="selected" ';}?> >Staff</option>
	<option value='industry' <?php if ($flash->old('profile.social_role') == 'industry') { echo 'selected="selected" ';}?> >Industry</option>
	
	
	</select>
</div>
