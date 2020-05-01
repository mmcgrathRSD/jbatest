function StyleRichRel(){
	var richThumbCount = $('#richSlider .richItem').length;
	//convert ratings to icons
	RichRelevanceRatings();
	//Determine Sizing
	sliderSize();


	function RichRelevanceRatings(slideNum,direction){
		$('.richItemRating').each(function(i, obj) {
			var stars=$(this).attr('class').split('-');
			for(var z = 0; z < stars[1]; z++) {
				$(this).append( '<i class="fa fa-star star-ratings"></i>' );
			}
			for(var EmptyStar = 0+parseFloat(stars[1]); EmptyStar < 5; EmptyStar++) {
				$(this).append( '<i class="fa fa-star-o star-ratings"></i>' );
			}
		});
	}


	//Determine the size of the slider, should also be able to run on screen resize
	function sliderSize(){
		var bodyWidth =  $('#richWrapper').width();
		var template = $('#richWrapper').attr('arrow-position');

		var richThumbWidth = ($('#richSlider .richItem:first').outerWidth( true ));
		var thumbsVisible = Math.floor(bodyWidth/richThumbWidth);
		$('#richSlider').css('width' , (richThumbWidth*richThumbCount));

		//size the container. we need to check for which template is being used
		$('#richContainer').css('width',(thumbsVisible*richThumbWidth));
		if(template=='side'){
		var positioning = (bodyWidth-(thumbsVisible*richThumbWidth))/2;
			$('#richSlider').css('margin-left',0);
			$('#richContainer').css('left',positioning);

		}

	}


	//function for arrow clicks
	 function arrowClicks(direction){
        if(direction=='right'){
            $('#richSlider').css('margin-left' ,  '-532');
			var firstItem = $('.richItem:first').html();
			var firstItemClass = $('.richItem:first').attr('class');
			$('.richItem:first').remove();
			$('#richSlider').append('<div class="'+firstItemClass+'">'+firstItem+'</div>');
			$('#richSlider').css('margin-left' ,  '-266');
        }

		 else if(direction=='left'){
			$('#richSlider').css('margin-left' ,  '0');
			var firstItem = $('.richItem:last').html();
			var firstItemClass = $('.richItem:last').attr('class');
			$('.richItem:last').remove();
			$('#richSlider').prepend('<div class="'+firstItemClass+'">'+firstItem+'</div>');
			$('#richSlider').css('margin-left' ,  '-266');
        }
		return false;
     }


	//ON ARROW CLICK
	 $( "#RR-sliderNext,#RR-sliderPrev" ).click(function(e) {
		e.preventDefault();
        var direction = $(this).attr('direction');
        arrowClicks(direction);
    });


	$(window).on('resize', function(){
      sliderSize();
	 });


}

