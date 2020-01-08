<div>
<div class="clearfix">

<div class="col-md-12">

    <div class="portlet">

        <div class="portlet-header">

            <h3>Unique title</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
        	<input name="cookie_title" class="form-control" type="text" value="<?php echo $item->{'cookie_title'};?>" onKeyPress="return letternumber(event)">
        </div>
        <!-- /.portlet-content -->

    </div>
    <!-- /.portlet -->    

    <div class="portlet">

        <div class="portlet-header">

            <h3>Time on site to show disruptor (In seconds)</h3>

        </div>
        <!-- /.portlet-header -->

        <div class="portlet-content">
            <input placeholder="00:00:00" name="time_to_show" class="form-control" type="text" value="<?php echo $item->{'time_to_show'};?>">
        </div>
        <!-- /.portlet-content -->
        <label>Use mouse exit detection to trigger disruptor</label><br />
        <input type="radio" name="use_mouse_override" id="use_mouse_override_yes" value="yes" <?php if ($item->get('use_mouse_override') == "yes") echo "checked='checked'"; ?>><label class="radio-inline" for="use_mouse_override_yes" style="padding-left: 5px;"> Yes</label>
        <label class="radio-inline">
            <input type="radio" name="use_mouse_override" value="no" <?php if ($item->get('use_mouse_override') == "no") echo "checked='checked'"; ?>> No
        </label>
    </div>
    <!-- /.portlet -->    


</div>
<SCRIPT TYPE="text/javascript">
function letternumber(e)
{
var key;
var keychar;

if (window.event)
   key = window.event.keyCode;
else if (e)
   key = e.which;
else
   return true;
keychar = String.fromCharCode(key);
keychar = keychar.toLowerCase();

// control keys
if ((key==null) || (key==0) || (key==8) || 
    (key==9) || (key==13) || (key==27) )
   return true;

// alphas and numbers
else if ((("abcdefghijklmnopqrstuvwxyz0123456789").indexOf(keychar) > -1))
   return true;
else
   return false;
}
//-->
</SCRIPT>