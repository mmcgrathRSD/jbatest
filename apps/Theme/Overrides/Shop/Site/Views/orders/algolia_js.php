<?php $site_type = \Base::instance()->get('SITE_TYPE'); ?>
<?php if (!empty($this->app->get('algolia.read_api_key')) && !empty($this->app->get('algolia.app_id')) && $checkoutmode == 0) : ?>
    <script>
        var searchOrders = instantsearch({
            appId: '<?php echo $this->app->get('algolia.app_id'); ?>',
            apiKey: '<?php echo $this->app->get('algolia.read_api_key'); ?>',
            indexName: 'orders',
            searchParameters: {
                facets: [
                    'user_id'
                ],
                filters: 'user_id = <?php echo (int) $this->auth->getIdentity()->reload()->get('netsuite.internalId'); ?>'
            },
        });
        
        var item_template_orders = '<?php echo trim(preg_replace("/[\n\r]/","",$this->renderLayout('Shop\Site\Views::orders/algolia_list_item_temp.php')));?>';

        var facet_template_orders = '<?php echo $this->renderLayout('DealerPortalTheme/Views::common/algolia/selectable_facets.php');?>';
        <?php if ($site_type =='wholesale') : ?>
        searchOrders.addWidget(
            instantsearch.widgets.searchBox({
                container: '#orders_search_box',
                placeholder: 'Search Orders',
                wrapInput: false
            })
        );
        <?php endif; ?>
        searchOrders.addWidget(
            instantsearch.widgets.hits({
                container: '#orders_search_hits',
                templates: {
                    allItems: item_template_orders
                },
                escapeHTML: false,
                hitsPerPage: 10,
                transformData: {
                    item: function(hit) {


                        //prices to fixed stuff
                        if(hit.total) {
                            hit.total = hit.total.toFixed(2);
                        }
                        
                        if(hit.sub_total) {
                            hit.sub_total = hit.sub_total.toFixed(2);
                        }
                        
                         if(hit.tax_total) {
                            hit.tax_total = hit.tax_total.toFixed(2);
                        }
                        
                        if(hit.shipping_total) {
                            hit.shipping_total = hit.shipping_total.toFixed(2);
                        }
                        
                        if(hit.discount_total) {
                            hit.discount_total = hit.discount_total.toFixed(2);
                        }

                        //tracking number stuff
                        if(hit.tracking_numbers) {
                             $.each(hit.tracking_numbers, function(index, tracking_number) {
                                switch(tracking_number['provider']) {
                                    case 'ups':
                                        $(this)[0].link = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=' + tracking_number['number'];
                                        break;
                                    case 'fedex':
                                        $(this)[0].link = 'http://www.fedex.com/Tracking?action=track&tracknumbers=' + tracking_number['number'];
                                        break;
                                    case 'usps':
                                        $(this)[0].link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' + tracking_number['number'];
                                        break;
                                }
                            });   
                        }

                        //Invoice stuffs
                        //unfulfilled items stuffs tracking number stuff
                        
                        //Invoice stuffs
                        //invoice stuffs tracking number stuff
                        if(hit.invoices.length != 0) {
                            $.each(hit['invoices'], function(index, invoice) {

                                //time/date stuff
                                if(invoice.created_utc) {
                                    //get local time and save to algolia object
                                    var date = new Date(invoice.created_utc * 1000);
                                    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                                    $(this)[0].local_date = months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
                                }
                                
                                //pdf link
                                invoice.pdf_link = '/order/' + hit.number + '/generate-invoice/' + invoice.number;

                                if(invoice['tracking_numbers']) {
                                    $.each(invoice['tracking_numbers'], function(index, tracking_number) {
                                        switch(tracking_number['provider']) {
                                            case 'ups':
                                                $(this)[0].link = 'http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=' + tracking_number['number'];
                                                break;
                                            case 'fedex':
                                                $(this)[0].link = 'http://www.fedex.com/Tracking?action=track&tracknumbers=' + tracking_number['number'];
                                                break;
                                            case 'usps':
                                                $(this)[0].link = 'http://trkcnfrm1.smi.usps.com/PTSInternetWeb/InterLabelInquiry.do?origTrackNum=' + tracking_number['number'];
                                                break;
                                        }
                                    });
                                }

                                if('return_by' in invoice && invoice['return_by'] >= <?php echo time(); ?>) {
                                    $(this)[0].can_return = true;
                                }
                            });
                        } else {
                            hit.invoices_empty = true;
                        }


                        //time/date stuff
                        if(hit.created_utc) {
                           
                            //get local time and save to algolia object
                            var date = new Date(hit.created_utc * 1000);
                            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                            /*var meridiem_indicator = "AM";
                            var hour = date.getHours();
                            if (hour >= 12) {
                                hour = hour - 12;
                                meridiem_indicator = "PM";
                            }
                            if (hour == 0) {
                                hour = 12;
                            } */
                            hit.local_date = months[date.getMonth()] + ' ' + date.getDate() + ', ' + date.getFullYear();
                            
                        }

                        return hit;
                    },

                    empty: function(hit) {
                        return hit;
                    },

                    allItems: function(results) {
                        $.each(results.hits, function(key, value) {
                            var date = new Date(value.created_utc*1000);

                            results.hits[key].created_utc_converted = (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear();

                            results.hits[key].total_converted = currency_format.format(value.total);
                        });

                        return results;
                    }
                }
            })
        );
        <?php if ($site_type == 'wholesale') : ?>
        searchOrders.addWidget({
            getConfiguration: function () {
            },
            init: function (params) {
                var datepicker = $('#orders_datepicker_range').datepicker({
                    autoclose: true,
                    format: 'mm/dd/yyyy',
                    todayHighlight: true,
                    weekStart: 1,
                    language: 'en',
                    disableKeyboard: true,
                    endDate: 'today'
                });
                var datepicker1 = $('#orders_datepicker_1');
                var datepicker2 = $('#orders_datepicker_2');
                var date = new Date();
                $(document).on("change", "#orders_datepicker_1, #orders_datepicker_2", function() {
                    var date1 = datepicker1.datepicker('getDate');
                    var date2 = datepicker2.datepicker('getDate');
                    params.helper.removeNumericRefinement('created_utc');
                    if(date1) {
                        params.helper.addNumericRefinement('created_utc', '>=', moment(date1).unix());
                    }

                    if(date2) {
                        params.helper.addNumericRefinement('created_utc', '<=', (moment(date2).unix() + 86400));
                    }

                    params.helper.search();
                });
            },
            render: function (params) {}
        });
    
        searchOrders.addWidget(
            instantsearch.widgets.sortBySelector({
                container: '#orders_search_sort',
                indices: [
                    {name: 'orders', label: 'Newest to Oldest'},
                    {name: 'orders-date-asc', label: 'Oldest to Newest'}
                ]
            })
        );
        
        searchOrders.addWidget(
            instantsearch.widgets.refinementList({
                container: '#orders_filter_status',
                attributeName: 'status',
                operator: 'or'
            })
        );

        searchOrders.addWidget(
            instantsearch.widgets.refinementList({
                container: '#orders_filter_tracking',
                attributeName: 'tracking_numbers_generated',
                operator: 'or',
                transformData: {
                    item: function (hit) {
                        if(hit.name == 'true') {
                            hit.highlighted = 'Yes';
                        } else if(hit.name == 'false') {
                            hit.highlighted = 'No';
                        }
                        return hit;
                    }
                }
            })
        );

       
        <?php endif; ?>
        
         searchOrders.addWidget(
            instantsearch.widgets.pagination({
                container: '#orders_pagination',
            })
        );
        
        searchOrders.start();
        
        $(document).on('click', '.order_show_items', function(e) {
            e.preventDefault();
            $(this).closest('.order_items_all').toggleClass('orders_expanded').find('.order_items').slideToggle();
        });
    </script>
<?php endif; ?>
