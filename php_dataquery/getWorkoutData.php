<?php

function getNumber($db,$userid) {
	//select total number of workout sessions
	$stmt = $db->prepare("SELECT COUNT(*) FROM workouts WHERE userid=:userid");
	$stmt->execute(array(':userid'=>$userid));
	
	$result = $stmt->fetch();
	return $result[0];
}

function getNumGyms($db,$userid) {
	//get number of unique gyms climbed at
	$stmt = $db->prepare("SELECT COUNT(DISTINCT(gymid)) FROM workouts WHERE userid=:userid");
	$stmt->execute(array(':userid'=>$userid));
	
	$result = $stmt->fetch();
	return $result[0];
}

function getNumClimbs($db,$userid) {
	//get number of climbs logged
	$stmt = $db->prepare("SELECT SUM(reps) FROM workout_segments INNER JOIN workouts ON workouts.workout_id = workout_segments.workout_id WHERE workouts.userid=:userid");
	$stmt->execute(array(':userid'=>$userid));
	
	$result = $stmt->fetch();
	return $result[0];
}

function getClimbingFrequency($db,$userid) {
	//get frequency of climbing in last 30 days
	$stmt = $db->prepare("SELECT COUNT(*) FROM workouts WHERE userid=:userid AND date_workout BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()");
	$stmt->execute(array(':userid'=>$userid));
	
	$result = $stmt->fetch();
	return $result[0];
}
?>