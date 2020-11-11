<?php if (!empty($this->app->get('algolia.read_api_key')) && !empty($this->app->get('algolia.app_id')) && $checkoutmode == 0) : ?>
<?php
$activeVehicle = $this->session->get('activeVehicle');
$identity = \Dsc\System::instance()->get('auth')->getIdentity();
//later we'll send the auth'd manufacturers to a json object

$dns_auth = $identity->getDNSBrands();

if(!empty($item)) {
    $type = $item->type();
}

$car = $this->session->get('activeVehicle');

$clear_all_exclusions = '';


?>

<script>
    var instances = [];
    var filters = setFilters('<?php if(!empty($activeVehicle)) { echo $activeVehicle['hash']; } ?>');
	var sticky_state = true;
	var dns_auth = null;

    var item_template = '<?php echo trim(preg_replace("/[\n\r]/","",$this->renderLayout('Search/Site/Views::search/algolia_list_item_temp.php')));?>';

    //before building the algolia functions going to get active vehicle and dns filters for product searches
    function setFilters(hash) {
        filters = 'sales_channels: <?php echo \Base::instance()->get('sales_channel'); ?> AND hasPrices: true AND hasData: true';

        if(hash) {
            filters += ' AND ymm_hashs: ' + hash + ' OR universal_item: true';
        }

        filters += ' AND NOT image = 0 AND default_price > 0 AND NOT product_type: Subitem';

        <?php if(!empty($dns_auth) && \Base::instance()->get('SITE_TYPE') == 'wholesale') : ?>
        var dns_auth = <?php echo json_encode($dns_auth); ?>;
        if(dns_auth) {
            filters ? filters += ' AND ' : '';
            $.each(dns_auth, function(key, val) {
                key != 0 ? filters += 'AND ' : '';
                filters += 'NOT netsuite.brand_name_internal_id:"' + val + '" ';
            });
        }
        <?php endif; ?>

        return filters;
    }

	function stickyEval(elem, row, add) {
        if(typeof(jQuery('.sticky_parent').hcSticky) == 'function') {
            if((elem.outerHeight(true) + add) >= row.outerHeight(true) && elem.outerHeight(true) != 0 && row.outerHeight(true) != 0) {
                jQuery('.sticky_parent').hcSticky('off');
                sticky_state = false;
            } else {
                if(!sticky_state) {
                    jQuery('.sticky_parent').hcSticky('on');
                    sticky_state = true;
                }
                //

                if(elem.offset().top + elem.outerHeight(true) + add > row.offset().top + row.outerHeight(true)) {
                    elem.css('top', (parseInt(elem.css('top'), 10) - 10 - add) + 'px');
                }
            }
        }
	}

    function algoliaProductInstance(
        facets,
        facet_refinements,
        hierarchical_facet_refinements,
        hit_count,
        hit_container,
        empty_template,
        url_sync,
        instance_id,
        use_widgets
    ) {
            if(instance_id && url_sync) {
                url_hash = true;
            } else {
                url_hash = false;
            }

            if(instance_id) {
                search_placeholder = 'Search within these results...';
            } else {
                search_placeholder = '';
            }

			ymm_instance = false;
			<?php if($type == 'shop.yearmakemodels') : ?>
			if(instance_id) {
				ymm_instance = false;
            }
            type = 'yearmakemodels';
            <?php endif; ?>
            
            <?php if($type == 'shop.manufacturers') : ?>
            type = 'manufacturers';
            <?php endif; ?>

            var index = 'products';

            if(!instance_id) {
                instance_id = '';
            } else {
                <?php if(!empty($item)) : ?>
                index = '<?php echo ($item->type() == 'shop.collections' && !empty($item->sort_by)) ? $item->sort_by : 'products'; ?>';
                <?php endif; ?>
            }
			var search = instantsearch({
				appId: '<?php echo $this->app->get('algolia.app_id'); ?>',
				apiKey: '<?php echo $this->app->get('algolia.read_api_key'); ?>',
                indexName: index,
				searchParameters: {
                    disjunctiveFacets: ['ymm_hashs, universal_item'],
                    filters: filters,
					hitsPerPage: 12,
					facets: facets,
					facetsRefinements: facet_refinements,
					<?php if($type == 'shop.categories' || !empty($hierarchical_refinement)) : ?>
					hierarchicalFacets: 'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl0',
					hierarchicalFacetsRefinements: hierarchical_facet_refinements,
					<?php endif; ?>
				},
				urlSync: {
					useHash: url_hash
				}
			});




			if(instance_id && (!url_hash || !url_sync)) {
                search.urlSync = false;
            }

        search.addWidget(
            instantsearch.widgets.hits({
                container: hit_container,
                templates: {
                    empty: empty_template,
                    allItems: item_template
                },
                hitsPerPage: 12,
                cssClasses: {
                },
                transformData: {
                    empty: function(empty) {
                        //jQuery('#hits_container' + instance_id).hide();
                        //jQuery('#empty-container' + instance_id + ', #empty_clear_all' + instance_id).show();
                        //jQuery('.alg_noresults_page .empty_template_query').show().html(empty.query);
                        if(empty.query.length > 0) {
                            jQuery('.empty_template_query').show().html('<h4>Search query: <strong>' + empty.query + '</strong></h4>');
                        } else {
                            jQuery('.empty_template_query').hide().html('');
                        }

                        jQuery('.ais-current-refined-values--link').removeClass('kitReturnFalse');
                        jQuery('.ais-current-refined-values--link').addClass('kitReturnFalse');

                        jQuery('click', 'em', function() {
                            console.log(jQuery(this));
                        });

                        return empty;
                    },
                    allItems: function(allItems) {
                        $.each(allItems.hits, function(key, hit) {
                            
                            if(hit.image) {
                                hit.image = cl.imageTag(hit.image, {secure: true, sign_url: true, type: "private", transformation: '<?php echo \Base::instance()->get('cloudinary.category_limit_v1'); ?>', alt: hit.title, title: hit.title, style: "opacity: 1;", class: "regular_image"}).toHtml();
                            } else {
                                hit.image = cl.imageTag("<?php echo \Base::instance()->get('cloudinary.no_photo'); ?>", {secure: true, type: "upload", transformation: '<?php echo \Base::instance()->get('cloudinary.category_limit_v1'); ?>', alt: hit.title, title: hit.title, style: "opacity: 1;", class: "regular_image"}).toHtml();
                            }

                            if(hit.image_2) {
                                hit.image_2 = cl.imageTag(hit.image_2, {secure: true, sign_url: true, type: "private", transformation: '<?php echo \Base::instance()->get('cloudinary.category_limit_v1'); ?>', alt: hit.title, title: hit.title, style: "opacity: 0; display: block", class: "additional_img"}).toHtml();
                            }

                            //generating cloudinary urls
                            if('swatches' in hit && !(hit.swatches instanceof Array)) {
                                let new_swatches = [];

                                $.each(hit.swatches, function(key, swatch) {
                                    new_swatch_values = [];

                                    $.each(swatch, function(value_key, value) {
                                        new_swatch_values.push(
                                            {
                                                "key": value_key,
                                                "value": cl.imageTag(value, {secure: true, sign_url: true, type: value.indexOf('product_images') == 0 ? 'private' : 'upload', transformation: '<?php echo \Base::instance()->get('cloudinary.swatch'); ?>', class: "amconf-image", alt: value_key, title: value_key}).toHtml()
                                            }
                                        );
                                    });

                                    new_swatches.push(
                                        {
                                            "key": key,
                                            "value": new_swatch_values
                                        }
                                    );

                                    hit.swatches = new_swatches;
                                });
                            } else {
                                delete hit.swatches;
                                
                                if(hit.product_type == 'Matrix') {
                                    hit.options_available = true;
                                }
                            }

                            if('previous_default_price' in hit && hit.previous_default_price && hit.previous_default_price > hit.default_price) {
                                hit.previous_default_price = currency_format.format(hit.previous_default_price);
                            } else {
                                delete hit.previous_default_price;
                            }

                            hit.default_price = currency_format.format(hit.default_price);
                        });

                        return allItems;
                    }
                }
            })
        );

       if(use_widgets) {


           search.addWidget(
                instantsearch.widgets.searchBox({
                    container: '#search-box' + instance_id,
                    placeholder: search_placeholder,
					reset: false,
                    magnifier: true,
                    autofocus: false
                })
            );

            if(!instance_id.length) {
                search.addWidget(
                    instantsearch.widgets.searchBox({
                        container: '#search-box-mobile',
                        placeholder: 'Search...',
                        wrapInput: false,
						reset: false,
						magnifier: false,
                    })
                );

				if(save_location.pathname != '/search') {
					search.addWidget(
						instantsearch.widgets.clearAll({
							container: '#search_clear_all',
							templates: {
								link: 'X Close Search'
							},
							cssClasses: {
								link: 'btn btn-default'
							},
							autoHideContainer: false,
                            clearsQuery: true
						})
					);
				}


            }

            <?php foreach((array) $this->app->get('product_specs') as $key => $spec) : ?>
            <?php if($spec['hidden'] != 'on') : ?>
            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#search_filter_<?php echo str_replace(' ', '', preg_replace("/[^A-Za-z0-9 ]/", '', $key)); ?>' + instance_id,
                    attributeName: 'specs.<?php echo $key; ?>',
                    templates: {
                        header: '<div class="block-title"><strong><span><div><em class="toggle toggle-plus"></em><div class="new_toggle"></div><a href="#" class="collapsible_facet_header_link"><span><?php echo $key; ?></span></a></div></span></strong></div>',
                        item: '<?php echo trim(preg_replace("/[\n\r]/","",$this->renderLayout('Search/Site/Views::search/refinement_item_template.php')));?>'
                    },
                    cssClasses: {
                        root: ''
                    },
                    collapsible: {
                        collapsed: true
                    },
                    sortBy: function(a, b) {
                        return alphanum(a.name, b.name);
                    },
                    limit: 10,
					showMore: true
                })
            );
            <?php endif; ?>
            <?php endforeach; ?>

		   //lets make some links if stuffsikins needs it
		   var additional_links = '';
		   <?php if(!empty($car) && $type != 'shop.yearmakemodels') : ?>
			additional_links += '<div class="ais-current-refined-values--item search_current_ymm"><a class="ais-current-refined-values--link clearAlgoliaYmm" href="#"><div><div class=""><?php echo \Dsc\ArrayHelper::get($car, 'vehicle_year') . ' ' . \Dsc\ArrayHelper::get($car, 'vehicle_make') . ' ' . \Dsc\ArrayHelper::get($car, 'vehicle_model') . ' ' . \Dsc\ArrayHelper::get($car, 'vehicle_sub_model'); ?><span href="#" class="close-thin"></span></div></div></a></div>';
			<?php endif; ?>

		   <?php if(!empty($custom_filter_objects)) : ?>
		   if(instance_id.length) {
			   <?php foreach($custom_filter_objects as $key => $custom_filter_object) : ?>
			   additional_links += '<div class="ais-current-refined-values--item"><a class="ais-current-refined-values--link" href="<?php echo $custom_filter_object; ?>"><div><div class=""><?php echo $key; ?><span href="#" class="close-thin"></span></div></div></a></div>';
			   <?php endforeach; ?>
		   }
		   <?php endif; ?>

           search.addWidget(
               instantsearch.widgets.currentRefinedValues({
                   container: '#search_selected' + instance_id,
                   clearAll: false,
                   autoHideContainer: false,
                   templates: {
                       header: additional_links,
                       item: '<div class="{{dynamicClass}}">{{attributeName}}{{name}}<a href="#" class="close-thin"></a></div>'
                   },
                   transformData: function (item) {
                       if(item.attributeName.slice(0, 5) == 'specs') {
                           item.attributeName = item.attributeName.slice(6) + ': ';
                       } else if(item.attributeName == "Rating") {
                           item.attributeName = 'Rating: ';
                           if(item.name == "5") {
                               item.name = "5 & Up";
                           }
                       } else if(item.attributeName == "default_price") {
                           item.attributeName = "Price: ";
                           if(item.operator == ">=") {
                               item.name = '$' + item.name + '+';
                           } else if(item.operator == "<=") {
                               item.name = '-$' + item.name;
                           } else {
                               item.name = '$' + item.name;
                           }
                       } else if(item.attributeName == 'universal_item') {
                           item.attributeName = 'Hide universal parts';
                           item.name = '';
                           item.dynamicClass = 'hidden';
                       } else {
                           item.attributeName = '';
                       }

                       if(instance_id.length) {
                           if(item.name == '<?php echo addslashes(\Dsc\ArrayHelper::get($item, 'title'));  ?>' || item.name == '<?php echo \Dsc\ArrayHelper::get($item, 'slug'); ?>'<?php if(!empty($hierarchical_refinement)) : ?> || (item.type == 'hierarchical')<?php endif; ?>) {
                               item.dynamicClass = 'hidden';
                           }
                       }

                       return(item);
                   }
               })
           );

           search.addWidget(
               instantsearch.widgets.currentRefinedValues({
                   container: '#empty-container' + instance_id + ' .empty_template_parameters',
                   clearAll: false,
                   autoHideContainer: false,
                   cssClasses: {
                       link: 'kitReturnFalse'
                   },
                   templates: {
                       header: additional_links,
                       item: '<div class="{{dynamicClass}}">{{attributeName}}{{name}}<a href="#" class="close-thin"></a></div>'
                   },
                   transformData: function (item) {
                       if(item.attributeName.slice(0, 5) == 'specs') {
                           item.attributeName = item.attributeName.slice(6) + ': ';
                       } else if(item.attributeName == "Rating") {
                           item.attributeName = 'Rating: ';
                           if(item.name == "5") {
                               item.name = "5 & Up";
                           }
                       } else if(item.attributeName == "default_price") {
                           item.attributeName = "Price: ";
                           if(item.operator == ">=") {
                               item.name = '$' + item.name + '+';
                           } else if(item.operator == "<=") {
                               item.name = '-$' + item.name;
                           } else {
                               item.name = '$' + item.name;
                           }
                       } else if(item.attributeName == 'universal_item') {
                           item.attributeName = 'Hide universal parts';
                           item.name = '';
                           item.dynamicClass = 'hidden';
                       } else {
                           item.attributeName = '';
                       }

                       if(instance_id.length) {
                           if(item.name == '<?php echo addslashes(\Dsc\ArrayHelper::get($item, 'title'));  ?>' || item.name == '<?php echo \Dsc\ArrayHelper::get($item, 'slug'); ?>'<?php if(!empty($hierarchical_refinement)) : ?> || (item.type == 'hierarchical')<?php endif; ?>) {
                               item.dynamicClass = 'hidden';
                           }
                       }

                       return(item);
                   }
               })
           );

            clear_exclusions = [
                '<?php echo $clear_all_exclusions; ?>'
                ];
            
            search.addWidget(
                instantsearch.widgets.clearAll({
                    container: '#empty_clear_all' + instance_id,
                    excludeAttributes: clear_exclusions,
                    templates: {
                        link: 'Clear all filters'
                    },
                    cssClasses: {
                        link: 'btn btn-default'
                    },
                    autoHideContainer: false,
                    clearsQuery: true
                })
            );

            search.addWidget(
                instantsearch.widgets.stats({
                  container: '#search_stats' + instance_id,
                  templates: {
                      body: function(hit) {
                          starting_hit = ((hit.hitsPerPage * (hit.page)) + 1);
                          possible_current_hits = ((starting_hit - 1) + hit.hitsPerPage);
                          end_hit = hit.nbHits > possible_current_hits ? possible_current_hits : hit.nbHits;

                          return ('Items ' + starting_hit + ' to ' + end_hit + ' of ' + hit.nbHits + ' total');
                      }
                  }
                })
              );

              search.addWidget(
                instantsearch.widgets.stats({
                  container: '#search2_stats' + instance_id,
                  templates: {
                      body: function(hit) {
                          starting_hit = ((hit.hitsPerPage * (hit.page)) + 1);
                          possible_current_hits = ((starting_hit - 1) + hit.hitsPerPage);
                          end_hit = hit.nbHits > possible_current_hits ? possible_current_hits : hit.nbHits;
                          
                          return ('Items ' + starting_hit + ' to ' + end_hit + ' of ' + hit.nbHits + ' total');
                      }
                  }
                })
              );


		   <?php if(!$car) : ?>
		   //jQuery('#search_universal').hide();
		   <?php endif; ?>

		//    search.addWidget(
		// 	  instantsearch.widgets.toggle({
		// 		container: '#search_universal' + instance_id,
		// 		attributeName: 'universal_item',
		// 		label: 'Show universal parts',
		// 		values: {
		// 			on: false
		// 		}
		// 	  })
		// 	);


            search.addWidget(
                instantsearch.widgets.hierarchicalMenu({
                    container: '#search_filter_categories' + instance_id,
                    attributes: [
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl0',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl1',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl2',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl3',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl4',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl5',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl6',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl7',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl8',
                        'hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl9'
                    ],
                    limit: 100,
                    templates: {
                        item: '<?php echo trim(preg_replace("/[\n\r]/","",$this->renderLayout('Search/Site/Views::search/hierarchy_item_template.php')));?>'
                    },
                    cssClasses: {
                        active: 'active'
                    },
                    transformData: {
                        item: function (hit) {

                            if(instance_id && type != 'manufacturers') {
                                let algolia_hierarchy = search.helper.state.hierarchicalFacetsRefinements["hierarchicalCategories<?php echo filter_var(\Base::instance()->get('.algolia.categories_by_channel'), FILTER_VALIDATE_BOOLEAN) ? '.' . \Base::instance()->get('sales_channel') . '.' : '.'; ?>lvl0"][0];

                                if(algolia_hierarchy == hit.value) {
                                    $.when($.post( "/category/description", { crumb: hit.value }, function(data) {
                                        if(!data.error) {
                                            if(data.result.h1) {
                                                h1 = data.result.h1;
                                            } else {
                                                h1 = data.result.title + ' Parts';
                                            }

                                            $('div[data-instance-id="search' + instance_id + '"] .category-title h1').html(h1);

                                            $('div[data-instance-id="search' + instance_id + '"] .category-title h2.manual_h2').html(data.result.h2);

                                            $('div[data-instance-id="search' + instance_id + '"] .ais-hits a').each(function(hit, key) {
                                                str = String($(this).attr('href')).split("/");
                                                $(this).attr('href', '/part' + data.result.crumbs[Object.keys(data.result.crumbs).pop()] + '/' + str[str.length - 1]);
                                            });
                                            $('div[data-instance-id="search' + instance_id + '"] .category-description.std').html(data.result.html);

                                            $('div[data-instance-id="search' + instance_id + '"] ul.category-children').html('');

                                            if(data.result.children) {
                                                

                                                data.result.children.each(function(child) {
                                                    if(child.image) {
                                                        $('div[data-instance-id="search' + instance_id + '"] ul.category-children').append('<li><a href="' + child.link + '"><img src="' + child.image + '" alt="' + child.title + '" height="60" width="175" data-algolia-hierarchy="' + child.hierarchical_category + '"></a></li>');
                                                    }
                                                    
                                                });
                                            }

                                            $('title').html(data.result.seo_title);

                                            //update rating title
                                            $('div[data-instance-id="search' + instance_id + '"] #category-rating-title > strong > span').html(hit.label);
                                            
                                            $('.ratings').remove();

                                            let last_crumb = data.result.crumbs[Object.keys(data.result.crumbs).pop()]

                                            $('div[data-instance-id="search' + instance_id + '"] .breadcrumbs ul').html('<li style="display: inline-block;" typeof="v:Breadcrumb"> <a href="/" title="Home" rel="v:url" property="v:title">Home</a>&nbsp;</li>');

                                            $.each(data.result.crumbs, function (key, crumb) {
                                                if(crumb != last_crumb) {
                                                    $('div[data-instance-id="search' + instance_id + '"] .breadcrumbs ul').append('<li style="display: inline-block;" typeof="v:Breadcrumb"> <span>/</span> <a href="/scp' + crumb + '" title="' + key + '" rel="v:url" property="v:title">' + key + '</a>&nbsp;</li>');
                                                } else {
                                                    $('div[data-instance-id="search' + instance_id + '"] .breadcrumbs ul').append('<li style="display: inline-block;"> <span>/</span> <strong>' + key + '</strong> </li>');
                                                }

                                            });

                                        } else {
                                            $('div[data-instance-id="search' + instance_id + '"] .category-title h1').html(hit.label + ' Parts');
                                            $('div[data-instance-id="search' + instance_id + '"] .category-title h2.manual_h2').html('');

                                            $('div[data-instance-id="search' + instance_id + '"] .breadcrumbs ul').html('<li style="display: inline-block;" typeof="v:Breadcrumb"> <a href="/" title="Home" rel="v:url" property="v:title">Home</a>&nbsp;</li>');

                                            $('div[data-instance-id="search' + instance_id + '"] .breadcrumbs ul').append('<li style="display: inline-block;"> <span>/</span> <strong>' + hit.label + '</strong> </li>');

                                            $('div[data-instance-id="search' + instance_id + '"] .category-description.std').html('');
                                            $('div[data-instance-id="search' + instance_id + '"] ul.category-children').html('');
                                        }
                                        

                                    })).then(function() {
                                        $('.category_dynamic_head').css('height', 'auto').removeClass('category_dynamic_head_loader').delay(800).fadeIn(400);
                                    });
                                }
                            }

                            return hit;
                        }
                    }
                })
            );

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#search_filter_brand' + instance_id,
                    attributeName: 'Brand',
                    operator: 'or',
                    limit: 15,
                    templates: {
                        item: '<?php echo trim(preg_replace("/[\n\r]/","",$this->renderLayout('Search/Site/Views::search/refinement_item_template.php')));?>'
                    },
                    searchForFacetValues: true,
                    searchForFacetValues: {
                        placeholder: 'Search Brands',
                        isAlwaysActive: false,
                        templates: {
                            noResults: 'No brands found'
                        }
                    },
                    sortBy: ['name:asc'],
					showMore: true
                })
            );

            <?php if(\Base::instance()->get('SITE_TYPE') != 'wholesale') : ?>

            search.addWidget(
                instantsearch.widgets.sortBySelector({
                    container: '#search_filter_sort' + instance_id,
                    indices: [
                        {name: 'products', label: 'Newest Products'},
                        {name: 'products-date-newest-asc', label: 'Oldest Products'},
                        {name: 'products-price-asc', label: 'Price Lowest'},
                        {name: 'products-price-desc', label: 'Price Highest'},
                        {name: 'products-date-newest-desc', label: 'Most Popular'},
                        {name: 'products-reviews', label: 'Reviews'}
                    ],
					template: '',
                    cssClasses: {
                        select: 'basicNRSelect'
                    }
                })
            );

            search.addWidget(
                instantsearch.widgets.starRating({
                    container: '#search_filter_rating' + instance_id,
                    attributeName: 'Rating',
                    autoHideContainer: true
                })
            );

            /*search.addWidget(
                instantsearch.widgets.priceRanges({
                    container: '#search_filter_price' + instance_id,
                    attributeName: 'default_price',
                    labels: {
                        currency: '$',
                        separator: 'to',
                        button: 'Go'
                    }
                })
            );*/

            // search.addWidget(
            //   instantsearch.widgets.rangeSlider({
            //     container: '#search_filter_price' + instance_id,
            //     attributeName: 'default_price',
            //     tooltips: {
            //       format: function(rawValue) {
            //         return '$' + Math.round(rawValue).toLocaleString();
            //       }
            //     }
            //   })
            // );

            <?php else : ?>

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#search_filter_status' + instance_id,
                    attributeName: 'status.<?php echo \Base::instance()->get('sales_channel'); ?>.title',
                    operator: 'or'
                })
            );

            search.addWidget(
                instantsearch.widgets.refinementList({
                    container: '#search_filter_promotions' + instance_id,
                    attributeName: 'promotions.<?php echo \Base::instance()->get('sales_channel'); ?>.title',
                    operator: 'or'
                })
            );

            <?php endif; ?>

            search.addWidget(
                instantsearch.widgets.pagination({
                    container: '#pagination-container' + instance_id
                })
            );

            search.addWidget(
                instantsearch.widgets.pagination({
                    container: '#pagination2-container' + instance_id
                })
            );
       }

        return search;
    }

	jQuery(document).on('click', '.ais-root__collapsed', function() {
		elem = jQuery(this).closest('.sticky_parent');
		add = jQuery(this).find('.ais-body').outerHeight();
		row = elem.closest('.row');
		stickyEval(elem, row, add);
	});

	jQuery(document).on('click', '.ais-show-more.ais-show-more__inactive', function() {
		jQuery(this).closest('.ais-refinement-list--list').addClass('search_scrollable_active');
		try {
		    jQuery('.sticky_parent').hcSticky('reinit');
	    } catch(err) {
  	        <?php if($DEBUG) : ?>
      			console.error(err);
      		<?php endif; ?>
        }
	});

	jQuery(document).on('click', '.ais-show-more.ais-show-more__active', function() {
		jQuery(this).closest('.ais-refinement-list--list').removeClass('search_scrollable_active');
		try {
		    jQuery('.sticky_parent').hcSticky('reinit');
	    } catch(err) {
  	        <?php if($DEBUG) : ?>
      			console.error(err);
      		<?php endif; ?>
        }
	});
	
	jQuery(document).on('click', '.ais-clear-all--link:not(#search_clear_all ais-clear-all--link)', function(e) {
		hash = false;
		<?php if ($type != 'shop.yearmakemodels') : ?>
        filters_active_vehicle = '';
        <?php else : 
        $activeVehicle = $this->session->get('activeVehicle'); ?>
        hash = '<?php if(!empty($activeVehicle)) { echo $activeVehicle['hash']; } ?>';
        <?php endif; ?>

        jQuery('.search_current_ymm').remove();
        jQuery('.search_universal_container').hide();
        $.each(instances, function(key, instance) {
            filters = setFilters(hash);
            
            
            <?php if ($type == 'shop.categories') : ?>
            	window[instance].helper.clearRefinements('universal_item').setQueryParameter('filters', filters).search();
            	
            <?php else : ?>
            	window[instance].helper.clearRefinements('universal_item').setQueryParameter('filters', filters).addFacetRefinement('<?php echo $facet; ?>', '<?php echo $refinement; ?>').search();
            <?php endif; ?>
            
        });
    });

    $(document).on('click', '.sort-by-wrap .sort-option', function(e) {
        e.preventDefault();

        jba_instance = $(this).closest('.main.row.search_container').attr('data-instance-id');

        var sort_index = $(this).parent().attr('data');
        window[jba_instance].helper.setIndex(sort_index).search();

        $('.sort-by-wrap li').removeClass('selected');
        $('.sort-by-wrap li[data="' + sort_index + '"]').addClass('selected');
        $('.sort-by-wrap span.current').html($(this).html()).attr('data-opposite', $(this).attr('data-opposite')).attr('data-current', sort_index);
    });

    $(document).on('click', '.sort-by-arrow', function(e) {
        e.preventDefault();

        jba_instance = $(this).closest('.main.row.search_container').attr('data-instance-id');
        
        var sort_index = $('.sort-by-wrap span.current').attr('data-current');
        var sort_opposite = $('.sort-by-wrap span.current').attr('data-opposite');
        window[jba_instance].helper.setIndex(sort_opposite).search();

        $('.sort-by-wrap li').removeClass('selected');
        $('.sort-by-wrap li[data="' + sort_opposite + '"]').addClass('selected');
        $('.sort-by-wrap span.current').html($('.sort-by-wrap li[data="' + sort_opposite + '"] a').html()).attr('data-opposite', $('.sort-by-wrap li[data="' + sort_opposite + '"] a').attr('data-opposite'));

        var sort_img = $(this).find('img');
        if(sort_img.hasClass('i_desc_arrow')) {
            $('.i_desc_arrow').removeClass('i_desc_arrow').addClass('i_asc_arrow').attr('src', '/theme/img/jba/i_asc_arrow.png');
        } else {
            $('.i_asc_arrow').removeClass('i_asc_arrow').addClass('i_desc_arrow').attr('src', '/theme/img/jba/i_desc_arrow.png');
        }
    });

    $(document).on('click', '.limiter .toolbar-dropdown a', function(e) {
        e.preventDefault();

        jba_instance = $(this).closest('.main.row.search_container').attr('data-instance-id');

        var hit_count = $(this).html();
        window[jba_instance].helper.setQueryParameter('hitsPerPage', hit_count).search();

        $('.limiter li').removeClass('selected');
        $('.limiter li[data="' + hit_count + '"]').addClass('selected');
        $('.limiter .current').html(hit_count);
    });

    $(document).on('click', '.collapsible_facet_header_link', function(e) {
        e.preventDefault();
    })

    $(document).on('submit', '.searchautocomplete', function(e) { 
        e.preventDefault(); 
        
        showAlgolia();

        $('html, body').animate({
            scrollTop: $("#algolia_master").offset().top
        }, 500);
    });

    $(document).on('change', '#header_ymm_search_select', function() {
        hierarchy = $('option:selected',this).attr('data-hierarchy');

        setAlgoliaCategory(search, hierarchy);

        if(!hierarchy) {
            $('#header_ymm_search_span').html('All');
        } else {
            $('#header_ymm_search_span').html(hierarchy);
        }

        $('#header_ymm_search_input_wrapper').css('padding-left', $('#header_ymm_search_select_wrapper').width());
        $('#header_ymm_search_select').width($('#header_ymm_search_select_wrapper').width());
    });
</script>
<?php endif; ?>