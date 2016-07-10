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
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>
	
		<script src="js/BoulderRouteGradingSystems.js"></script>
		
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("php_common/analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
					<div class="page-header">
						<h1>Statistics FAQ</h1>
					</div>
					
					<ul>
						<li id="grading-system-conversion">
							<h3 class="text-left">What is the conversion between different grading systems?</h3>
							<p>All grades in each grading system are mapped to an absolute grade index according to the following tables:</p><br>
							
							<h4 class="text-left">Boulder Grading Systems</h4>
							<table class="table table-bordered" id="boulder-grading-table">
							</table>
							
							<h4 class="text-left">Route Grading Systems</h4>
							<table class="table table-bordered" id="route-grading-table">
							</table>
						</li><br>
						<li id="points-calculation">
							<h3 class="text-left">How are points calculated?</h3>
							<p>Points are meant to roughly represent the amount of effort exerted in a climbing workout. For each climb, points are calculated using the following formula:</p>
							<p class="equation-block">
								Points [for a specific grade/climb type/ascent type] = CTF * (AGI + 0.5) * GNF * AF * N * 100<br><br>
								Total Points = &Sigma;(Points)
							</p>
							<p>
							where
							</p>
							<div class="equation-block">
								<ul>
									<li><b>CTF</b> = climbing type factor, accounts for differences between climbing types<br>
										<table class="table table-bordered equation-table">
											<tr>
												<th></th>
												<th>Boulder</th>
												<th>Top-Rope</th>
												<th>Lead</th>
											</tr>
											<tr>
												<td>CTF</td>
												<td>1.0</td>
												<td>1.5</td>
												<td>2.0</td>
											</tr>
										</table>
									</li>
									<li><b>AGI</b> = absolute grade index, corresponding to the table shown above<br>
									<b>0 - 22</b> for bouldering, <b>0 - 30</b> for roped climbing</li>
									<li><b>GNF</b> = grade normalization factor, accounts for the difference in absolute grade ranges between bouldering and roped climbing.<br>
									Equals <b>1.0</b> for bouldering and <b>22/30 = 0.733</b> for roped climbing.</li>
									<li><b>AF</b> = ascent factor, accounts for differences between different ascent types<br>
										<table class="table table-bordered equation-table">
											<tr>
												<th></th>
												<th>Attempt</th>
												<th>Redpoint</th>
												<th>Flash</th>
												<th>Onsight</th>
											</tr>
											<tr>
												<td>AF</td>
												<td>0.5</td>
												<td>1.0</td>
												<td>1.15</td>
												<td>1.2</td>
											</tr>
										</table>
									</li>
									<li><b>N</b> = number of repetitions of this specific grade/climb type/ascent type</li>
								</ul>
								
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
		
		<p id = "scroll-padding"></p>
		
		<?php require("php_common/footer.php"); ?>
	</body>
	
	<script>
		function getGradingSystemHtml(gradingSystemNames, gradingConversionTable) {
			var gradingTableHtml = '';
			
			gradingTableHtml += "<tr><th>Absolute Grade Index</th>";
			for (var key in gradingSystemNames) {
				gradingTableHtml += "<th>" + gradingSystemNames[key] + "</th>";
			}
			gradingTableHtml += "</tr>";
			
			for (var i = 0; i < gradingConversionTable[0].length; i++) {
				gradingTableHtml += "<tr>";
				
				// absolute grade
				gradingTableHtml += "<td>" + i + "</td>";
				for (var key in gradingConversionTable) {
					gradingTableHtml += "<td>" + gradingConversionTable[key][i] + "</td>";
				}		
				gradingTableHtml += "</tr>";
			}		
			return gradingTableHtml;
		}
		
		var boulderTableHtml = getGradingSystemHtml(boulderGradingSystems, boulderConversionTable),
			routeTableHtml = getGradingSystemHtml(routeGradingSystems, routeConversionTable);
		
		document.getElementById('boulder-grading-table').innerHTML = boulderTableHtml;
		
		document.getElementById('route-grading-table').innerHTML = routeTableHtml;
		
		
	</script>
</html>
