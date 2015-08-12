<?php

//connect to database
include '../dbconnect.php';
include '../BoulderRouteGradingSystems.php';

$rankBy = $_GET['rankBy']; //{'grade','points'}
$climbType = $_GET['climbType']; //{'boulder','TR','lead'}
$timeFrame = $_GET['timeFrame']; //{'week,'month','year','alltime'}
$gender = $_GET['gender']; //{'male','female','all'}
$gymid = $_GET['gymid']; //{'-1','0','1',...}
$userid_req = $_GET['userid_req']; //the userid of the person looking at rankings
$timeInterval = 0;
switch ($timeFrame) {
	case "week":
		$timeInterval = 7;
		break;
	case "month":
		$timeInterval = 30;
		break;
	case "year":
		$timeInterval = 365;
		break;
	case "alltime":
		$timeInterval = 90000;
		break;
	default:
		$timeInterval = 30;

}

//get user's preferred grading systems
$stmt = $db->prepare("SELECT * FROM userprefs WHERE userid 
	= :userid_req");
$stmt->execute(array(':userid_req'=>$userid_req));
$userprefs = $stmt->fetch(PDO::FETCH_ASSOC);
//extract grading system used for this workout (use userprefs)
$boulderGradingID = $userprefs['boulderGradingSystemID'];
$routeGradingID = $userprefs['routeGradingSystemID'];

if ($climbType=='TR') {
	$climbType = 'toprope'; //to match database schema
}
//data is JSON-formatted [[1,username1,grade,number],
	//[2,username2,grade,number]],etc.

if ($climbType=='boulder') {
	$climbConversionTable = $boulderConversionTable;
	$gradingSystemID = $boulderGradingID;
}
elseif ($climbType=='toprope' || $climbType=='lead') {
	$climbConversionTable = $routeConversionTable;
	$gradingSystemID = $routeGradingID;
}
	
	
//Get sorted list of users with max grades
if ($gymid == -1) {
	$gymid = '%'; //use wildcard for gym
}

$stmt = $db->prepare("SELECT climbs.username,max_grade,climbs.sum_reps,climbs.userimage FROM
		(SELECT username,max(grade_index) as max_grade 
		FROM users INNER JOIN workouts ON users.userid=workouts.userid 
		INNER JOIN workout_segments ON workouts.workout_id = workout_segments.workout_id 
		WHERE workout_segments.climb_Type = :climbType AND workout_segments.ascent_Type 
		IN ('redpoint','flash','onsight') AND workouts.gymid LIKE (:gymid) AND DATE(workouts.date_workout) BETWEEN DATE_SUB(NOW(), INTERVAL :timeInterval DAY) AND NOW() GROUP BY username) AS topclimbs
		JOIN 
		(SELECT users.username AS username,grade_index,sum(reps) as sum_reps, userdata.userimage AS userimage 
		FROM users INNER JOIN workouts ON users.userid=workouts.userid 
		INNER JOIN workout_segments ON workouts.workout_id = workout_segments.workout_id INNER JOIN 
		userdata ON users.userid = userdata.userid 
		WHERE workout_segments.climb_Type = :climbType2 AND workout_segments.ascent_Type 
		IN ('redpoint','flash','onsight') AND workouts.gymid LIKE (:gymid2) AND DATE(workouts.date_workout) BETWEEN DATE_SUB(NOW(), INTERVAL :timeInterval2 DAY) AND NOW() GROUP BY username,grade_index) AS climbs
	ON (climbs.grade_index = topclimbs.max_grade and climbs.username=topclimbs.username)
	ORDER BY max_grade DESC, sum_reps DESC LIMIT 15");	
	

$stmt->execute(array(':climbType'=>$climbType,':gymid'=>$gymid,':timeInterval'=>$timeInterval,':climbType2'=>$climbType,':gymid2'=>$gymid,':timeInterval2'=>$timeInterval));

//data returned is JSON-formatted [[username1,grade,number,userimage],
	//[username2,grade,number,userimage]],etc.
	//number is the number of climbs at the highest grade (only if they select 
		//highest grade.
$rankings = array();
//convert absolute grade index ($topClimbs[1]) to grade text
while ($topClimbs = $stmt->fetch(PDO::FETCH_NUM)) {
	$rankings[] = array($topClimbs[0],$climbConversionTable[$gradingSystemID][$topClimbs[1]],$topClimbs[2],$topClimbs[3]);
}



$rankingsJSON = json_encode($rankings);

echo $rankingsJSON;
?>