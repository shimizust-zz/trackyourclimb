<?php
include "./core/bootstrap.php";

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
	
// 	$workout_info is an assc. array of non-climb data for the workout:
// 	* array(userid, date_workout, gymid, boulder_notes, tr_notes, lead_notes, other_notes)
	
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