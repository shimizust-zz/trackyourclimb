<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	
		 		
				
				
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
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<!--website content here-->
			</div>
			
			
			
			
		</div>
		
		
		
		<?php require("footer.php"); ?>
	</body>
</html>
