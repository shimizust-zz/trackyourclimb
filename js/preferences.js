$(document).ready(function() {

//Note: default min/max ranges are defined outside of this JS file
//include "js/BoulderRouteGradingSystems.js" before running this script

//generate Bouldering rating selection upon click
//Find which boulder grading system is selected and update the difficulty range
//**********************************************
$("select[name='boulder-rating-select']").click(function()
{
	var boulderGradingSelect = document.getElementById("boulder-rating-select");
	boulderGradingID = boulderGradingSelect.options[boulderGradingSelect.selectedIndex].value;
	console.log(boulderGradingID);
	
	var maxBoulder = boulderRatings[boulderGradingID].length - 1;

	var boulderingMinRange = "<div id='boulderprefs'><p>Minimum bouldering rating: <select name='minBoulderRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxBoulder;i++) {
		if (i==selectMinBoulder) {
			boulderingMinRange += "<option value="+i+" selected>"+boulderRatings[boulderGradingID][i]+"</option>";
		} else {
		boulderingMinRange += "<option value="+i+">"+boulderRatings[boulderGradingID][i]+"</option>";
		}
	}
	boulderingMinRange += "</option></select>";

	//get max value of bouldering range
	var boulderingMaxRange = "<p>Maximum bouldering rating: <select name='maxBoulderRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxBoulder;i++) {
		if (i==Math.min(maxBoulder,selectMaxBoulder)) {
			boulderingMaxRange += "<option value="+i+" selected>"+boulderRatings[boulderGradingID][i]+"</option>";
		} else {
		boulderingMaxRange += "<option value="+i+">"+boulderRatings[boulderGradingID][i]+"</option>";
		}
	}
	boulderingMaxRange += "</option></select></div>";
	
	document.getElementById("boulderprefs").innerHTML = 
	boulderingMinRange + boulderingMaxRange;
});
//**********************************************
//initialize the boulder grading system
var maxBoulder = boulderRatings[boulderGradingID].length - 1;


var boulderingMinRange = "<div id='boulderprefs'><p>Minimum bouldering rating: <select name='minBoulderRange' class='form-control' id='rating-range-select'>";
for (var i = 0;i<=maxBoulder;i++) {
	if (i==selectMinBoulder) {
		boulderingMinRange += "<option value="+i+" selected>"+boulderRatings[boulderGradingID][i]+"</option>";
	} else {
	boulderingMinRange += "<option value="+i+">"+boulderRatings[boulderGradingID][i]+"</option>";
	}
}
boulderingMinRange += "</option></select>";

//get max value of bouldering range
var boulderingMaxRange = "<p>Maximum bouldering rating: <select name='maxBoulderRange' class='form-control' id='rating-range-select'>";
for (var i = 0;i<=maxBoulder;i++) {
	if (i==Math.min(maxBoulder,selectMaxBoulder)) {
		boulderingMaxRange += "<option value="+i+" selected>"+boulderRatings[boulderGradingID][i]+"</option>";
	} else {
	boulderingMaxRange += "<option value="+i+">"+boulderRatings[boulderGradingID][i]+"</option>";
	}
}
boulderingMaxRange += "</option></select></div>";
//************************



//Update TR and lead when clicked
//******************************
$("select[name='route-rating-select']").click(function()
{
	var routeGradingSelect = document.getElementById("route-rating-select");
	routeGradingID = routeGradingSelect.options[routeGradingSelect.selectedIndex].value;
	console.log(routeGradingID);
	
	
	var maxRoute = routeRatings[routeGradingID].length - 1;
	
	//generate TR rating selection
	var TRMinRange = "<div id='trprefs'><p>Minimum top-rope rating: <select name='minTRRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxRoute;i++) {
		if (i==selectMinTR) {
			TRMinRange +="<option value="+i+" selected>"+
			routeRatings[routeGradingID][i]+"</option>";
		} else {
			TRMinRange += "<option value="+i+">"+
			routeRatings[routeGradingID][i]+"</option>";
		}
	}
	TRMinRange += "</option></select>";

	var TRMaxRange = "<p>Maximum top-rope rating: <select name='maxTRRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxRoute;i++) {
		if (i==Math.min(maxRoute,selectMaxTR)) {
			TRMaxRange +="<option value="+i+" selected>"+
			routeRatings[routeGradingID][i]+"</option>";
		} else {
			TRMaxRange += "<option value="+i+">"+
			routeRatings[routeGradingID][i]+"</option>";
		}
	}
	TRMaxRange += "</option></select></div>";

	//generate lead rating selection
	var LeadMinRange = "<div id='leadprefs'><p>Minimum lead rating: <select name='minLeadRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxRoute;i++) {
		if (i==selectMinL) {
			LeadMinRange +="<option value="+i+" selected>"+
			routeRatings[routeGradingID][i]+"</option>";
		} else {
			LeadMinRange += "<option value="+i+">"+
			routeRatings[routeGradingID][i]+"</option>";
		}
	}
	LeadMinRange += "</option></select>";

	var LeadMaxRange = "<p>Maximum lead rating: <select name='maxLeadRange' class='form-control' id='rating-range-select'>";
	for (var i = 0;i<=maxRoute;i++) {
		if (i==Math.min(maxRoute,selectMaxL)) {
			LeadMaxRange +="<option value="+i+" selected>"+
			routeRatings[routeGradingID][i]+"</option>";
		} else {
			LeadMaxRange += "<option value="+i+">"+
			routeRatings[routeGradingID][i]+"</option>";
		}
	}
	LeadMaxRange += "</option></select></div>";

	document.getElementById("trprefs").innerHTML = TRMinRange + TRMaxRange;
