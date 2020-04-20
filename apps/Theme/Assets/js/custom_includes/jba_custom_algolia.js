jQuery(function($){
    $(document).on('click', 'em', function() {
        console.log($(this));
        $(this).next()[0].click();
    });
});