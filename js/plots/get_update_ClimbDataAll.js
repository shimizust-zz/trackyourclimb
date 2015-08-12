function getTotalBoulderData(ascentType,userid,userid_req,gymid) {
	//call PHP function with these parameters to get data of
	//distribution of all climbs for a specific userid (userid = -1 to look
	//at all users)
	$.ajaxSetup({cache:false});
	climbType1 = 'boulder';
	url1 = sitePath()+"/php_dataquery/getTotalClimbDataAll.php?dataq=totalnum"+
		"&climbType="+climbType1+"&ascentType="+ascentType+"&userid="+userid+"&userid_req="+userid_req+"&gymid="+gymid;
	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: url1,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function

				updateClimbDataAll(data,'totalnum',climbType1);
			},
			timeout: 8000,
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
	});
}

function getTotalTRData(ascentType,userid,userid_req,gymid) {
	//call PHP function with these parameters to get data of
	//distribution of all climbs for a specific userid (userid = -1 to look
	//at all users)
	$.ajaxSetup({cache:false});
	climbType2 = 'tr';
	url2 = sitePath()+"/php_dataquery/getTotalClimbDataAll.php?dataq=totalnum"+
		"&climbType="+climbType2+"&ascentType="+ascentType+"&userid="+userid+"&userid_req="+userid_req+"&gymid="+gymid;
	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: url2,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function

				updateClimbDataAll(data,'totalnum',climbType2);
			},
			timeout: 8000,
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
	});
}

function getTotalLeadData(ascentType,userid,userid_req,gymid) {
	//call PHP function with these parameters to get data of
	//distribution of all climbs for a specific userid (userid = -1 to look
	//at all users)
	$.ajaxSetup({cache:false});
	climbType3 = 'lead';
	url3 = sitePath()+"/php_dataquery/getTotalClimbDataAll.php?dataq=totalnum"+
		"&climbType="+climbType3+"&ascentType="+ascentType+"&userid="+userid+"&userid_req="+userid_req+"&gymid="+gymid;
	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: url3,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function

				updateClimbDataAll(data,'totalnum',climbType3);
			},
			timeout: 8000,
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
	});
}


function getHighestClimbDataAll(climbType,ascentType,userid_req) {
	//call PHP function with these parameters to get data
	$.ajaxSetup({cache:false});
	
	url4 = sitePath()+"/php_dataquery/getHighestClimbDataAll.php?dataq=highest"+
		"&climbType="+climbType+"&ascentType="+ascentType+"&userid_req="+userid_req;
	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: url4,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function
				
				updateClimbDataAll(data,'highest',climbType);
			},
			timeout: 8000,
			error: function(jqXHR, textStatus, errorThrown){
				alert(textStatus);
			}
		});
	});
}

function updateClimbDataAll(data,dataq,climbType) {
	//data is JSON-formatted [[3,5],[2,3]],etc.

	//these options should be the same across graphs
	var options = {
		bars: {
			show:true,
			barWidth:1
		},
		xaxis: {
			axisLabel: "Grade",
			axisLabelUseCanvas: true,
		},
		yaxis: {
			axisLabel: "",
		}
	};
	
	options.xaxis.mode = "categories";
	if (climbType=='tr'||climbType=='lead') {
		//if formatted like [["<=5.5",23],...], so TR or lead
		options.bars.align = "right";
		
		options.xaxis.axisLabelPadding = 20;
		options.xaxis.rotateTicks = 90;
	} else {
		options.bars.align = "center";
		//options.xaxis.tickSize = 1;
		//options.xaxis.tickDecimals = 0;
		options.xaxis.axisLabelPadding = 10;
	}
	
	if (dataq == 'totalnum') {
		options.yaxis.axisLabel = "Number of Climbs";
		$(function() {
			$.plot($("#"+climbType+"gradehistogram"),[data],options);
		});
	}	
	else if (dataq=='highest'){
		options.yaxis.axisLabel = "Number of Climbers";
		$(function() {
			$.plot($("#highest"+climbType+"gradeplot_all"),[data],options);
		});
	}
	

}