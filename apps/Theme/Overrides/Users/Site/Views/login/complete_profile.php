<div class="container">

<h2>
    Complete Profile <small>Final Step</small>
</h2>

<form action="./login/completeProfile" method="post">

    <div class="well well-sm">
        <div class="form-group">
            <label>Username</label>
            <input type="text" class="form-control" data-required="true" required="required" name="username" value="<?php echo $flash->old('username'); ?>" placeholder="Username">
        </div>
            
        <div class="form-group">
            <label>Email</label>
            <input type="text" class="form-control" data-required="true" required="required" name="email" value="<?php echo $flash->old('email'); ?>" placeholder="Email" autocomplete="email">
        </div>
    </div>

    <div class="input-group form-group">
        <button type="submit" class="btn btn-default custom-button btn-lg">Complete Profile</button>
    </div>    
    
</form>

</div>

