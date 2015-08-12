 //This script plots a stacked bar chart of points per day, stacked by
//climb type.


//JSON is a way of packaging up the php array into a format that
//can be easily recognized by javascript


$(function() {

	

	var options2 = {

		series: {
				lines: { show: true },
				points: {
					radius: 3,
					show: true,
					fill: true
				},
			},
		xaxis: {
			mode: "time",
			tickSize: [7,"day"],
			axisLabel: "Workout Date",
			axisLabelUseCanvas: true,
			axisLabelPadding: 10
		},
		yaxis: {
			axisLabel: "Grade",
			ticks: boulder_tick_labels,
			panRange: false
		},
		legend: {
			position: "nw"
		},
		grid: {
				hoverable: true,
				clickable: true
		},
		pan: {
			interactive: true,
			cursor: "move",
			frameRate: 40
		}
		
	};
	
	//Max number of days in the time plot
	var maxViewTimeDays = 120; 
	var msPerDay = 24*3600*1000; //milliseconds per day
	var numTickMarks = 12; //number of tick marks to display
	var minTickSizeDay = 1; //min tick size in days
	
	if (typeof avgboulder_vs_time == 'undefined' || avgboulder_vs_time.length == 0) {
		//no boulder data
		var currEmptyPlot = document.getElementById('bouldervsTime');
		currEmptyPlot.style.height = '50px';
		currEmptyPlot.innerHTML = '<div class="alert alert-warning">No Bouldering Data Recorded</div>';
	} 
	else {
		//get the start timestamp (ms) for bouldering
		var startBTime = avgboulder_vs_time[0][0];
		var endBTime = avgboulder_vs_time[avgboulder_vs_time.length-1][0];
		
		//get number of days in the range
		var numDaysTotalBoulder = Math.round((endBTime-startBTime)/msPerDay);
		var tickSize = Math.max(minTickSizeDay,Math.round(numDaysTotalBoulder/numTickMarks));
		options2.xaxis.tickSize = [tickSize,"day"];
		
		/*
		//Use these options if you want to pan
		options2.xaxis.panRange = [startBTime,endBTime];
		options2.xaxis.min = Math.max(endBTime-maxViewTimeDays*msPerDay,startBTime);
		options2.xaxis.max = endBTime;
		*/
		
		//Compile dataset to plot
		var all_data = [{label:"Average Bouldering Grade",data:
				avgboulder_vs_time,points: { symbol: "circle", fillColor: "#058DC7" },color:"#488FC8"},
				{label:"Highest Bouldering Grade",data:
				highestboulder_vs_time,points: { symbol: "circle", fillColor: "#50B432" },color:"#6CCB17"}];

		$.plot($("#bouldervsTime"), all_data,options2);
		
		//create a div for the tooltip
		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");
		
		$("#bouldervsTime").bind("plothover", function(event,pos,item) {
			if (item) {
				//how to 
				var y = Math.round(item.datapoint[1]);
				$("#tooltip").html(boulderRatings[boulderGradingID][y])
				.css({top:item.pageY-20,left:item.pageX+15}).fadeIn(100);
			} else {
				$("#tooltip").hide();
			}
		});
	}
	

	if (typeof avgtoprope_vs_time == 'undefined' || avgtoprope_vs_time.length == 0) {
		//no top rope data
		var currEmptyPlot = document.getElementById('topropevsTime');
		currEmptyPlot.style.height = '50px';
		currEmptyPlot.innerHTML = '<div class="alert alert-warning">No Top Rope Data Recorded</div>';
	} 
	else {
		//get the start timestamp (ms) for toproping
		var startTRTime = avgtoprope_vs_time[0][0];
		var endTRTime = avgtoprope_vs_time[avgtoprope_vs_time.length-1][0];
		
		//get number of days in the range
		var numDaysTotalTopRope = Math.round((endTRTime-startTRTime)/msPerDay);
		var tickSize = Math.max(minTickSizeDay,Math.round(numDaysTotalTopRope/numTickMarks));
		options2.xaxis.tickSize = [tickSize,"day"];
		
		//Compile toprope dataset to plot
		var all_data = [{label:"Average Top-Rope Grade",data:
				avgtoprope_vs_time,points: { symbol: "circle", fillColor: "#058DC7" },color:"#488FC8"},
				{label:"Highest TopRope Grade",data:
				highesttoprope_vs_time,points: { symbol: "circle", fillColor: "#50B432" },color:"#6CCB17"}];
		
		options2.yaxis.ticks = toprope_tick_labels;
		$.plot($("#topropevsTime"), all_data,options2);
		
		//create a div for the tooltip
		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");
		
		$("#topropevsTime").bind("plothover", function(event,pos,item) {
			if (item) {
				//how to 
				var y = Math.round(item.datapoint[1]);
				$("#tooltip").html(routeRatings[routeGradingID][y])
				.css({top:item.pageY-20,left:item.pageX+15}).fadeIn(100);
			} else {
				$("#tooltip").hide();
			}
		});
	}
	
	
	if (typeof avglead_vs_time == 'undefined' || avglead_vs_time.length == 0) {
		//no lead data
		var currEmptyPlot = document.getElementById('leadvsTime');
		currEmptyPlot.style.height = '50px';
		currEmptyPlot.innerHTML = '<div class="alert alert-warning">No Lead Data Recorded</div>';
	} 
	else {
		//get the start timestamp (ms) for lead
		var startLTime = avglead_vs_time[0][0];
		var endLTime = avglead_vs_time[avglead_vs_time.length-1][0];
		
		//get number of days in the range
		var numDaysTotalLead = Math.round((endLTime-startLTime)/msPerDay);
		var tickSize = Math.max(minTickSizeDay,Math.round(numDaysTotalLead/numTickMarks));
		options2.xaxis.tickSize = [tickSize,"day"];
		
		//Compile lead dataset to plot
		var all_data = [{label:"Average Lead Grade",data:
				avglead_vs_time,points: { symbol: "circle", fillColor: "#058DC7" },color:"#488FC8"},
				{label:"Highest Lead Grade",data:
				highestlead_vs_time,points: { symbol: "circle", fillColor: "#50B432" },color:"#6CCB17"}];
		
		options2.yaxis.ticks = lead_tick_labels;
		$.plot($("#leadvsTime"), all_data,options2);
		
		//create a div for the tooltip
		$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");
		
		$("#leadvsTime").bind("plothover", function(event,pos,item) {
			if (item) {
				//how to 
				var y = Math.round(item.datapoint[1]);
				$("#tooltip").html(routeRatings[routeGradingID][y])
				.css({top:item.pageY-20,left:item.pageX+15}).fadeIn(100);
			} else {
				$("#tooltip").hide();
			}
		});
	}
	
});