$(function() {

        $(document).on('click', '.order-panel-trigger', function(e) {
    		  e.preventDefault()
					if ($(this).find('.order-chevron i').hasClass("fa-chevron-right")){
						$(this).find('.order-chevron i').removeClass( "fa-chevron-right" ).addClass( "fa-chevron-down" );
						$(this).siblings('.panel-collapse').toggle();
						$(this).siblings('.panel-collapse').css('display','block');

					}else{
						$(this).find('.order-chevron i').removeClass( "fa-chevron-down" ).addClass( "fa-chevron-right" );
						$(this).siblings('.panel-collapse').toggle();
						$(this).siblings('.panel-collapse').css('display','none');
						
					}
    		})
    		

		$(window).load(function() {
			setTimeout(function(){ $('.async-hide').removeClass('async-hide'); }, 1000);
            if ($(".recommendedProductItemArticle")[0]){
                $('.recommendedProductItem').each(function(i, obj){
                    var stars = $(this).find('.rrRating').attr('data-rating');
                    var halfStar = stars.split('.');
                    var halfStar = halfStar[1];
                    var zeroStar = 5-stars;
                    $(this).find('.rrRating').html('');
                    $(this).find('.rrRating').css('display','block');
                    for (i = 1; i <= 5; i++) {
                        if(stars >= i){
                            $(this).find('.rrRating').append('<i class="fa fa-star star-ratings"></i>');
                        }
                        else if(halfStar>0){
                            $(this).find('.rrRating').append('<i class="fa fa-star-half-o star-ratings"></i>');
                        }
                        else{
                            $(this).find('.rrRating').append('<i class="fa fa-star-o star-ratings"></i>');
                        }
                    }
                });
 			}
		});

	// analytics
	//Called when a product is added to a shopping cart.

	$(document).on('click','.iPromo',function(e){
		e.preventDefault();
		var url=$(this).attr('href');

		// Identify the promotion that was clicked.
		ga('ec:addPromo', {
		  'id': $(this).attr('data-id'),
		  'name': $(this).attr('data-title'),
		  'creative': $(this).attr('data-creative'),
		  'position': $(this).attr('data-position')
		});

		// Send the promo_click action with an event.
		ga('ec:setAction', 'promo_click');
		ga('send', 'event', 'Internal Promotions', 'click', $(this).attr('data-title'));

		setTimeout(function() {location.href = url}, 150);
	});

	function addToCart(product) {
		product=JSON.parse(product);
	  ga('ec:addProduct', {
	    'id': product.id,
	    'name': product.name,
	    'category': product.category,
	    'brand': product.brand,
	    'variant': product.variant,
	    'price': product.price,
	    'quantity': product.qty
	  });
	  ga('ec:setAction', 'add');
	  ga('send', 'event', 'Shopping', 'click', 'add to cart');     // Send data using an event.
	}


    // check balance
    var $formBalance = $('form#check-balance');
    if ($formBalance.size()) {
        var $checkBalanceButton = $('button[data-task="check-balance"]', $formBalance);
        $checkBalanceButton.on('click', function(e) {
            var $this = $(this);
            e.preventDefault();

            var digits20 = $('input#gift-card-16-digits', $formBalance).val().trim();
            var digits4 = $('input#gift-card-4-digits', $formBalance).val().trim();

            $.ajax({
                type: 'POST',
                data: {
                    dig20: digits20,
                    dig4: digits4
                },
                url: './shop/giftcards/check-balance'
            }).done(function(response) {
                var rowDiv = $('div[data-object-type="balance-message"]', $formBalance).removeClass('hidden');
                if (response.result) {
                    var $alertDiv = $('div.alert', rowDiv)
                        .removeClass('alert-danger')
                        .addClass('alert-info')
                        .html(response.balance_msg);
                } else {
                    $('div.alert', rowDiv)
                        .removeClass('alert-info')
                        .addClass('alert-danger')
                        .html(response.error);
                }
            }).always(function() {

            });
        });
    }





    $(document).on('click', '.autocomplete-suggestion', function(e) {
        $('body').css('opacity', '0.5');
        $('.autocomplete-suggestion').hide();
        $('body').prepend('<div id="loader"></div>');
        $('#searchBox').val($(this).attr('data-value'));
        $(this).closest("form").submit();

    });


    var delay = (function() {
        var timer = 0;
        return function(callback, ms) {
            clearTimeout(timer);
            timer = setTimeout(callback, ms);
        };
    })();

    // voting

    $(document).on('click', '.reviewBtn', function(e) {
        e.preventDefault();

        var thisBtn = $(this);
        $(this).addClass('btn-primary').removeClass('btn-default');
        $.ajax({
            type: 'get',
            url: $(this).attr('data-reviewRoute'),

            success: function(data) {

                if (data.code == "200") {

                    $(thisBtn).children('.upVoteCount').html(data.votes['up']);
                    $(thisBtn).children('.downVoteCount').html(data.votes['down']);
                    $(thisBtn).attr('disabled', 'true');
                    $(thisBtn).siblings('button').attr('disabled', 'true');

                } else {
                    $(thisBtn).attr('disabled', 'true');
                    $(thisBtn).siblings('button').attr('disabled', 'true');
                }

            }
        });


    });

    	$(document).on("click",".wishListAdd", function(e){

    		 $(this).closest('.wishlistItem').append('<div id="loaderBlock"></div>').css('opacity','0.5');



    	});

    	$(document).on("click",".wishlistRemove", function(e){

    		e.preventDefault();


   		 $(this).closest('.wishlistItem').append('<div id="loaderBlock"></div>').css('opacity','0.5');

    		var addToCart = $.get( $(this).attr('href'), function() {

    			$( "#cartModalContent" ).load( "/shop/cart/lightbox", function() {

    				$('.nav-tabs a[href="#wishlist"]').tab('show');

    			});
    		})

    	});
    	//

    	$('#productTabs a').click(function (e) {
    		  e.preventDefault()
    		  $(this).tab('show')
    		})

     	// Remove from cart

    	$(document).on("click",".cartRemove", function(e){

   		  ga('ec:addProduct', {
  		    'id':  $(this).attr('data-analytics')
  		  });
  		  ga('ec:setAction', 'remove');
  		  ga('send', 'event', 'Shopping', 'click', 'remove from cart');     // Send data using an event.

    		 $(this).closest('.cart-product').append('<div id="loaderBlock"></div>').css('opacity','0.5').attr("disabled", "disabled");

    	});

    	$(document).on("click",".moveToWishlist", function(e){
    		$(this).closest('.cart-product').append('<div id="loaderBlock"></div>').css('opacity','0.5');
            $(this).parent('form').submit();
    	});

    //product page

	$('.matrixSelect').change(function() {
		var jsonObject = jQuery.parseJSON($(this).find('option:selected').attr('data-variant'));
		//image changes if available for matrix
		var selected = $(this).find('option:selected').attr('data-image');
		$('.thumbnailImg').each(function() {
			if($(this).attr('id') == selected) {
				$('.mainImg').css('opacity', '0.5');
				$('.mainImg').attr("data-index", $(this).children('img').attr("data-index"));

				$('.mainImg').attr("src", $(this).children('img').attr("data-src"));

				$('.mainImg').attr("data-src", $(this).children('img').attr("data-src"));
				$('.mainImg').css('opacity', '1');
			}
		});

		//stock status updates dynamically for matrix
		$('#stock-status').html('<i class="fa fa-circle-o-notch fa-spin fa-fw margin-bottom" style="margin-bottom: 28px;"></i>');
		$.ajax( {
			type: "POST",
			url: '/shop/product/stock',
			data: {model: $(this).find('option:selected').attr('data-model')},
			success: function( response ) {
		  		$('#stock-status').html(response);
			}
		} );

		//price updates dynamically for matrix
		$('.price_actual').html('<span>'+jsonObject.price+'</span>');

		//option selected for matrix, enable da big button
		$('.addToCartButton').prop('disabled', false);

		updateAffirmPromos($('h2.price').parents('.affirm-parent').find('.affirm-as-low-as'), jsonObject.priceInCents);
		$('.promotionalTickets').html(Math.floor(jsonObject.priceInCents / 500) + ' Automatic Entries');
	});

	$('.promotionalModal').click(function(e) {
		e.preventDefault();

		$('.disrupt_bg_promotion').show();
		$('.disrupt_fg_promotion').show();
	});


    $('.thumbnailImg').hover(function(e) {
        e.preventDefault();


        $('.mainImg').css('opacity', '0.5');
        $('.mainImg').attr("data-index", $(this).children('img').attr("data-index"));

        $('.mainImg').attr("src", $(this).children('img').attr("data-src"));

        $('.mainImg').attr("data-src", $(this).children('img').attr("data-src"));
        $('.mainImg').css('opacity', '1');
    });
    $('.mobileThumbnailImg').click(function(e) {
        e.preventDefault();


        $('.mobileMainImg').css('opacity', '0.5');
        $('.mobileMainImg').attr("data-index", $(this).children('img').attr("data-index"));

        $('.mobileMainImg').attr("src", $(this).children('img').attr("data-src"));

        $('.mobileMainImg').attr("data-src", $(this).children('img').attr("data-src"));
        $('.mobileMainImg').css('opacity', '1');
    });
    $(document).on('click', '.photoModal', function(e) {

        var clicked = $(e.target);

        if (clicked.is('.photoModal')) {
            e.preventDefault();
            $('.photoModal').remove();
        }

    });

    $('.mobileMainImg').click(function(e) {

       // $('body').prepend('<div class="photoModal" data-index=' + $(this).attr('data-index') + '><div class="col-sm-8 frame"><a href="#" style="display:none;" class="rightArrow"></a><a href="#" style="display:none;" class="photoModalClose paddingTop paddingBottomSm pull-right"><i class="glyphicon x1 glyphicon-remove"></i></a><img src="' + $(this).attr('data-src') + ' " class="img-responsive"></div></div>');
    });

    // form validation
    $('input, select').blur(function(event) {

        if ($(event.target).attr('no-validation') == 'true' || $(event.target).attr('type') == 'checkbox') {
            return false;
        } else {
            event.target.checkValidity();
            $(event.target).removeClass("error");
            $(event.target).addClass("valid");
            $(event.target).closest('.form-group').find('.statusIcon').remove();
            $(event.target).closest('.form-group').addClass('has-success').addClass('has-feedback').removeClass('has-error').append('<span class="statusIcon glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>');

        }

    }).bind('invalid', function(event) {


        if ($(event.target).attr('no-validation') == 'true' || $(event.target).attr('type') == 'checkbox') {
            return false;
        } else {

            setTimeout(function() {
                $(event.target).closest('.form-group').removeClass('has-success').addClass('has-error');
                $(event.target).closest('.form-group').find('.statusIcon').removeClass('glyphicon-ok').addClass('glyphicon-remove');
                $(event.target).removeClass("valid");
                $(event.target).addClass("error");
            }, 50);
        }
    });

    function navLeft() {
        var numThumbs = $('.thumbnailImg').length ;
        var currentIndex = $('.photoModal').attr('data-index');

        if (currentIndex == 0) {
            currentIndex = numThumbs+1;
        }

        if (currentIndex !== "0") {

            var nextIndex = (Number(currentIndex) - (1));
            var nextSrc = $('.thumbnailImg img[data-index="' + nextIndex + '"]').attr('data-src');

            $('.photoModal img').attr("src", nextSrc);
            $('.photoModal').attr("data-index", nextIndex);

        }
    };

    function navRight() {
        var numThumbs = $('.thumbnailImg').length -1  ;

        var currentIndex = $('.photoModal').attr('data-index');

        if (currentIndex == "0") {
        	currentIndex=1;
        }
        if (numThumbs+1 == currentIndex) {
            currentIndex = 0;
        }

        if (currentIndex !== "0") {
            $('.leftArrow').show();
        } else {

            $('.leftArrow').hide();
        }


        var nextIndex = (Number(currentIndex) + (1));
        var nextSrc = $('.thumbnailImg img[data-index="' + nextIndex + '"]').attr('data-src');



        $('.photoModal img').attr("src", nextSrc);
        $('.photoModal').attr("data-index", nextIndex);

    };
    $('body').on("click", ".leftArrow", function(e) {
        e.preventDefault();
        navLeft();
    });
    $('body').on("click", ".rightArrow", function(e) {
        e.preventDefault();
        navRight();
    });
    $("body").keydown(function(e) {

        if (e.keyCode == 37) { // left

            navLeft();

        } else if (e.keyCode == 39) { // right


            navRight();
        }
    });

    $(document).on("click", ".photoModal img", function(e) {
        e.preventDefault();
        //$(this).remove();

        navRight();



    });

    $(document).on("click", ".photoModalClose", function(e) {
        e.preventDefault();
        $('.photoModal').remove();
    });

    var ymmYear, ymmMake, ymmModel, ymmSubmodel;

    //Category

    //uncheck all on page load

    $(document).on('click','#filterButton', function(e){

    	e.preventDefault();
    	$('#mobileFilters').toggle();
    	$(window).scrollTop(0);

    });

    $(document).on("click", "a.filterable", function(e) {
        var newUrl = $(this).attr('href');
        $('#productsHolder').css('opacity', '0.5');
    });

    // Text Modal

    $('.textModal').click(function(e) {
        if ($(this).attr('data-src')) {
            var srcDiv = $(this).attr('data-src');
            $("#textModal .modal-body").html($(srcDiv).html());

        } else {
            $("#textModal .modal-body").html($(this).parent().find('.textModalBody').html());
        }

    });

   

    $(document).on('click', '#mobileNavNumber', function(e){
    	 ga('send', 'event', 'click', 'mobile phone number', 'mobile phone number clicked');
    });
    $(document).on('submit', '#contactForm', function(e) {

        e.preventDefault();

        ga('send', 'event', 'submit form', 'contact us', 'Contact us form submit');
        $('#contactFormBox').css('opacity', '0.5');


        var frm = $(this);


        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function(data) {

                if (data.code == "200") {
                    $("#contactFormBox").html('<div class="alert alert-success" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Thank you for contacting RallySport Direct. A customer service representative will contact you shortly.</div>');
                } else {
                    $("#contactFormBox").html('<div class="alert alert-danger" role="alert"> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Something went wrong, please try again later.</div>');
                }

                $('#contactFormBox').css('opacity', '1');

            }
        });

    });




    // ADD TO CART
    $(document).on('submit', '.addToCartForm', function(e) {

        addToCart($('.addToCartButton').attr('data-analytics'));

        var frm = $(this);
        var oldHtml = $(frm).children('.addToCartButton').html();

        $(frm).children('.addToCartButton').html('<i class="glyphicon glyphicon-refresh spinning"></i>');


    });

    // MOBILE IMG



    // WISH LIST BUTTONS
    $('select[name="variant_id"]').change(function() {
        var $optionSelected = $("option:selected", this),
            variant_id = $optionSelected.val().trim();

        //$('.wishListButton, .addToWishlist').attr('data-variant', variant_id);
        $('.wishListButton').load("/shop/wishlist/button/" + variant_id, function() {});
    });

    $(".wishListButton").each(function() {
        $(this).load("/shop/wishlist/button/" + $(this).data('variant'), function() {});
    });

    $(document).on("click", ".addToWishlist", function(e) {

        e.preventDefault();

        var myButton = $(this).parent('div');
        var variant_id = $(this).data('variant');

        var addToWishList = $.post("/shop/wishlist/add", {
            variant_id: variant_id
        }, function(data) {
            myButton.load("/shop/wishlist/button/" + variant_id, function() {});
        })

    });

    $(document).on("click", ".removeFromWishlist", function(e) {

        e.preventDefault();
        $(this).closest('.cart-product').append('<div id="loaderBlock"></div>').css('opacity', '0.5');
        var myButton = $(this).parent('div');
        var variant_id = $(this).data('variant');
        var hash = $(this).data('hash');

        var addToWishList = $.get("/shop/wishlist/remove/" + hash, function(data) {
            myButton.load("/shop/wishlist/button/" + variant_id, function() {});
        })

    });

    

    // MY ACCOUNT

    $("#searchBox")
        .focusout(function() {
            setTimeout(function() {
                $(".autocomplete-suggestions").hide();
            }, 300);
        });

    $('.toggleSearch').click(function(e) {
        $('.searchInput').css('width', $('.mainNav').width());

        if ($('.searchInput').is(":visible")) {
            $(this).children('.glyphicon').addClass('glyphicon-search').removeClass('glyphicon-remove');
            $('.searchInput').hide();
        } else {
            $(this).children('.glyphicon').removeClass('glyphicon-search').addClass('glyphicon-remove');
            $('.searchInput').show()
            $('#searchBox').focus();
        }


    });

});

