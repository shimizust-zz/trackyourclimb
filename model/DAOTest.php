<?php

//TODO: Replace with phpunit
include "../core/bootstrap.php";

$dbHelper = new DBHelper();
$propKeys = array("prop1","prop2","prop3");
echo $dbHelper::genPlaceholderList($propKeys)."\n\n";


$userService = new UserService();
var_dump($userService->registerUser("tester8","test","tester8@gmail.com"));

$CDAO = new ClimbingAreaDAO();
$areaid = 7;
$indoor = 1;
var_dump($CDAO->climbingAreaExists($areaid, $indoor));


$UserDAO = new UserDAO();
echo $UserDAO->getNumUsers();

var_dump($UserDAO->getUserPrefs(954));
var_dump($UserDAO->setUserPrefs(954,array("show_boulder"=>1, "minL"=>3)));
var_dump($UserDAO->getUserPrefs(954));

var_dump($UserDAO->getUserProfile(954));
var_dump($UserDAO->getUserRecords(954));


$WorkoutService = new WorkoutLoggingService();
$workout_segments_abs = array(
	array("climb_type"=>"boulder","ascent_type"=>"flash","grade_index"=>1,"reps"=>1),
	array("climb_type"=>"toprope","ascent_type"=>"project","grade_index"=>9,"reps"=>5),
	array("climb_type"=>"lead","ascent_type"=>"redpoint","grade_index"=>11,"reps"=>6),
	array("climb_type"=>"boulder","ascent_type"=>"onsight","grade_index"=>13,"reps"=>2)
);
var_dump($WorkoutService->calcWorkoutPoints($workout_segments_abs));


// Save a workout
$DBManager = new DBConnectionManager();
$db = $DBManager->connect();



// userid, date_workout, gymid, boulder_notes, 
// tr_notes, lead_notes, other_notes
$userid = 4;

$workout_info =  array("userid"=>$userid, "date_workout"=>"2015-10-23", "gymid"=>1, 
		"boulder_notes"=>"bouldering!", "tr_notes"=>"tr rocks", "lead_notes"=>"",
		"other_notes"=>"other notes!!!");

$workout_info["boulder_points"] = 200;
$workout_info["TR_points"] = 300;
$workout_info["Lead_points"] = 400;

$userrecordDAO = new UserRecordsDAO();
var_dump($userrecordDAO->getUserRecords($userid));

$results = $WorkoutService->saveWorkoutAbsGrades($workout_info, $workout_segments_abs);
echo $results;

//$grading_systems = array("boulder"=>2,"route"=>4);

//$results = $WorkoutService->saveWorkoutRelGrades($workout_info, $workout_segments_abs, $grading_systems);

//$gradingConversionService = new GradingConversionService();
//$result =  $gradingConversionService->convertRelToAbsGradeIndex("boulder", 2, 3);
//var_dump($result);

$userrecordDAO = new UserRecordsDAO();
var_dump($userrecordDAO->getUserRecords($userid));
?>



































