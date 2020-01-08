 var richThumbCount = 0;
    var bodyWidth = 0;
    var arrowWidths = 0;
    var totalSpace =0; //find out the total area we have for the slider
    var thumbWidth = 0;
    var slideNumber = 0;
    var thumbsVisible =0;
    var sliderVisible = 0;
var totalSlides = 0;
$( window ).load(function() {
    
    //THIS WAITS UNTIL RICH RELEVANCE HAS COMPLETED LOADING BEFORE STARTING OUR OWN STUFF
    var checkExist = setInterval(function() {
        if ($('#richSlider .richItem').length) {
            
            clearInterval(checkExist);
            styleRichRel();
        }
    }, 100); // check every 100ms
});
$(window).on('resize', function(){
   styleRichRel();
});

function styleRichRel(){
    richThumbCount = ($('#richSlider .richItem').length);
    thumbWidth = $('.richItem:first').outerWidth(true);
    bodyWidth = $('.container').width();
    if(($('.container').width())<=0){
        mobileFriendly();
    }else{
    arrowWidths = ($('#RR-sliderPrev').width()+$('#RR-slidernext').width());
    totalSpace = bodyWidth-arrowWidths; //find out the total area we have for the 
     slideNumber = 0;
    
    //FIND OUT HOW MANY ITEMS WE CAN FIT IN OUR TOTAL SPACE
    thumbsVisible = Math.floor(totalSpace/thumbWidth);
    
    //RESIZE SLIDER BASE ON HOW MANY THUMBS CAN BE VISIBLE
    sliderVisible = thumbsVisible*thumbWidth;
    $('#richContainer').css('width',sliderVisible+'px');//the 20 is taking into account the margin on the container
    $('#richSlider').css('width',(richThumbCount*thumbWidth)+20+'px');
    
    //NOW WE CAN FIGURE OUT HOW MANY SLIDES THERE ARE, THEN ASSIGN THESE VALUE TO A DATA ATTRIBUE TO CALL ON LATER
     totalSlides = Math.round( richThumbCount/thumbsVisible);
    $('#richContainer').attr('total-slides',totalSlides);
    $('#RR-sliderNext').attr('slides',(totalSlides-1));
    $('#RR-sliderPrev').attr('slides','0');
    $('#richSlider').css('margin-left','0');
    //these attributes should probably be done in the acutal template
    $('#RR-sliderNext').attr('direction','right');
    $('#RR-sliderPrev').attr('direction','left');
    $('#richContainer').attr('slide-number','0');
    updateRichArrows();
    
    
    //CLICK EVENTS
    
   
    
    
    
    $('.richItemRating').each(function(i, obj) {
		var starRating =  $(this).text();
		if(starRating>0){
			var itemThis = $(this);
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
}
}
function updateRichArrows(){
    var Next=$("#RR-sliderNext");
    if($(Next).attr('slides')>=1){
        $(Next).css({ 'cursor' : 'pointer', 'color' : '#0155ab' });
    }else{
        $(Next).css({ 'cursor' : 'default', 'color' : '#9d9d9d'});
    }
    var Prev=$("#RR-sliderPrev");
    if($(Prev).attr('slides')>=1){
        $(Prev).css({ 'cursor' : 'pointer', 'color' : '#0155ab' });
    }else{
        $(Prev).css({ 'cursor' : 'default', 'color' : '#9d9d9d'});
    }
    
}
 $(document).on('click',"#RR-sliderNext,#RR-sliderPrev",function () {  
		var direction = $(this).attr('direction');
        
        if(direction=='right'){
            var slideNumber = $('#richContainer').attr('slide-number');
            
            slideNumber++;
            var slidesRemaining= $(this).attr('slides');
            if(slidesRemaining>=1){
				var margin = sliderVisible+8.5;
                $('#richSlider').css('margin-left',-1*(slideNumber*margin));
                slidesRemaining--;
                $(this).attr('slides',slidesRemaining);
                var slidesRemaining= $('#RR-sliderPrev').attr('slides');
                $('#RR-sliderPrev').attr('slides',parseInt(slidesRemaining)+1);
                $('#richContainer').attr('slide-number',slideNumber);
            }
        }else{
            
            var slideNumber = $('#richContainer').attr('slide-number');
            slideNumber--;
            var slidesRemaining= $(this).attr('slides');
            if(slidesRemaining>=1){
				var margin = sliderVisible+8.5;
                $('#richSlider').css('margin-left',-1*(slideNumber*margin));
                slidesRemaining--;
                $(this).attr('slides',slidesRemaining);
                var slidesRemaining= $('#RR-sliderNext').attr('slides');
                $('#RR-sliderNext').attr('slides',parseInt(slidesRemaining)+1);
                $('#richContainer').attr('slide-number',slideNumber);
            }
            
        }
        updateRichArrows();
	});
function mobileFriendly(){
    $('#RR-sliderPrev,#RR-sliderNext').remove();
    $('#richContainer').css('width','100%');
    $('#richContainer').css('overflow-x','scroll');
     $('#richSlider').css('width',(richThumbCount*thumbWidth)+'px');
    $('.richItemRating').each(function(i, obj) {
		var starRating =  $(this).text();
		if(starRating>0){
			var itemThis = $(this);
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
    
}
    