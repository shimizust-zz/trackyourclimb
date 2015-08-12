var projectPopoverText = 'Attempt = Attempted, but did not complete a climb cleanly (x0.5)';
var redpointPopoverText = 'Redpoint = Completed a climb cleanly after multiple tries and/or beta (x1.0)';
var flashPopoverText = 'Flash = Completed a climb on first try with beta (x1.25)';
var onsightPopoverText = 'Onsight = Completed a climb on first try with no beta (x1.35)';


var popoverIcon = '<span class = "glyphicon glyphicon-question-sign"></span>';

var tableHeader = '<table class=\"workoutInputTable\">'+
					'<tr><td></td><td '+hideProject+'>'+
					'<a data-toggle="popover" class="btn"' +
					'data-content="'+projectPopoverText+'"><h4 style="display:inline;">Attempt </h4>'+popoverIcon+'</a></td>'+
					'<td '+hideRedpoint+'>'+
					'<a data-toggle="popover" class="btn"' +
					'data-content="'+redpointPopoverText+'"><h4 style="display:inline;">Redpoint </h4>'+popoverIcon+'</a></td>'+
					'<td '+hideFlash+'>'+
					'<a data-toggle="popover" class="btn"' + 
					'data-content="'+flashPopoverText+'"><h4 style="display:inline;">Flash </h4>'+popoverIcon+'</a></td>'+
					'<td '+hideOnsight+'>'+
					'<a data-toggle="popover" class="btn"' + 
					'data-content="'+onsightPopoverText+'"><h4 style="display:inline;">Onsight </h4>'+popoverIcon+'</a></td></tr>';

var YDSratings_text = ["<=5.5","5.6","5.7","5.8","5.9","5.10a","5.10b","5.10c","5.10d",
	"5.11a","5.11b","5.11c","5.11d","5.12a","5.12b","5.12c","5.12d","5.13a",
	"5.13b","5.13c","5.13d","5.14a","5.14b","5.14c","5.14d","5.15a","5.15b",
	"5.15c","5.15d"];
var YDSratings = ["5_5","5_6","5_7","5_8","5_9","5_10a","5_10b","5_10c","5_10d",
	"5_11a","5_11b","5_11c","5_11d","5_12a","5_12b","5_12c","5_12d","5_13a",
	"5_13b","5_13c","5_13d","5_14a","5_14b","5_14c","5_14d","5_15a","5_15b",
	"5_15c","5_15d"];