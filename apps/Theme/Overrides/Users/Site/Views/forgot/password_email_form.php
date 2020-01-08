<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ">
            <legend class="paddingTop">
                <strong>Forgotten Password?</strong>
            </legend>
            
            <h4>Provide us with your email address.  We'll send you a private URL to use to reset your password.</h4>
            
            <h4><b>IMPORTANT:</b> The URL will only be valid for 2 hours and can only be used once.</h4> 
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
            <form action="./user/forgot-password" method="post" class="form" role="form">
                <div class="form-group paddingTop">
                    <label>Email Address:</label>
                    <input class="form-control" name="email" placeholder="Email Address" type="text" />
                </div>
                    <div class="form-group">       
                <button class="btn btn-lg btn-primary" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>