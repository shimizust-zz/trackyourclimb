<?php 
	

						
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
			
			<a href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Return to main page.</a>
			
			<div class="container">
				<div class="page-header">
					<h1>About</h1>
				</div>
				
				<div class="lead-text">
				<p>
					Unlike other sports like running and cycling, there haven't been many online training tools developed for climbing. Thus, TrackYourClimb.com was started in September 2014 as a personal project to keep track of my climbing workouts and  progress over time. 
				</p>
				<p>There are a lot of exciting improvements and developments in the works. If you have any ideas, comments or other feedback, please <a href="contact.php">contact me.</a></p>
				<hr><br>
				<p>
					<div id="circular-SSphoto"></div>
					<h4 style="text-align:left;display:inline-block;margin-left:20px"><b>Steven Shimizu</b></h4>
					<p style="display:block;margin-left:220px"><b>{Developer, Climber}</b></p>
					<p style="display:block;margin-left:220px">I started climbing in early 2013 and have been hooked ever since, with bouldering being my primary interest. I'm currently a resident of Brooklyn, New York after finishing school in Cambridge, Massachusetts.</p>
					<p style="clear:both;margin-left:40px;font-size:0.7em;font-style:italic">Credit: Aaron Hwang</p>
				</p>
				
				<br><hr><br>
				<p>
					This site would not have been possible without several pieces of open-source languages, software and other materials which deserve mention:
					<ul>
						<li><a href="http://php.net/">PHP</a></li>
						<li><a href="http://www.mysql.com/">MySQL</a></li>
						<li><a href="http://jquery.com/">JQuery</a></li>
						<li><a href="http://getbootstrap.com/">Bootstrap</a></li>
						<li><a href="http://www.flotcharts.org/">Flot</a></li>
						<li><a href="https://github.com/Serhioromano/bootstrap-calendar">Bootstrap-Calendar</a></li>
						<li><a href="https://phpexcel.codeplex.com/">PHPExcel</a></li>
						<li><a href="http://jqvmap.com/">JQVMap</a></li>
						<li>The Noun Project</li>
						<ul>
							<li><a href="http://thenounproject.com/term/event/37208/">Event Icon</a></li>
							<li><a href="http://thenounproject.com/term/climbing/78370/">Climbing Icon</a></li>
							<li><a href="http://thenounproject.com/term/graph/37874/">Stats Icon</a></li>
						</ul>
					</ul>
					
					
					
				</p>
				<hr>
				<div id="scroll-padding"></div>
				</div>
				
				
			</div>
			
			
			</div>
		</div>
		
		
	</div>
		<?php require("footer.php"); ?>
	</body>
</html>