<div class="main row">
    <div class="col-main grid_13 custom_left">
        <div class="my-account">
            <div class="page-title">
                <h1>My Orders</h1>
            </div>
            <div id="myOrders" class="" role="tabpanel" aria-labelledby="headingSeven">
                <?php echo $this->renderView('Shop\Site\Views::orders/list.php'); ?>
                <?php echo $this->renderView('Shop\Site\Views::orders/algolia_js.php'); ?>
            </div>
            <div class="buttons-set">
                <p class="back-link"><a href="/shop/account"><small>« </small>Back</a></p>
            </div>
        </div>
    </div>
    <?php echo $this->renderView('Shop/Site/Views::account/sidebar.php'); ?>
</div>
<style>
#orders_pagination a.ais-pagination--link {
    padding: 7px;
    padding-left: 14px;
    padding-right: 14px;
}

#orders_pagination ul.ais-pagination {
    margin-top: 10px;
    margin-bottom: 10px;
    clear: both;
}
</style>