

function updateGymEvents() {

	var gym_select = document.getElementById("select-gym");
	var gymid = gym_select.options[gym_select.selectedIndex].value;
	
	$.ajax({
		url: sitePath()+"/php_dataquery/getEventsByGym.php?gymid="+gymid,
		dataType: 'text',
		success: function(data) {
			//append gym_options to the gym-select input
			//data is JSON output
			
			parsedData = jQuery.parseJSON(data);
			if (jQuery.isEmptyObject(parsedData)){
				
				//an empty json object
				var msg = "<div class='alert alert-info'>No upcoming events found.</div>";
				
			}
			else {
				
				var numevents = Object.keys(parsedData).length;
				var msg = "<div class='alert alert-info'><b>Upcoming Events for this Gym:</b><ol>";
				for (var i=0;i<numevents;i++) {
					var startdate_str = parsedData[i].event_startdate;
					var enddate_str = parsedData[i].event_enddate;
					
					msg += "<li>"+parsedData[i].event_name+" (";
					
					if (startdate_str === enddate_str) {
						msg += parsedData[i].event_startdate+")</li>";
					}
					else {
						//different end and start dates
						msg += parsedData[i].event_startdate+" to "+parsedData[i].event_enddate+")</li>";
					}
					
				}
			}
			//document.getElementById("select-gym-div").innerHTML = data;
			document.getElementById("existing-events").innerHTML = msg;
			
		},
		error: function(){
			alert('Error in AJAX call');
		}
	});
}