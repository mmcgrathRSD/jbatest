var richThumbCount = $('.richItem').length; 
var richSlideNumber = 1;
$(window).load(function(){
    
    $('.richItemRating').each(function(i, obj) {
        var starRating =  $(this).text();
		if(starRating>0){
            var starCount=1;
		    var halfStar=starRating.split('.');
		    halfStar=halfStar[1];
            var blankStars = 5-Math.ceil(starRating);
            $(this).html('');
            while(starCount<=Math.round(starRating)){
			    $(this).append( '<i class="fa fa-star star-ratings"></i>' );
			    starCount++;
            }
            if (halfStar>0) {
                $(this).append('<i class="fa fa-star-half-o star-ratings"></i>');
            }
            for(var z=0; z < blankStars; z++){
                $(this).append( '<i class="fa fa-star-o star-ratings"></i>' );
			}
        }
	});
    
    $('#richContainer').attr('slideNumber','1');
    sizing();
    
    $( "#RR-sliderPrev" ).click(function() {
        var slidesLeft = parseFloat( $('#RR-sliderPrev').attr('slides'));
        var slidesRight = parseFloat( $('#RR-sliderNext').attr('slides'));
        var slideNumber = parseFloat( $('#richContainer').attr('slideNumber')); 
        var slideWidth = parseFloat( $('.rrItemContainer').css('width')); 
		if(slidesLeft>0){
            slidesRight++
            slideNumber--
            $('#richContainer').attr('slideNumber',slideNumber);
            $('#RR-sliderNext').attr('slides',slidesRight);
			$('#richSlider').css('left' , -1*(slideWidth*(slideNumber-1)))+'px';
			slidesLeft--; 
            $(this).attr('slides',slidesLeft);            
            arrows();
		}
	});
	
	$( "#RR-sliderNext" ).click(function() {
        var slidesLeft = parseFloat( $('#RR-sliderPrev').attr('slides'));
        var slidesRight = parseFloat( $(this).attr('slides'));
        var slideNumber = parseFloat( $('#richContainer').attr('slideNumber')); 
        var slideWidth = parseFloat( $('#richContainer').css('width')); 
		if(slidesRight>0){
            slidesLeft++
            slideNumber++
            $('#richContainer').attr('slideNumber',slideNumber);
            $('#RR-sliderPrev').attr('slides',slidesLeft);
			$('#richSlider').css('left' , -1*(slideWidth*(slideNumber-1)))+'px';
			slidesRight--; 
            $(this).attr('slides',slidesRight);
            arrows();
		}
	});
        
	
});
$(window).on('resize', function(){
	sizing();
});


function arrows() {
    if( $('#RR-sliderNext').attr('slides')>0){
        $('#RR-sliderNext').css({ 'opacity' : '1', 'cursor' : 'pointer' });
    }else{
        $('#RR-sliderNext').css({ 'opacity' : '0.5', 'cursor' : 'default' });
    }
    if( $('#RR-sliderPrev').attr('slides')>0){
        $('#RR-sliderPrev').css({ 'opacity' : '1', 'cursor' : 'pointer' });
    }else{
        $('#RR-sliderPrev').css({ 'opacity' : '0.5', 'cursor' : 'default' });
    }
	if( $('#RR-sliderPrev').attr('slides')<=0 && $('#RR-sliderNext').attr('slides')<=0){
		$('#RR-sliderPrev').css('display','none');
            $('#RR-sliderNext').css('display','none');
	}
}
	
function sizing() {
    var richThumbCount = $('.rrCarouselItem').length;
    $('#richSlider').css('left',0);
    $('#richContainer').attr('slideNumber','1');
    
    if ($('body').width() >= 1200 ){
        var richSlideCount = Math.round(richThumbCount/5);
        $('#RR-sliderNext').attr('slides',(richSlideCount-1));
        $('#RR-sliderPrev').attr('slides',0);
        $('.rrItemContainer').css('width','1015');
        if(richSlideCount<1){
            $('#RR-sliderPrev').css('display','none');
            $('#RR-sliderNext').css('display','none');
        }else{
            $('#RR-sliderPrev').css('display','block');
            $('#RR-sliderNext').css('display','block');
        }
        arrows();
    }
    
    if ($('body').width() >= 1000 && $('body').width() <1200){
        var richSlideCount = Math.round(richThumbCount/4);
        $('#RR-sliderNext').attr('slides',(richSlideCount-1));
        $('#RR-sliderPrev').attr('slides',0);
        $('.rrItemContainer').css('width','812');
        if(richSlideCount<=1){
            $('#RR-sliderPrev').css('display','none');
            $('#RR-sliderNext').css('display','none');
        }else{
            $('#RR-sliderPrev').css('display','block');
            $('#RR-sliderNext').css('display','block');
        }
        arrows();
	}
    
    if ($('body').width() < 900) {
        $('.rrItemContainer').css('overflow-x','scroll');
         $('#RR-sliderPrev').css('display','none');
         $('#RR-sliderNext').css('display','none');
        $('.rrItemContainer ul').css('width',richThumbCount*203);
        $('.rrItemContainer').css('width',$('body').width());
            //MOBILE
	}		
}