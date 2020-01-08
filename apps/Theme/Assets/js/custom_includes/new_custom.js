$(document).ready(function(){
    $('#showAllOrders').click(function(e) {
            e.preventDefault();
            $(this).hide();
            $('.showingOrders').hide();
            $('#myOrders .panel').show();

    });
    $('.free-shipping a').click(function(e) {
            e.preventDefault();
            var modalContent = $(this).parent().find('.textModalBody').html();
            $('body').prepend('<div id="modalPopFade"></div><div id="modalPop"><div id="modalPopExit"><i class="glyphicon x1 glyphicon-remove"></i></div>'+modalContent+'</div>');
            var winHeight=$(window).height();
            var winWidth=$(window).width();
            var modalHeight=$('#modalPop').outerHeight();
            var modalWidth=$('#modalPop').outerWidth();
            modalHeight=(winHeight-modalHeight)/2;
            modalWidth=(winWidth-modalWidth)/2;
            $('#modalPop').css('top', modalHeight+'px');
            $('#modalPop').css('left', modalWidth+'px');
    });
    $('.guaranteeModal').click(function(e) {
            e.preventDefault();
            var modalContent = $(this).parent().find('.textModalBody').html();
            $('body').prepend('<div id="modalPopFade"></div><div id="modalPop"><div id="modalPopExit"><i class="glyphicon x1 glyphicon-remove"></i></div>'+modalContent+'</div>');
            var winHeight=$(window).height();
            var winWidth=$(window).width();
            var modalHeight=$('#modalPop').outerHeight();
            var modalWidth=$('#modalPop').outerWidth();
            modalHeight=(winHeight-modalHeight)/2;
            modalWidth=(winWidth-modalWidth)/2;
            $('#modalPop').css('top', modalHeight+'px');
            $('#modalPop').css('left', modalWidth+'px');
    });
    $('.reviewModal').click(function(e) {
            e.preventDefault();
            var modalContent = $(this).attr('data-href');
            $('body').prepend('<div id="modalPopFade"></div><div id="modalPop"><div id="modalPopExit"><i class="glyphicon x1 glyphicon-remove"></i></div><img style="width:100%;" src="'+modalContent+'"/></div>');
            var winHeight=$(window).height();
            var winWidth=$(window).width();
            var modalHeight=$('#modalPop').outerHeight();
            var modalWidth=$('#modalPop').outerWidth();
            modalHeight=(winHeight-modalHeight)/2;
            modalWidth=(winWidth-modalWidth)/2;
            $('#modalPop').css('top', modalHeight+'px');
            $('#modalPop').css('left', modalWidth+'px');
    });
    $(document).on('click',"#modalPopExit",function () {        
           $("#modalPopFade").remove();
        $('#modalPop').remove();
        });
    $(document).on('click',"#modalPopFade",function () { 
        if($( window ).width()>768){
            $("#modalPopFade").remove();
            $('#modalPop').remove();
        }
    });

    $(document).on('click',"#modalPopExit",function () {        
           $("#modalPopFade").remove();
        $('#modalPop').remove();
        });
    $(document).on('click',"#modalPopFade",function () { 
        if($( window ).width()>768){
            $("#modalPopFade").remove();
            $('#modalPop').remove();
        }
    });

    $('.viewLoginModal').click(function(e) {
            e.preventDefault();
            var modalContent = $('#loginModal .container').html();
            $('body').prepend('<div id="modalPopFade"></div><div id="modalPop"><div id="modalPopExit"><i class="glyphicon x1 glyphicon-remove"></i></div>'+modalContent+'</div>');
            var winHeight=$(window).height();
            var winWidth=$(window).width();
            var modalHeight=$('#modalPop').outerHeight();
            var modalWidth=$('#modalPop').outerWidth();
            modalHeight=(winHeight-modalHeight)/2;
            modalWidth=(winWidth-modalWidth)/2;
            $('#modalPop').css('top', modalHeight+'px');
            $('#modalPop').css('left', modalWidth+'px');
    });
    $('#returns').click(function(e) {
            e.preventDefault();
            var modalContent = $('#returns .textModalBody').html();
            $('body').prepend('<div id="modalPopFade"></div><div id="modalPop"><div id="modalPopExit"><i class="glyphicon x1 glyphicon-remove"></i></div>'+modalContent+'</div>');
        var winHeight=$(window).height();
            var winWidth=$(window).width();
            var modalHeight=$('#modalPop').outerHeight();
            var modalWidth=$('#modalPop').outerWidth();
            modalHeight=(winHeight-modalHeight)/2;
            modalWidth=(winWidth-modalWidth)/2;
            $('#modalPop').css('top', modalHeight+'px');
            $('#modalPop').css('left', modalWidth+'px');
    });

    $('#returns .textModal').attr('data-toggle','');
    $('.viewLoginModal').attr('data-toggle','');

                            
});