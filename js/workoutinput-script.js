

$(document).ready(function() {
	$('[data-toggle="popover"]').popover({
		trigger:'hover',
		'placement':'top'
		});
	
	
	$('.datepicker').datepicker({
		todayBtn : "linked",
		todayHighlight : true,	
		autoclose : true,
		format: "yyyy-mm-dd"
	});
	//sets default date to today
	$('.datepicker').datepicker('update',new Date());


	
	//handle button clicks on grades

	var rating = '0';
	var climbType = ''; //initialize
	var dec = false;
	$('body').click(function(event) {
		
		//check what type of climbing
		climbType = event.target.id.match(/(B)|(TR)|(L)/g);

		rating = event.target.id.match(/\d+/g);
		
		//get ascent type (e.g. Project/Redpoint/Flash/Onsight)
		//include the g modifier to return just the match
		ascentType = event.target.id.match(/(Project)|(Redpoint)|(Flash)|(Onsight)/g);
		
		//check if decrement
		var decval = event.target.id.match(/(dec)/g);
		if (decval!=null) {
			dec = true;
		}
		else {
			dec = false;
		}
		
		
		//extract current number of climbs of this category from the 
		//hidden input
		var hiddenInputId = "num"+ascentType+climbType+rating;
		var mainButtonId = "btn-"+ascentType+climbType+rating;
		var count = document.getElementById(hiddenInputId).value;
		
		//increment or decrement the climb count
		var updateCount = false; //only update the count if you increment or decrement (don't keep updating the count if decrementing a zero count
		if (!dec) {
			count++;
			updateCount = true;
		}
		else if (dec & count>0) {
			count--;
			updateCount = true;
		}
		
		//now write the value to the hidden input and update button label
		document.getElementById(hiddenInputId).value = count;
		
		//use jquery .text() for cross-browser compatibility compared to innerText
		$("#"+mainButtonId).text("+ ("+count+")");
		
		//calculate the point change
		var gradingID = climbType=="B" ? boulderGradingID : routeGradingID;
		
		if (updateCount) {
			currPointTotal = updatePointTotal(currPointTotal,ascentType[0],climbType,rating,gradingID,dec);
			$("#current-point-total").text(Math.round(currPointTotal));
		}
		
		
		
	});
	

	
});
/*
$(window).scroll(function(e){ 
  $el = $('.fixedElement'); 
  if ($(this).scrollTop() > 200 && $el.css('position') != 'fixed'){ 
    $('.fixedElement').css({'position': 'fixed', 'top': '0px'}); 
  }
  if ($(this).scrollTop() < 200 && $el.css('position') == 'fixed')
  {
    $('.fixedElement').css({'position': 'static', 'top': '0px'}); 
  } 
});
*/