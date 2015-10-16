<?php

//TODO: Replace with phpunit
include "../core/bootstrap.php";

$root = realpath("../core/bootstrap.php");
echo $root;

$regService = new RegistrationService();
var_dump($regService->registerUser("tester8","test","tester8@gmail.com"));

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


$WorkoutDAO = new WorkoutDAO();
$workout_segments = array(
	array("climbType"=>"boulder","ascentType"=>"flash","absGradeIndex"=>1,"reps"=>1),
	array("climbType"=>"toprope","ascentType"=>"project","absGradeIndex"=>9,"reps"=>5),
	array("climbType"=>"lead","ascentType"=>"redpoint","absGradeIndex"=>4,"reps"=>6),
	array("climbType"=>"boulder","ascentType"=>"onsight","absGradeIndex"=>13,"reps"=>2)
);
var_dump($WorkoutDAO->calcWorkoutPoints($workout_segments));
?>