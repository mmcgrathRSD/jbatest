
    <ol class="breadcrumb row">
        <li>
            <a href="./user">My Account</a>
        </li>
        <li class="active">Change Email</li>
    </ol>
    
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <legend>
                Change Email Address
            </legend>
            
            <p><b>Current Email Address:</b> <?php echo $this->auth->getIdentity()->email; ?> </p>            
            
            <p>Before this change can be finalized, you will need to verify ownership of the email address. We'll email you a link to open in your browser.</p>
            
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            
            <form action="./user/change-email" method="post" class="form" role="form">
                <div class="form-group">
                    <label>New Email Address</label>
                    <input class="form-control" name="new_email" placeholder="New Email Address" type="email" required />
                </div>
                            
                <button class="btn btn-lg btn-primary" type="submit">Submit</button>
                <a class="btn btn-link" href="./user">Cancel</a>
                
            </form>
        </div>
    </div>
