<?php
include "./core/bootstrap.php";

include 'dbconnect.php';
//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

//Read in submitted workout and write to workout tables
$userService = new UserService();
$climbingAreaService = new ClimbingAreaService();
$workoutLoggingService = new WorkoutLoggingService();

//check that register form is submitted 
if (isset($_POST['workoutsubmit'])) {
	
	$workoutdate = $_POST['workoutdate']; 
	$gymid = $_POST['gymid'];
	
	if (isset($_POST['default-gym'])) {
		//check if indoor gym or outdoor crag
		//update default climbing area accordingly
		if (isset($_POST['gym-indoor'])) {
			$_POST["gym-indoor"] ? $userService->setUserMainGym($userid, $gymid) 
				: $userService->setUserMainCrag($userid, $gymid); 
		}
		
		//set default country to that of the chosen climbing area
		$countryCode = $climbingAreaService->getCountryCode($gymid);
		$userService->setUserCountryCode($userid, $countryCode);
	}

	//Extract user's current preferred grading system 
	$gradingSystems = $userService->getUserGradingSystems($userid);
	$boulderGradingSystemID = $gradingSystems["boulder"];
	$routeGradingSystemID = $gradingSystems["route"];
	
	//extract notes on each climbing type
	$boulderNotes = $_POST['boulderNotes'];
	$TRNotes = $_POST['TRNotes'];
	$LeadNotes = $_POST['LeadNotes'];
	$OtherNotes = $_POST['OtherNotes'];
	
	$workoutInfo = array(
		"userid"=>$userid,
		"date_workout"=>$workoutdate,
		"gymid"=>$gymid,
		"boulder_notes"=>$boulderNotes,
		"tr_notes"=>$TRNotes,
		"lead_notes"=>$LeadNotes,
		"other_notes"=>$OtherNotes
	);
	
	
	$workoutSegmentsRel = $workoutLoggingService->getWorkoutSegmentsRelFromPost($_POST, $boulderGradingSystemID, $routeGradingSystemID);
	$gradingSystems = ["boulder"=>$boulderGradingSystemID, "route"=>$routeGradingSystemID];
	$workoutResult = $workoutLoggingService->saveWorkoutRelGrades($workoutInfo, $workoutSegmentsRel, $gradingSystems);
	
	$boulder_points = $workoutResult["boulderPoints"];
	$tr_points = $workoutResult["trPoints"];
	$lead_points = $workoutResult["leadPoints"];
	
	
}
else {
	header("Location: workout-input.php");
}
	
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
