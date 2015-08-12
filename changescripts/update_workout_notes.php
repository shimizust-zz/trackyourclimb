<?php
/*
//connect to database
include '../dbconnect.php';

//First, select all and update boulder workouts
$stmt = $db->prepare("SELECT * FROM boulder_workouts");
$stmt->execute();


while($workout = $stmt->fetch(PDO::FETCH_NUM)) {
	$workoutid = $workout[0];
	$boulderpoints = $workout[1];
	$bouldernotes = $workout[2];

	$stmt3 = $db->prepare("UPDATE workouts SET boulder_notes=
	:bouldernotes WHERE workout_id=:workoutid");
	$stmt3->execute(array(':bouldernotes'=>$bouldernotes,':workoutid'=>$workoutid));
	
}

$stmt4 = $db->prepare("SELECT * FROM tr_workouts");
$stmt4->execute();

while($workout = $stmt4->fetch(PDO::FETCH_NUM)) {
	$workoutid = $workout[0];
	$trpoints = $workout[1];
	$trnotes = $workout[2];
	
	$stmt5 = $db->prepare("UPDATE workouts SET tr_notes=
	:trnotes WHERE workout_id=:workoutid");
	$stmt5->execute(array(':trnotes'=>$trnotes,':workoutid'=>$workoutid));
}

//update lead workouts
$stmt7 = $db->prepare("SELECT * FROM lead_workouts");
$stmt7->execute();

while($workout = $stmt7->fetch(PDO::FETCH_NUM)) {
	$workoutid = $workout[0];
	$leadpoints = $workout[1];
	$leadnotes = $workout[2];
	
	$stmt8 = $db->prepare("UPDATE workouts SET lead_notes=
	:leadnotes WHERE workout_id=:workoutid");
	$stmt8->execute(array(':leadnotes'=>$leadnotes,':workoutid'=>$workoutid));
}
 * 
 */
?>