$(document).on('change','.categoryPicker', function(e) {
	var url=$( ".categoryPicker option:selected" ).attr('data-href');
	if (url){
		location.href=url;
	}
});


$(document).on('click','.viewAllCatSpec',function(e) {
    e.preventDefault();

    $(this).closest('li').hide();
    $(this).closest('li').siblings('li').show();
});

$(document).on('click', '#lightbox',function(e){
	e.preventDefault();
	$('#lightboxVid').html('');
	$('#lightbox').modal('hide');
});

var $lightbox = $('#lightbox');

$(document).on('click','[data-target="#lightbox"]', function() {
	var thisSrc= $(this).attr('data-href');

	if (!$(this).attr('data-video')) {
        var $img = $(this).children('img'),
        src = $img.attr('src'),
        alt = $img.attr('alt');

        $('#lightboxVid').html('');
        $('#lightboxVid').hide();
        $('#lightboxImg').show();
        $('#lightboxImg').attr('src', thisSrc ).css(css).attr('alt', alt);
    } else {
        $('#lightboxImg').hide();
        $('#lightboxVid').show();
        $('#lightboxVid').html('<iframe id="ytplayer" type="text/html" src="//www.youtube.com/embed/'+ $(this).attr('data-video') +'"frameborder="0"/>');
    }

    if ($(this).attr('data-caption')) {
        $('#lightboxCaption').html('<br>' + $(this).attr('data-caption'));
    }
});
//load/resize nav links for desktop (trump container class for inner nav area)
var waitForFinalEvent = (function () {
    var timers = {};
    return function (callback, ms, uniqueId) {
        if (!uniqueId) {
            uniqueId = "NotUnique";
        }
        if (timers[uniqueId]) {
            clearTimeout (timers[uniqueId]);
        }
        timers[uniqueId] = setTimeout(callback, ms);
    };
})();
function setLocation(url){
    window.location.href = url;
}

