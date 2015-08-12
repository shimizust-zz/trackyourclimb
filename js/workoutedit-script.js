

$(document).ready(function() {
	$('[data-toggle="popover"]').popover({
		trigger:'hover',
		'placement':'top'
		});
	
	
	//var countV = new Array(16); //store from V0 to V15

	var rating = '0';
	var climbType = ''; //initialize
	var dec = false;
	$('body').click(function(event) {
		
		//check what type of climbing
		climbType = event.target.id.match(/(B)|(TR)|(L)/g);

		if (climbType=='B') {
			//get the Vrating
			rating = event.target.id.match(/\d+/g);	
		}
		else if ((climbType=='TR')||(climbType=='L')) {
			rating = event.target.id.match(/\d+/g);
		}
		
		
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
		
		
		//$("#log").html(climbType+"  "+rating);
		//$("#log").html(event.target.id);
		
		//extract current number of climbs of this category from the 
		//hidden input
		var hiddenInputId = "num"+ascentType+climbType+rating;
		var mainButtonId = "btn-"+ascentType+climbType+rating;
		var count = document.getElementById(hiddenInputId).value;
		
		//increment or decrement the climb count
		if (!dec) {count++;}
		else if (dec & count>0) {count--;}
		
		//now write the value to the hidden input and update button label
		document.getElementById(hiddenInputId).value = count;
		
		//use jquery .text() for cross-browser compatibility compared to innerText
		$("#"+mainButtonId).text("+ ("+count+")");

		
	});
	

	
});

