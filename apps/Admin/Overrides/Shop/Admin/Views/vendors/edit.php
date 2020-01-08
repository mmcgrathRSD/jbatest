<div class="well">

<form id="detail-form" class="form" method="post">
    <div class="row">
        <div class="col-md-12">
            
            <div class="clearfix">

                <div class="pull-right">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <input id="primarySubmit" type="hidden" value="save_edit" name="submitType" />
                        <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_new'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Create Another</a>
                            </li>
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_as'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save As</a>
                            </li>
                            <li>
                                <a onclick="document.getElementById('primarySubmit').value='save_close'; document.getElementById('detail-form').submit();" href="javascript:void(0);">Save & Close</a>
                            </li>
                        </ul>
                    </div>
                        
                    &nbsp;
                    <a class="btn btn-default" href="./admin/shop/vendors">Cancel</a>
                </div>

            </div>
            <!-- /.form-group -->
            
            <hr />
            
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab-basics" data-toggle="tab"> Basics </a>
                </li>
                 <li class="">
                    <a href="#tab-images" data-toggle="tab"> Images and Display </a>
                </li>
                
                </li>
                <?php foreach ((array) $this->event->getArgument('tabs') as $key => $title ) { ?>
                <li>
                    <a href="#tab-<?php echo $key; ?>" data-toggle="tab"> <?php echo $title; ?> </a>
                </li>
                <?php } ?>                
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane active" id="tab-basics">
                	<?php echo $this->renderView("Shop/Admin/Views::vendors/tab_basics.php"); ?>
                </div>
                <!-- /.tab-pane -->
                 <div class="tab-pane" id="tab-images">
                	<?php echo $this->renderView("Shop/Admin/Views::vendors/tab_images.php"); ?>
                 </div>
              
                <!-- /.tab-pane -->
            </div>

        </div>
        
    </div>
</form>

</div>