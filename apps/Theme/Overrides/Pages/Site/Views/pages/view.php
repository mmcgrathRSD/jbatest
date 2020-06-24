<div class="content-container">
    <div class="main row">
        <div class="col-main grid_13 custom_left">
            <div class="breadcrumbs">
                <ul>
                    <li style="display: inline-block;" typeof="v:Breadcrumb">
                        <a href="/" title="Home" rel="v:url" property="v:title">Home</a>
                    </li>
                    <li style="display: inline-block;">
                        <span>/</span>
                        <strong><?php echo $item->get('title'); ?></strong>
                    </li>
                </ul>
            </div>
            <div class="page-title">
                <h1><?php echo $item->get('title'); ?></h1>
            </div>
            <div class="std">
                <?php echo $item->{'copy'}; ?>
            </div>
        </div>
        <div class="col-left sidebar grid_5 custom_left">
            <tmpl type="modules" name="account-page-sidebar" />
        </div>
    </div>
</div>

<div style="clear:both; padding-bottom: 20px;"></div>
