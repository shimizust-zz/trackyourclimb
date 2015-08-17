<?php 

//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	

include 'getNumUsers.php';


?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gym Climbing Tracker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		
		<script src="bower_components/flot/jquery.flot.js"></script>	
		<script src="bower_components/flot-axislabels/jquery.flot.axislabels.js"></script>
		<script src="bower_components/flot/jquery.flot.categories.js"></script>
		<script src="bower_components/flot-tickrotor/jquery.flot.tickrotor.js"></script>
		<script src="bower_components/flot/jquery.flot.resize.js"></script>
		
		<script src="js/sitePath.js"></script>
		<script src="js/uservoice.js"></script>
		<script src="js/plots/get_update_ClimbDataAll.js"></script>

		<link rel="stylesheet" type="text/css" href="css/mycss.css">
	</head>

	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div class="container">
			<div class="page-header">
				<h1>Community Statistics</h1>
			</div>
			
			<ul class="nav nav-tabs" role="tablist" id="sitestats-tabs">
				  <li class="active"><a href="#site-climb-histogram" role="tab" data-toggle="tab">Climb Distribution</a></li>
				  <li><a href="#site-climber-histogram" role="tab" data-toggle="tab">Climber Distribution</a></li>
				  
			</ul>
			
			
			<!-- Tab panes -->
			<div class="tab-content">
				<div class="tab-pane fade in active" id="site-climb-histogram">
					<!--Plot a histogram of all climbing grades (so number of climbs vs. grade)-->
					<h3>Boulder Grade Distribution</h3>
					<div class="histogram-div">
						<select id="ascentSelect" onchange="getTotalBoulderData(this.value,'-1',<?php echo $userid ?>,'-1')">
							<option value="Project" >Attempt</option>
							<option value="Redpoint" >Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "bouldergradehistogram">	
						</div>
					</div>
					
					<h3>Top Rope Grade Distribution</h3>
					<div class="histogram-div">
						<select id="ascentSelect" onchange="getTotalTRData(this.value,'-1',<?php echo $userid ?>,'-1')">
							<option value="Project" >Attempt</option>
							<option value="Redpoint">Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "trgradehistogram">
						</div>
					</div>
					
					<h3>Lead Grade Distribution</h3>
					<div class="histogram-div">
						<select id="ascentSelect" onchange="getTotalLeadData(this.value,'-1',<?php echo $userid ?>,'-1')">
							<option value="Project" >Attempt</option>
							<option value="Redpoint">Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "leadgradehistogram">
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="site-climber-histogram">
					<!--
					<table>
						<tr>
							<td>Number of Users:</td>
							<td><?php echo $numUsers; ?></td>
						</tr>
						<tr></tr>
					</table>
					-->
					
					<!--Plot histogram of highest climbing grade of each type-->
					
					
					<h3>Highest Boulder Grade Climbed</h3>
					<div class="histogram-div">
						<select id="ascentSelect2" onchange="getHighestClimbDataAll('boulder',this.value,<?php echo $userid ?>)">
							<option value="Project" >Attempt</option>
							<option value="Redpoint">Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "highestbouldergradeplot_all">
						</div>
					</div>
					
					<div class="histogram-div">
						<h3>Highest Top-Rope Grade Climbed</h3>
						<select id="ascentSelect2" onchange="getHighestClimbDataAll('tr',this.value,<?php echo $userid ?>)">
							<option value="Project" >Attempt</option>
							<option value="Redpoint">Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "highesttrgradeplot_all">
						</div>
					</div>
					
					<div class="histogram-div">
						<h3>Highest Lead Grade Climbed</h3>
						<select id="ascentSelect2" onchange="getHighestClimbDataAll('lead',this.value,<?php echo $userid ?>)">
							<option value="Project" >Attempt</option>
							<option value="Redpoint">Redpoint</option>
							<option value="Flash">Flash</option>
							<option value="Onsight">Onsight</option>
							<option value="RFO" selected>>=Redpoint</option>
						</select>
						<div class="gradehistogram" id = "highestleadgradeplot_all">
						</div>
					</div>
					
				</div>
			</div>
			<p id = "scroll-padding"></p>
		</div>
</body>

<script>
	getTotalBoulderData('RFO','-1',<?php echo $userid ?>,'-1');
	getTotalTRData('RFO','-1',<?php echo $userid ?>,'-1');
	getTotalLeadData('RFO','-1',<?php echo $userid ?>,'-1');
	//getHighestClimbDataAll('boulder','Redpoint',<?php echo $userid ?>);
	
	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

	  if (e.target.hash == "#site-climber-histogram") {

		  getHighestClimbDataAll('boulder','RFO',<?php echo $userid ?>);
		  getHighestClimbDataAll('tr','RFO',<?php echo $userid ?>);
		  getHighestClimbDataAll('lead','RFO',<?php echo $userid ?>);
	  }
	  
	})

</script>
</html>



