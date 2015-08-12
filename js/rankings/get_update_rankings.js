function getboulderRankingData(rankBy,climbType,timeFrame,gender,gymid,userid_req) {
	//call PHP function with these parameters to get data
	$.ajaxSetup({cache:false});
	
	//dataq is {'grade','points'} (which parameters by which to rank)
	//climbType = {'boulder','TR','lead'}
	//timeFrame = {'week','month','year','alltime'}
	climbTypeURL = ucfirst(climbType);

	urlbrank = sitePath()+"/php_dataquery/getRankingData.php?rankBy="+rankBy+
	"&climbType="+climbType+"&timeFrame="+timeFrame+"&gender="+gender+
	"&gymid="+gymid+"&userid_req="+userid_req;


	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: urlbrank,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function
				updateRankingData(data,rankBy,climbType,timeFrame,gender,gymid);
			},
			error: function(){
				alert('Error in AJAX call');
			}
		});
	});
	
}

function getTRRankingData(rankBy,climbType,timeFrame,gender,gymid,userid_req) {
	//call PHP function with these parameters to get data
	$.ajaxSetup({cache:false});
	
	//dataq is {'grade','points'} (which parameters by which to rank)
	//climbType = {'boulder','TR','lead'}
	climbTypeURL = ucfirst(climbType);

	urltrank = sitePath()+"/php_dataquery/getRankingData.php?rankBy="+rankBy+
	"&climbType="+climbType+"&timeFrame="+timeFrame+"&gender="+gender+
	"&gymid="+gymid+"&userid_req="+userid_req;


	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: urltrank,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function
				updateRankingData(data,rankBy,climbType,timeFrame,gender,gymid);
			},
			error: function(){
				alert('Error in AJAX call');
			}
		});
	});
	
}

function getleadRankingData(rankBy,climbType,timeFrame,gender,gymid,userid_req) {
	//call PHP function with these parameters to get data
	$.ajaxSetup({cache:false});
	
	//dataq is {'grade','points'} (which parameters by which to rank)
	//climbType = {'boulder','TR','lead'}
	climbTypeURL = ucfirst(climbType);

	urllrank = sitePath()+"/php_dataquery/getRankingData.php?rankBy="+rankBy+
	"&climbType="+climbType+"&timeFrame="+timeFrame+"&gender="+gender+
	"&gymid="+gymid+"&userid_req="+userid_req;


	
	//Note: Wrapping the function update in $(); means it will execute once 
	//the DOM is ready	
	$(function update() {
		$.ajax({
			url: urllrank,
			dataType: 'json',
			success: function(data) {
				//Note: inner functions have access to variables in outer function
				updateRankingData(data,rankBy,climbType,timeFrame,gender,gymid);
			},
			error: function(){
				alert('Error in AJAX call');
			}
		});
	});
	
}
function updateRankingData(data,rankBy,climbType,timeFrame,gender,gymid) {
	//data is JSON-formatted [[username1,grade,number],
	//[username2,grade,number]],etc.
	//number is the number of climbs at the highest grade (only if they select 
		//highest grade.
		
	//Now replace the contents of the table
	var tableID = '#'+climbType+"RankingsTable";
	
	var tableContent = "<tr id='rankings-header'><th class='col-sm-1'>Rank</th><th class='col-sm-5'>Username</th><th class='col-sm-3'>Highest Grade Climbed (Redpoint or better)"+
			"</th><th class='col-sm-3'>Number Climbed at Grade</th></tr>";

	for (var i = 0; i < data.length; i++) {
		userURL = sitePath()+"/publicprofile.php?username="+data[i][0];
		
		//check if a valid user image file exists
		/*
		tableContent += "<tr><td>"+(i+1)+"</td><td><div class='userimage-thumb' style='width:42px;height:42px;overflow:hidden'><img src='userimages/"+data[i][3]+"'  onError='this.src=\"images/default_user.png\";' height='42' /></div><a href="+userURL+">"+data[i][0]+"</a></td><td>"+data[i][1]+
			"</td><td>"+data[i][2]+"</td></tr>";
			*/
			
		var userimage;
		if (data[i][3]==null) {
			userimage = "images/default_user.png";
		} else {
			userimage = "userimages/" + data[i][3];
		}
		
		tableContent += "<tr><td>"+(i+1)+"</td><td id='td-username'><a href="+userURL+"><div class='userimage-thumb middle' style='background-image: url("+userimage+")'> </div><span>"+data[i][0]+"</span></a></td><td>"+data[i][1]+
			"</td><td>"+data[i][2]+"</td></tr>";
			

			
	}
	$(function() {
		$(tableID).html(tableContent);
	});
	
	

}

function ucfirst(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}