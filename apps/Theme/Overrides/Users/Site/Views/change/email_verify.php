<div class="container">

    <ol class="breadcrumb">
        <li>
            <a href="./user">My Account</a>
        </li>
        <li class="active">Change Email</li>
    </ol>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <legend>
                Verify Email Address
            </legend>
            
            <p>We have emailed you a private URL.  Please click that URL to verify your email address.</p>

            <p><b>IMPORTANT:</b> The URL will only be valid for 2 hours and can only be used once.</p>
            
            <p>Alternatively, you can provide the Token from the email below.</p>            
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">            
            <form action="./user/change-email/confirm" method="post" class="form" role="form">
                <div class="form-group">
                    <label>New Email Address</label>
                    <input class="form-control" name="new_email" placeholder="New Email Address" type="email" required />
                </div>
                
                <div class="form-group">
                    <label>Token</label>
                    <input class="form-control" name="token" placeholder="Token" type="string" required />
                </div>
                            
                <button class="btn btn-lg btn-primary" type="submit">Verify</button>
                
            </form>
        </div>
    </div>
</div>