//This script plots a stacked bar chart of points per day, stacked by
//climb type.


//JSON is a way of packaging up the php array into a format that
//can be easily recognized by javascript

var options = {

	series: {
		stack: true,
	},
	bars: {
		show:true,
		align: "center",
		barWidth: 86400000*0.7 //a day
	},
	xaxis: {
		mode: "time",
		minTickSize: [1, "day"],
		axisLabel: "Workout Date",
		axisLabelUseCanvas: true,
		axisLabelPadding: 10
	},
	yaxis: {
		axisLabel: "Points"
	},
	legend: {
		position: "nw"
	},
	grid: {
			hoverable: true,
			clickable: true
	},
};

$(function() {
	
	//Compile dataset to plot
	var all_data = [{label:"Bouldering",data:
		 	boulder_points_vs_time,color:"#488FC8"},
		 	{label:"Top-Roping",data:
			TR_points_vs_time,color:"#6CCB17"},
			{label:"Leading",data:
			Lead_points_vs_time,color:"#FFC605"}];

	$.plot($("#placeholder"), all_data,options);
	
	//create a div for the tooltip
	$("<div id='tooltip'></div>").css({
		position: "absolute",
		display: "none",
		border: "1px solid #fdd",
		padding: "2px",
		"background-color": "#fee",
		opacity: 0.80
	}).appendTo("body");
	
	$("#placeholder").bind("plothover", function(event,pos,item) {
		if (item) {
			//how to 
			var y = item.datapoint[1];
			$("#tooltip").html(item.series.label)
			.css({top:item.pageY+5,left:item.pageX+5}).fadeIn(100);
		} else {
			$("#tooltip").hide();
		}
	});
	
});

