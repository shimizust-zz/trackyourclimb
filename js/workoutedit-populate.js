//fill the workout input buttons/hidden inputs with values

//A JSON object, workoutsegments, contains all the previous climbs
//A JSON object, workoutinfo, contains all workout information
$(document).ready(function() {
	var num_segments = workoutsegments.length;
	
	for (var i = 0;i < num_segments;i++) {
		var climb_type = workoutsegments[i].climb_type; //boulder|toprope|lead
		var ascent_type = workoutsegments[i].ascent_type; //project|redpoint|flash|onsight
		var abs_grade_index = workoutsegments[i].grade_index; //this is the absolute grade index
		var count = workoutsegments[i].reps;
		
		//make the correct button and hidden input match this workout information
		var btn_climb_type = "";
		//rating needs to be the index of the grade in boulderRatings or routeRatings
		var rating = "";
		switch (climb_type) {
			case "boulder":
				btn_climb_type = "B";
				rating = boulderRatings[boulderGradingID].indexOf(boulderConversionTable[boulderGradingID][abs_grade_index]);
				break;
			case "toprope":
				btn_climb_type = "TR";
				rating = routeRatings[routeGradingID].indexOf(routeConversionTable[routeGradingID][abs_grade_index]);
				break;
			case "lead":
				btn_climb_type = "L";
				rating = routeRatings[routeGradingID].indexOf(routeConversionTable[routeGradingID][abs_grade_index]);
				break;
		}

		
		var inputsuffix = capitaliseFirstLetter(ascent_type)+btn_climb_type+rating;
		var hiddenInputId = 'num'+inputsuffix;
		var mainButtonId = 'btn-'+inputsuffix;
		
		
		document.getElementById(hiddenInputId).value = parseInt(count) + parseInt(document.getElementById(hiddenInputId).value);
		
		//use jquery .text() for cross-browser compatibility compared to innerText
		$("#"+mainButtonId).text("+ ("+document.getElementById(hiddenInputId).value+")");
		
		document.getElementById("BoulderNotes").value = workoutinfo.boulder_notes;
		document.getElementById("TRNotes").value = workoutinfo.tr_notes;
		document.getElementById("LeadNotes").value = workoutinfo.lead_notes;
		document.getElementById("OtherNotes").value = workoutinfo.other_notes;
	}

});

function capitaliseFirstLetter(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}
