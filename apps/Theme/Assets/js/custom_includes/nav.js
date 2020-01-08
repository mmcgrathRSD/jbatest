//Mobile Nav JS

$('.navMaster').ready(function() {
    $('#slideOut, .bigButtonNav').click(function() {
        body = $('body');
        body.toggleClass('slideOut');
        $('.topNav a').toggleClass('sliden');
        if(!body.hasClass('slideOut')) {
            body.addClass('slideIn');
            $('.navMaster').removeClass('sliden');
            $('.navSlave').removeClass('eclipse');
            $('.goBack').hide();
        }
        else {
            body.removeClass('slideIn');
            $('.navSlave').css('display', '');
        }
        $('.slideOut .topNav a').click(function(e) {
            if($('body').hasClass('slideOut') && $(this).attr('id') != 'navPhone') {
                e.preventDefault();
                $('.navSlave[data-id='+$(this).find('li').attr('data-id')+']').addClass('eclipse');
                $('.navMaster').addClass('sliden');
                $('.goBack').show();
            }
        });
        $('.slideOut .navSlave ul li:first-of-type').click(function() {
            $('.chosenOne').removeClass('chosenOne');
            $('.sliden .glyphicon-chevron-down').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-right');
            $(this).closest('ul').addClass('chosenOne');
            $(this).find('i').removeClass('glyphicon-chevron-right').addClass('glyphicon-chevron-down');
        });
        $('.slideOut .navSlave ul li:first-of-type a').click(function(e) {
            if($(this).closest('ul').hasClass('chosenOne')) {
                $(this).css('color', 'rgb(18, 135, 255)');
            }
            else {
                e.preventDefault();
            }
        });
        $('.slideOut .accountName').click(function(e) {
            e.preventDefault();
            $(this).mouseenter();
        });
    });

    $('.goBack').click(function() {
        $('.navSlave').removeClass('eclipse');
        $('.navMaster').removeClass('sliden');
        $('.goBack').hide();
    });

    //Hover intent/drawer delay (desktop nav)
    var alive = false;
    var timeout;
    var timeout2;
    var navSelected;
    var expired_package_deals_impressions = [];
    $(".navSlave, .topNav a").on( {
        'mouseenter':function() {
            if(!$('body').hasClass('slideOut')) {
                clearTimeout(timeout);
                navSelected = $(this).attr('data-id');
                navText = $(this).text();
                isTopNav = (!$(this).hasClass('navSlave'))

                alive = true;
                timeout = setTimeout(function() {
                    $('.navSlave').each(function() {
                        if($(this).attr('data-id') != navSelected) {
                            $('div[data-id='+$(this).attr('data-id')+']').hide();
                        }
                    });
                    if(alive) {
                        $('div[data-id='+navSelected+']').show();

                        if(!expired_package_deals_impressions.includes(navText) && $('div[data-id='+navSelected+']').find('.packageDealsDiv').length && isTopNav) {

                            ga('ec:addPromo', {               // Promo details provided in a promoFieldObject.
                              'id': 'Package Deals Banner ' + navText,             // Promotion ID. Required (string).
                              'name': 'Package Deals Banner ' + navText,          // Promotion name (string).
                              'creative': $('div[data-id='+navSelected+']').find('.packageDealsDiv img').attr('src'),   // Creative (string).
                            });

                            ga('send', 'event', 'Internal Promotions', 'impression', 'Package Deals Banner ' + navText);

                            expired_package_deals_impressions.push(navText);
                        }
                    }
                }, 300);
            }
        },
        'mouseleave':function() {
            if(!$('body').hasClass('slideOut')) {
                alive = false;
                clearTimeout(timeout2);
                timeout2 = setTimeout(function(){
                    if(!alive) {
                        $('.navSlave').hide();
                    }
                }, 400);
            }
        }
    });

        // YMM selection

    $('.toggle-ymm, .selectCarBtn').click( function (event) {
        event.preventDefault();
        $('#ymmModal').toggle();
             ga('send', 'event', 'click',  'Popup YMM Selector');
             setTimeout(function (){
                    $('#slcYear').focus();
            }, 550);
    });

    $('.packageDealsDiv .packageDeals').click(function(e) {
        navText = $('li[data-id="' + $(this).closest('.navSlave').data('id') + '"]').text();

        ga('ec:addPromo', {               // Promo details provided in a promoFieldObject.
          'id': 'Package Deals Banner ' + navText,             // Promotion ID. Required (string).
          'name': 'Package Deals Banner ' + navText,          // Promotion name (string).
          'creative': $(this).find('img').attr('src'),   // Creative (string).
        });

        ga('ec:setAction', 'promo_click');
        ga('send', 'event', 'Internal Promotions', 'click', 'Package Deals Banner ' + navText);
    });
});
