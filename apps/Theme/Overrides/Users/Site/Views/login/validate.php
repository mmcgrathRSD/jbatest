<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <legend>
                Email Verification
            </legend>
            
            <p>We have sent you an email to verify your identity.  Please click the link inside the email to confirm your email address and activate your account.</p>
            
            <p>Alternatively, you may copy and paste the Token from the email into this form.</p> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <form action="./login/validate" method="post" class="form" role="form">
                <div class="form-group">
                    <label>Token</label>
                    <input class="form-control" name="token" placeholder="Token from email" type="text" />
                </div>
                            
                <button class="btn btn-lg btn-primary" type="submit">Submit</button>
                
            </form>
        </div>
    </div>
</div>