document.getElementById("leadprefs").innerHTML = 
	LeadMinRange + LeadMaxRange;		
});
//**********************************

//Initialize TR and Lead selections

var maxRoute = routeRatings[routeGradingID].length - 1;
//generate TR rating selection
var TRMinRange = "<div id='trprefs'><p>Minimum top-rope rating: <select name='minTRRange' class='form-control' id='rating-range-select'>";
for (var i = 0;i<=maxRoute;i++) {
	if (i==selectMinTR) {
		TRMinRange +="<option value="+i+" selected>"+
		routeRatings[routeGradingID][i]+"</option>";
	} else {
		TRMinRange += "<option value="+i+">"+
		routeRatings[routeGradingID][i]+"</option>";
	}
}
TRMinRange += "</option></select>";

var TRMaxRange = "<p>Maximum top-rope rating: <select name='maxTRRange' class='form-control' id='rating-range-select'>";
for (var i = 0;i<=maxRoute;i++) {
	if (i==Math.min(maxRoute,selectMaxTR)) {
		TRMaxRange +="<option value="+i+" selected>"+
		routeRatings[routeGradingID][i]+"</option>";
	} else {
		TRMaxRange += "<option value="+i+">"+
		routeRatings[routeGradingID][i]+"</option>";
	}
}
TRMaxRange += "</option></select></div>";

//generate lead rating selection
var LeadMinRange = "<div id='leadprefs'><p>Minimum lead rating: <select name='minLeadRange' class='form-control' id='rating-range-select'>";
for (var i = 0;i<=maxRoute;i++) {
	if (i==selectMinL) {
		LeadMinRange +="<option value="+i+" selected>"+
		routeRatings[routeGradingID][i]+"</option>";
	} else {
		LeadMinRange += "<option value="+i+">"+
		routeRatings[routeGradingID][i]+"</option>";
	}
}
LeadMinRange += "</option></select>";

var LeadMaxRange = "<p>Maximum lead rating: <select name='maxLeadRange' class='form-control' id='rating-range-select'> ";
for (var i = 0;i<=maxRoute;i++) {
	if (i==Math.min(maxRoute,selectMaxL)) {
		LeadMaxRange +="<option value="+i+" selected>"+
		routeRatings[routeGradingID][i]+"</option>";
	} else {
		LeadMaxRange += "<option value="+i+">"+
		routeRatings[routeGradingID][i]+"</option>";
	}
}
LeadMaxRange += "</option></select></div>";

//write rating preferences to html
document.getElementById("ratingrange").innerHTML = 
		boulderingMinRange + boulderingMaxRange + TRMinRange + TRMaxRange +
		LeadMinRange + LeadMaxRange;
	
$("select[name='country-select']").click(function()
{
	//get country value
	var country = document.getElementById("country-select");
	var countryID = country.options[country.selectedIndex].value;
	console.log(countryID);
	
	//determine best guess for grading system?
});	
	
	
$("input[name='showBoulder']").click(function()
{
	if ($(this).is(':checked')) {
		$("#boulderprefs").show();
	}
	else {
		$("#boulderprefs").hide();
	}
});	
	
$("input[name='showTR']").click(function()
{
	if ($(this).is(':checked')) {
		$("#trprefs").show();
	}
	else {
		$("#trprefs").hide();
	}
});	

$("input[name='showLead']").click(function()
{
	if ($(this).is(':checked')) {
		$("#leadprefs").show();
	}
	else {
		$("#leadprefs").hide();
	}
});	
});



