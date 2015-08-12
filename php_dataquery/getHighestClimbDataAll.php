<?php

//connect to database
include '../dbconnect.php';
include '../BoulderRouteGradingSystems.php';


$dataq = $_GET['dataq']; //{'totalnum','highest'}
$climbType = $_GET['climbType']; //{'boulder','tr','lead'}
$ascentType = $_GET['ascentType']; //{'Project','Redpoint','Flash','Onsight','RFO'}
$ascentType = strtolower($ascentType);

$userid_req = $_GET['userid_req']; //this is the userid of the person requesting the stats

//get user's preferred grading systems
$stmt = $db->prepare("SELECT * FROM userprefs WHERE userid 
	= :userid_req");
$stmt->execute(array(':userid_req'=>$userid_req));
$userprefs = $stmt->fetch(PDO::FETCH_ASSOC);
//extract grading system used for this workout (use userprefs)
$boulderGradingID = $userprefs['boulderGradingSystemID'];
$routeGradingID = $userprefs['routeGradingSystemID'];


//$maxGrade denotes max grade to plot
if ($climbType=='boulder') {
	$maxGrade = 19;
	$climbConversionTable = $boulderConversionTable;
	$gradingSystemID = $boulderGradingID;
}
elseif ($climbType=='tr') {
	$climbType = 'toprope';
	$maxGrade = 24; //this is the index
	$climbConversionTable = $routeConversionTable;
	$gradingSystemID = $routeGradingID;
}
elseif ($climbType=='lead') {
	$maxGrade = 24;
	$climbConversionTable = $routeConversionTable;
	$gradingSystemID = $routeGradingID;
}


//initialize climbData_assoc = associative array of [max_grade_text => num_climbers]
$climbData_assoc = array();
for ($i = 0;$i<=$maxGrade;$i++) {
	$grade_text = $climbConversionTable[$gradingSystemID][$i];
	//echo $grade_text;
	$climbData_assoc[$grade_text] = 0;
}

if ($ascentType=='rfo') {
	$query = "SELECT MAX(grade_index) AS max_grade_ind FROM workout_segments 
	INNER JOIN workouts ON workouts.workout_id = workout_segments.workout_id 
	WHERE climb_type= :climbType AND ascent_type IN ('redpoint','flash','onsight') GROUP BY userid ";
	$stmt2 = $db->prepare($query);
	$stmt2->execute(array(':climbType'=>$climbType));
} 
else {
	$query = "SELECT MAX(grade_index) AS max_grade_ind FROM workout_segments 
	INNER JOIN workouts ON workouts.workout_id = workout_segments.workout_id 
	WHERE climb_type= :climbType AND ascent_type = :ascentType GROUP BY userid ";

	$stmt2 = $db->prepare($query);
	$stmt2->execute(array(':climbType'=>$climbType,':ascentType'=>strtolower($ascentType)));
}





while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
	if ($row['max_grade_ind']<=$maxGrade) {
		//get the number of reps and add to the climbData_assoc table. This code groups climbs together that have the same grade_text rating (but different absolute grades)
		$climbData_assoc[$climbConversionTable[$gradingSystemID][$row['max_grade_ind']]] += 1;
	}
}
	
//Now convert climbData_assoc to 2-dimensional array compatible with plotting of the form [[grade_text,num_climbers],...]
$climbData = array();
foreach ($climbData_assoc as $key => $value) {
	$climbData[] = [$key,$value];
}

$climbDataJSON = json_encode($climbData);

echo $climbDataJSON;
?>