$( document ).ready(function() {
    var timer;
  
  $('body').on('submit', '.addToCartForm', function (e){
        e.preventDefault();
        var form = $(this);
        form.find('.ymmLoader').show();
        form.find('.add_to_cart_text').hide();
        var product_name = form.find('button').attr('product_name');
        jQuery.fancybox.showActivity();
        $.ajax( {
            type: "POST",
            url: form.attr( 'action' ),
            data: form.serialize(),
            success: function( response ) {
                form.find('.ymmLoader').hide();
                form.find('.add_to_cart_text').show();
                //Find the my-cart element and replace with refreshed version.
                jQuery.fancybox.hideActivity();
                jQuery.fancybox({
                    'content'           : '<div class="ajax-message"><p>' + product_name + ' was added to your shopping cart.<br><br> <button class="button" onclick="setLocation(\'/shop/cart/\')"><span><span>View Cart</span></span></button><button class="button" onclick="setLocation(\'/shop/checkout/\')"><span><span>Checkout</span></span></button></p></div>',
                    'autoDimensions'	: true,
                    'padding'		    : 30,
                    'transitionIn'		: 'none',
                    'transitionOut'		: 'none',
                    'autoScale'         : true,
                    'centerOnScroll'	: true
                }
            );
            //TODO: make sure we throw up the spinny loady and confirmation modal w/ prod title.
                
            },
            error: function( response ) {
                $('#cart-modal .modal-body').html(response.message);
                $('#cart-modal').fadeIn();
                jQuery.fancybox.hideActivity();
            }
        });
    });
  });
