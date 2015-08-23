<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	
		 		
				
				
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
		

		
		<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.7.0/underscore-min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="bower_components/bootstrap-calendar/js/calendar.js"></script>
		<script src="js/uservoice.js"></script>
		
		<link rel="stylesheet" type="text/css" href="bower_components/bootstrap-calendar/css/calendar.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">

	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("php_common/analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
				
				<div class="container" style="margin-bottom:20px">
				<h3 class="page-header"></h3>
				<div class="pull-right form-inline">
					<div class="btn-group">
						<button class="btn btn-primary" data-calendar-nav="prev"><< Prev</button>
						<button class="btn" data-calendar-nav="today">Today</button>
						<button class="btn btn-primary" data-calendar-nav="next">Next >></button>
					</div>
					<div class="btn-group">
						<button class="btn btn-warning" data-calendar-view="year">Year</button>
						<button class="btn btn-warning active" data-calendar-view="month">Month</button>
						<button class="btn btn-warning" data-calendar-view="week">Week</button>
					</div>
				</div>
				</div>
				
				
				<div id="calendar"></div>
				</div>
				
				
				
				
				<script type="text/javascript">
					var calendar = $("#calendar").calendar(
						{
							tmpl_path: "bower_components/bootstrap-calendar/tmpls/",
							events_source: function () { return []; }
						});         
				</script>
				
			</div>
		</div>
		
		<script src="js/calendar_app_customized.js"></script>
		<div id="scroll-padding"></div>
		<?php require("php_common/footer.php"); ?>
	</body>
</html>
