<div id="link-last-container">
    <p>This is the last link in the list and looks different (use it as a "view more" link, etc.).</p>
    <label for="listrak-title">Title (Will display above slider):</label>
    <input id="listrak-title" type="text" name="listrak-title" class="form-control" value="<?php echo $item->{'listrak-title'} ?>">
    <label for="listrak-id">Listrak Merchandise Block ID:</label>
    <input id="listrak-id" type="text" name="listrak-id" class="form-control" value="<?php echo $item->{'listrak-id'} ?>">
</div>
<style>
    #link-last-container {
        padding: 20px;
        background-color: #3a3633;
        border: solid 0px #000000;
        color: #fff;
        margin-bottom: 20px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        -moz-box-shadow: 0px 1px 5px #000000;
        -webkit-box-shadow: 0px 1px 5px #000000;
        box-shadow: 0px 1px 5px #000000;
    }

    #link-list .link-target {
        width: auto;
        display: inline;
        vertical-align: middle;
        margin: 10px;
    }
</style>