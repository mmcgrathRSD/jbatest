<div class="well">

<form id="settings-form" role="form" method="post" class="clearfix">

    <div class="clearfix">
        <button type="submit" class="btn btn-primary pull-right">Save Changes</button>
    </div>
    
    <hr/>

    <div class="row">
        <div class="col-md-3 col-sm-4">
            <ul class="nav nav-pills nav-stacked">
                <li class="active">
                    <a href="#tab-basic" data-toggle="tab"> General </a>
                </li>            
            </ul>
        </div>

        <div class="col-md-9 col-sm-8">

            <div class="tab-content stacked-content">
            
                <div class="tab-pane fade in active" id="tab-general">
                
                    <?php echo $this->renderLayout('RallySport/Admin/Views::settings/tab_general.php'); ?>

                </div>

            </div>

        </div>
    </div>

</form>

</div>