<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	
		 		
include 'genDefaultRankingTable.php';				

include 'getWorkoutData.php';		
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
		<script src="js/sitePath.js"></script>
		<script src="js/rankings/get_update_rankings.js"></script>
		<script src="js/uservoice.js"></script>
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
	</head>

	<body>

		<div id="wrap">
			<div id="main">
			<?php include_once("analyticstracking.php") ?>
			<?php require("navigation.php"); ?>
			
			<!--website content here-->
			
			<div id="container-main">
				<div class="page-header">
					<h1>TrackYourClimb Dashboard</h1>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<a href="workout-input.php" class="alink-nounderline">
							<div class="outer-block blue-block" style="color:#FFFFFF;text-align:center">
								<div id="block-content">
									<img src="images/icon_78370.png" style="width:15%">
									<h4 style="display:inline;margin-left:20px">LOG A WORKOUT</h4>
								</div>
							</div>
						</a>
					</div>
					
					<div class="col-sm-4">
						<a href="add-event.php" class="alink-nounderline">
							<div class="outer-block green-block" style="color:#FFFFFF;text-align:center">
								<div id="block-content">
									<img src="images/CalendarIcon.png" style="width:15%">
									<h4 style="display:inline;margin-left:20px">ADD AN EVENT</h4>
								</div>
							</div>
						</a>
					</div>
					
					<div class="col-sm-4">
						<a href="mystats.php" class="alink-nounderline">
							<div class="outer-block gold-block" style="color:#FFFFFF;text-align:center">
								<div id="block-content">
									<img src="images/StatsIcon.png" style="width:15%">
									<h4 style="display:inline;margin-left:20px">VIEW YOUR STATS</h4>
								</div>
							</div>
						</a>
					</div>
					
				</div>
				<hr>
				<div class="row">
					<div class="col-sm-3">
						<div class="col-sm-12 dashboard-stat">
							<?php echo getNumber($db,$userid); ?>
							<p class="dashboard-stat-text">Total <br>Workouts</p>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="col-sm-12 dashboard-stat">
							<?php echo getNumClimbs($db,$userid); ?>
							<p class="dashboard-stat-text">Total <br>Climbs</p>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="col-sm-12 dashboard-stat">
							<?php echo getClimbingFrequency($db,$userid); ?>
							<p class="dashboard-stat-text">Workouts <br>Last 30 Days</p>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="col-sm-12 dashboard-stat">
							<?php echo getNumGyms($db,$userid); ?>
							<p class="dashboard-stat-text">Gyms <br>Visited</p>
						</div>
					</div>
				</div>
				<hr>
				<div class="row">
					
					<div class="col-sm-6 col-md-6">
						
						 
						 <div class="col-sm-12">
							
							<label><h4>Site Updates</h4></label>
							<a class="twitter-timeline" href="https://twitter.com/TrackYourClimb" data-widget-id="526603511237074945">Tweets by @TrackYourClimb</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
							
							<hr>
						 </div>
						
						 
					</div>

					<div class="col-sm-6 col-md-6">
						<div class="col-sm-12">
							<?php include 'getUserEvents.php'; ?>
							<label><?php echo $header_event_text ?></label>
							<?php echo $event_result; ?>
							<hr>
						</div>
						<div class="col-sm-12">
							
						 </div>
					</div>
				</div>

			</div>

			</div>
		</div>
		
		
		
		<?php require("footer.php"); ?>
		
	</body>
</html>
