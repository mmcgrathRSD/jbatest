
    <ol class="breadcrumb row">
        <li>
            <a href="./user">My Account</a>
        </li>
        <li class="active">Change Password</li>
    </ol>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
            <legend>
                Change Password
            </legend>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <form action="./user/change-password" method="post" class="form" role="form">
                <div class="form-group">
                    <label>New Password</label>
                    <input class="form-control" name="new_password" placeholder="New Password" type="password" required />
                </div>
                
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <input class="form-control" name="confirm_new_password" placeholder="Confirm New Password" type="password" required />
                </div>
                            
                <button class="btn btn-lg btn-primary" type="submit">Update password</button>
                <a class="btn btn-link" href="./user">Cancel</a>
                
            </form>
        </div>
    </div>
