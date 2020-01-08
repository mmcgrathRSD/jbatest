<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-table fa-fw "></i> 
                Want to be Notified
            <span> > 
                List
            </span>
        </h1>
    </div>
</div>

<form class="searchForm" method="post">
    <input type="hidden" name="list[order]" value="<?php echo $state->get('list.order'); ?>" />
    <input type="hidden" name="list[direction]" value="<?php echo $state->get('list.direction'); ?>" />

    <div class="row">
        <div class="col-xs-3 col-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="filter[part_number]" placeholder="Full Part #" value="<?php echo $state->get('filter.part_number'); ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" value="Search" />
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xs-3 col-md-4">
            <div class="form-group">
                <div class="input-group">
                    <input class="form-control" type="text" name="filter[email]" placeholder="Email Address" value="<?php echo $state->get('filter.email'); ?>">
                    <span class="input-group-btn">
                        <input class="btn btn-primary" type="submit" value="Search" />
                    </span>
                </div>
            </div>
        </div>

        <div class="text-right col-xs-6 col-md-4">
            <button class="btn btn-danger" type="button" onclick="Dsc.resetFormFilters(this.form);">Reset Filters</button>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>

                <?php if (!empty($paginated->items)) { ?>
                    <div class="col-xs-6 text-align-right">
                    <span class="pagination">
                        <span class="hidden-xs hidden-sm">
                            <?php echo $paginated->getResultsCounter(); ?>
                        </span>
                    </span>
                    <span class="pagination">
                        <?php echo $paginated->getLimitBox( $state->get('list.limit') ); ?>
                    </span>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th class="text-center"><a class="btn btn-link" data-sortable="product.publication.status">Publication Status</a></th>
                        <th><a class="btn btn-link" data-sortable="product.tracking.model_number">Part Number</a></th>
                        <th><a class="btn btn-link" data-sortable="product.title">Title</a></th>
                        <th class="text-right"><a class="btn btn-link" data-sortable="product.purchase_orders.total_ordered_available">On Order</a></th>
                        <th class="text-right"><a class="btn btn-link" data-sortable="want_to_be_notified">Want to be Notified</a></th>
                        <th class="text-center"><a class="btn btn-link">Actions</a></th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach($paginated->items as $item): ?>
                        <tr class="mainRow">
                            <td class="text-center">
                                <span class="label <?php echo $item->publishableStatusLabel() ?>"><?php echo $item->{'product.publication.status'} ?></span>
                            </td>
                            <td><?php echo $item->{'product.tracking.model_number'} ?></td>
                            <td><?php echo $item->{'product.title'} ?></td>
                            <td class="text-right">
                                <?php echo ((int) $item->{'product.purchase_orders.total_ordered_available'}) ?: '<span class="label label-warning">None</span>'; ?>
                            </td>
                            <td class="text-right"><?php echo $item->want_to_be_notified ?></td>
                            <td class="text-center">
                                <button type="button" class="view-button btn btn-default btn-xs">
                                    <span class="glyphicon glyphicon-eye-open"></span> View
                                </button>

                                <button type="button" data-id="<?php echo (string) $item->{'product._id'} ?>" class="delete-button btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                            </td>
                        </tr>

                        <tr class="customerRow" style="display: none">
                            <td style="background-color: #ddd;"></td>
                            <td style="background-color: #ddd;"><strong>Customer(s):</strong></td>
                            <td style="background-color: #ddd;" colspan="4">
                                <div style="min-height: 64px">
                                    <?php foreach ($item['notifications'] as $notification) : ?>
                                        <a href="/admin/shop/notification/delete/<?php echo (string) $notification['_id'] ?>" class="btn btn-danger btn-xs" data-bootbox="confirm" style="margin: 4px 0">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                        <?php echo $notification['email']; ?>
                                        <?php if (!empty($notification['user']['id'])): ?>
                                            - <a href="/admin/shop/customer/read/<?php echo (string) $notification['user']['id'] ?>"><?php echo $notification['user']['name'] ?></a>
                                        <?php endif; ?>
                                        <br>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="panel-footer">
            <div class="row">
                <div class="col-sm-10">
                    <?php if (!empty($paginated->total_pages) && $paginated->total_pages > 1) { ?>
                        <?php echo $paginated->serve(); ?>
                    <?php } ?>
                </div>
                <div class="col-sm-2">
                    <div class="datatable-results-count pull-right">
                        <span class="pagination">
                            <?php echo (!empty($paginated->total_pages)) ? $paginated->getResultsCounter() : null; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    $('.view-button').click(function() {
        $('.customerRow').hide();
        $(this).closest('.mainRow').next('.customerRow').fadeIn();
    });

    $('.delete-button').click(function() {
        if (confirm('Are you sure?')) {
            var link = '/admin/shop/notifications/product/delete/' + $(this).data('id');

            if (confirm('Do you also want to send customer service tickets to follow up with each customer?')) {
                link = link + '?create-tickets=true';
            }

            window.location.href = link;
        }
    });
</script>
