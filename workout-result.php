<?php

//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

include 'saveworkout.php';
//print_r($maxBoulder);
include 'write-records-relative.php';
	
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gym Climbing Tracker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		<script src="js/uservoice.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>

	</head>
	
	<body>
		<?php include_once("php_common/analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div id = "log">
		</div>
		<div>
			<h3>Date: <?php echo $workoutdate ?></h3>
			<h3>You earned: <?php echo round($boulder_points+$tr_points+$lead_points); ?> points!</h3>
		</div>
		
		
	</body>
	
</html>
