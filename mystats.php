<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

include 'BoulderRouteGradingSystems.php';

$stmt = $db->prepare("SELECT workout_id as firstworkoutid,date_workout,sum(boulder_points) AS
totalboulder_points, sum(TR_points) AS totalTR_points, 
sum(Lead_points) as totalLead_points FROM 
users INNER JOIN workouts ON users.userid=workouts.userid WHERE
users.userid=:userid GROUP BY workouts.date_workout ORDER BY
workouts.date_workout ASC");
$stmt->execute(array(':userid'=>$userid));
$pointstimeresults = $stmt->fetchAll(PDO::FETCH_ASSOC);

$boulder_series = array();
$TR_series = array();
$Lead_series = array();

foreach ($pointstimeresults as $row) {
	$workout_date = date_create_from_format('Y-m-d H:i:s',$row['date_workout']."2:00:00");
	//set time to 2am, as that seems to center the bars
	
	$workout_time = (float)date_format($workout_date,'U')*1000;
	
	$workout_time2 = date_format($workout_date,'Y-m-d H:i:s');

	//Note: Need to convert to milliseconds, since flot uses that format
	//which are javascript timestamps. A php int can't store that high of 
	//an int, so cast it to a float
	
	
	$boulder_series[] = array($workout_time,
	(int)$row['totalboulder_points']);
	$TR_series[] = array($workout_time,(int)$row['totalTR_points']);
	$Lead_series[] = array($workout_time,(int)$row['totalLead_points']);
	//data need to be an array of 2-element arrays, where each
	//2-element array contains an x and y value, in order.
	
	//First, initialize an array. The square brackets during each
	//assignment add the array of x,y pairs to the end of the array.
	//You're left with a 2D array, essentially
	
	//cast to an int. Fetching seems to autoconvert to string, which
	//flot does not plot well
}

$boulder_json = json_encode($boulder_series);
$TR_json = json_encode($TR_series);
$L_json = json_encode($Lead_series);

//Now, extract the total number of points for all climbing types since beginning
$stmt = $db->prepare("SELECT sum(boulder_points) as totalboulder_points,
sum(TR_points) AS totalTR_points, sum(Lead_points) as totalLead_points FROM
users INNER JOIN workouts ON users.userid=workouts.userid WHERE 
users.userid=?");
$stmt->execute(array($userid));
$totalPointResults = $stmt->fetch(PDO::FETCH_ASSOC);
$totalBoulderPoints = $totalPointResults['totalboulder_points'];
$totalTRPoints = $totalPointResults['totalTR_points'];
$totalLeadPoints = $totalPointResults['totalLead_points'];

//extract their user preferences
$stmt = $db->prepare("SELECT * FROM userprefs WHERE userid 
	= '$userid'");
$stmt->execute();
$userprefs = $stmt->fetch(PDO::FETCH_ASSOC);

//extract grading system used for this workout (use userprefs)
$boulderGradingID = $userprefs['boulderGradingSystemID'];
$routeGradingID = $userprefs['routeGradingSystemID'];

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

		<script src="bower_components/flot/jquery.flot.js"></script>		
		<script src="bower_components/flot/jquery.flot.stack.js"></script>
		<script src="bower_components/flot/jquery.flot.time.js"></script>
		<script src="bower_components/flot/jquery.flot.pie.js"></script>
		<script src="bower_components/flot-axislabels/jquery.flot.axislabels.js"></script>
		<script src="bower_components/flot/jquery.flot.categories.js"></script>
		<script src="bower_components/flot-tickrotor/jquery.flot.tickrotor.js"></script>
		<script src="bower_components/flot/jquery.flot.navigate.js"></script>
		<script src="bower_components/flot/jquery.flot.resize.js"></script>
		<script src="bower_components/flot/jquery.flot.symbol.js"></script>
		
		
		<script src="js/sitePath.js"></script>
		<script src="js/uservoice.js"></script>
		<script src="js/BoulderRouteGradingSystems.js"></script>
		<script>
			var boulderGradingID = <?php echo $boulderGradingID ?>;
			var routeGradingID = <?php echo $routeGradingID ?>;
		</script>
		<script src="js/plots/get_update_ClimbDataAll.js"></script>
		
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<?php include_once("php_common/analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div class="container">
			<div id="log"><?php  ?></div>
			<div class="page-header"><h1>My Stats</h1></div>
			<div>
				<!-- Generate user records table -->
				<?php include 'highestClimbDataTable.php'; ?>
			</div>
			
			
			<?php include 'php_dataquery/getClimbvsTime.php'; ?>
			<script type="text/javascript">
				var avgboulder_vs_time = <?php echo $boulder_avg_timseries_json ?>;
				var highestboulder_vs_time = <?php echo $boulder_max_timseries_json ?>;
		
				//compute ticks corresponding with grading system
				var boulder_tick_labels = <?php echo $boulder_tick_labels ?>;
				
				
				var avgtoprope_vs_time = <?php echo $toprope_avg_timseries_json ?>;
				var highesttoprope_vs_time = <?php echo $toprope_max_timseries_json ?>;
		
				//compute ticks corresponding with grading system
				var toprope_tick_labels = <?php echo $toprope_tick_labels ?>;
				
				
				var avglead_vs_time = <?php echo $lead_avg_timseries_json ?>;
				var highestlead_vs_time = <?php echo $lead_max_timseries_json ?>;
		
				//compute ticks corresponding with grading system
				var lead_tick_labels = <?php echo $lead_tick_labels ?>;
			</script>
			<script type="text/javascript" src="js/climbvsTimePlot.js"></script>
			<h3>Bouldering Progression</h3>
			<div class="climbvsTime" id="bouldervsTime">
				<!--Plot average and highest boulder grades vs time (workout points-->
				
			</div>
			
			<h3>Top-Rope Progression</h3>
			<div class="climbvsTime" id="topropevsTime">
				<!--Plot average and highest toprope grades vs time (workout points-->
				
			</div>
			
			<h3>Lead Progression</h3>
			<div class="climbvsTime" id="leadvsTime">
				<!--Plot average and highest lead grades vs time (workout points-->
				
			</div>
			
			<h3>Bouldering Grade Distribution</h3>
			<div class="histogram-div" id="bouldergradehistogram-div">
				<select id="ascentSelect" onchange="getTotalBoulderData(this.value,<?php echo $userid; ?>,<?php echo $userid; ?>,'-1')">
					<option value="Project" >Attempt</option>
					<option value="Redpoint">Redpoint</option>
					<option value="Flash">Flash</option>
					<option value="Onsight">Onsight</option>
					<option value="RFO" selected>>=Redpoint</option>
				</select>
				<div class="gradehistogram" id="bouldergradehistogram">
					<!--Plot bouldering grade distribution-->
				</div>
			</div>
			
			<h3>Top Rope Grade Distribution</h3>
			<div class="histogram-div" id="trgradehistogram-div">
				<select id="ascentSelect" onchange="getTotalTRData(this.value,<?php echo $userid; ?>,<?php echo $userid; ?>,'-1')">
					<option value="Project" >Attempt</option>
					<option value="Redpoint">Redpoint</option>
					<option value="Flash">Flash</option>
					<option value="Onsight">Onsight</option>
					<option value="RFO" selected>>=Redpoint</option>
				</select>
				<div class="gradehistogram" id="trgradehistogram">
					<!--Plot toprope grade distribution-->
				</div>
			</div>
			
			<h3>Lead Grade Distribution</h3>
			<div class="histogram-div" id="leadgradehistogram-div">
			<select id="ascentSelect" onchange="getTotalLeadData(this.value,<?php echo $userid; ?>,<?php echo $userid; ?>,'-1')">
				<option value="Project" >Attempt</option>
				<option value="Redpoint">Redpoint</option>
				<option value="Flash">Flash</option>
				<option value="Onsight">Onsight</option>
				<option value="RFO" selected>>=Redpoint</option>
			</select>
			<div class="gradehistogram" id="leadgradehistogram">
				<!--Plot lead grade distribution-->
			</div>
			</div>
			
			<script type="text/javascript" >
				//pass datasets to javascript
				var boulder_points_vs_time = <?= $boulder_json ?>; 
				var TR_points_vs_time = <?= $TR_json ?>;
				var Lead_points_vs_time = <?= $L_json ?>;
				var totalBoulderPoints = <?= empty($totalBoulderPoints) ? 0 : $totalBoulderPoints ?>;
				var totalTRPoints = <?= empty($totalTRPoints) ? 0 : $totalTRPoints ?>;
				var totalLeadPoints = <?= empty($totalLeadPoints) ? 0 : $totalLeadPoints ?>;
			</script>
			<script type="text/javascript" src="js/stackedPointPlotvsTime.js"></script>
			<script type="text/javascript" src="js/climbingTypePieChart.js"></script>
			
			<h3>Points vs. Time</h3>
			<div id="placeholder">
					<!--insert bar plot of workout points vs past days-->
			</div>
			
			<h3>Climbing Type Distribution</h3>
			<div id="piechartplaceholder">
				Activities by Point Totals:
			</div>
			
			
	
		</div>
		
		<p id = "scroll-padding"></p>
		
</body>

<script>
	getTotalBoulderData('RFO',<?php echo $userid; ?>,<?php echo $userid; ?>,'-1');
	getTotalTRData('RFO',<?php echo $userid; ?>,<?php echo $userid; ?>,'-1');
	getTotalLeadData('RFO',<?php echo $userid; ?>,<?php echo $userid; ?>,'-1');
</script>

</html>

