<?php

$boulder_results = getClimbvsTime_TickLabels('boulder',$boulderGradingID,$userid);
$toprope_results = getClimbvsTime_TickLabels('toprope',$routeGradingID,$userid);
$lead_results = getClimbvsTime_TickLabels('lead',$routeGradingID,$userid);


$boulder_avg_timseries_json = $boulder_results[0];
$boulder_max_timseries_json = $boulder_results[1];
$boulder_tick_labels = $boulder_results[2];

$toprope_avg_timseries_json = $toprope_results[0];
$toprope_max_timseries_json = $toprope_results[1];
$toprope_tick_labels = $toprope_results[2];

$lead_avg_timseries_json = $lead_results[0];
$lead_max_timseries_json = $lead_results[1];
$lead_tick_labels = $lead_results[2];



function getClimbvsTime_TickLabels($climbType,$climbGradingID,$userid) {
//$climbType = {'boulder','toprope','lead'}

include 'dbconnect.php';
include 'BoulderRouteGradingSystems.php';

switch ($climbType) {
	case 'boulder':
		$climbConversionTable = $boulderConversionTable;
		$climbRatings = $boulderRatings;
		break;
	case 'toprope':
		$climbConversionTable = $routeConversionTable;
		$climbRatings = $routeRatings;
		break;
	case 'lead':
		$climbConversionTable = $routeConversionTable;
		$climbRatings = $routeRatings;
		break;
}


//want to get max climb grade for each date
$climbvstimequery = "SELECT date_workout,MAX(grade_index) AS max_grade_index FROM workouts INNER JOIN workout_segments ON workouts.workout_id = workout_segments.workout_id WHERE workouts.userid = :userid AND workout_segments.climb_type=:climbType AND workout_segments.ascent_type IN ('redpoint','flash','onsight') GROUP BY workouts.date_workout ORDER BY workouts.date_workout ASC";

$stmt = $db->prepare($climbvstimequery);
$stmt->execute(array(':userid'=>$userid,':climbType'=>$climbType));

//want to get a list of all climbing grades and reps, manually extract average and max grade
$climbvstimequery2 = "SELECT date_workout,grade_index,reps FROM workouts INNER JOIN workout_segments ON workouts.workout_id = workout_segments.workout_id WHERE workouts.userid = :userid AND workout_segments.climb_type=:climbType AND workout_segments.ascent_type IN ('redpoint','flash','onsight') ORDER BY workouts.date_workout ASC";
$stmt2 = $db->prepare($climbvstimequery2);
$stmt2->execute(array(':userid'=>$userid,':climbType'=>$climbType));
$workouts_all = $stmt2->fetchAll(PDO::FETCH_ASSOC);

$climb_avg_timeseries = array();
$climb_max_timeseries = array();



$climb_list = array(); //just a list of all avg or max grades
$i = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$workout_date = date_create_from_format('Y-m-d H:i:s',$row['date_workout']."12:00:00");
	//set time to 12pm, as that seems to center the bars
	
	$workout_time = (float)date_format($workout_date,'U')*1000;
	
	$workout_time2 = date_format($workout_date,'Y-m-d H:i:s');

	//Note: Need to convert to milliseconds, since flot uses that format
	//which are javascript timestamps. A php int can't store that high of 
	//an int, so cast it to a float
	
	//iterating through each date, now for each date, iterate through workouts while the date is the same
	//obtain average grades
	$climbData_singleworkout = array();
	while ($workouts_all[$i]['date_workout'] == $row['date_workout']) {
		//obtain the display grade index
		$climbData_singleworkout[] = array_search($climbConversionTable[$climbGradingID][$workouts_all[$i]['grade_index']],$climbRatings[$climbGradingID]);
		$i++;
	}
	//echo $climbData_singleworkout;
	$avg_grade_disp = (float)array_sum($climbData_singleworkout)/(float)count($climbData_singleworkout);
	/*
	$avg_grade_abs = round($row['avg_grade_index']);
	$avg_grade_disp = array_search($climbConversionTable[$climbGradingID][$avg_grade_abs],$climbRatings[$climbGradingID]); //map the absolute grade to the displayed grade index
	*/

	$max_grade_abs = (int)$row['max_grade_index'];
	$max_grade_disp = array_search($climbConversionTable[$climbGradingID][$max_grade_abs],$climbRatings[$climbGradingID]); //map the absolute grade to the displayed grade index
	
	$climb_avg_timeseries[] = array($workout_time,$avg_grade_disp);
	$climb_max_timeseries[] = array($workout_time,$max_grade_disp);
	//data need to be an array of 2-element arrays, where each
	//2-element array contains an x and y value, in order.
	

	
	$climb_list[] = $avg_grade_disp;
	$climb_list[] = $max_grade_disp;
	
}
$minclimb = max(1,floor(min($climb_list)));
$maxclimb = min(count($climbRatings[$climbGradingID])-2,ceil(max($climb_list)));



$climb_avg_timseries_json = json_encode($climb_avg_timeseries);
$climb_max_timseries_json = json_encode($climb_max_timeseries);

//generate grade tick labels
$ticks = array();
$j = 0;
for ($i=$minclimb-1;$i<=$maxclimb+1;$i++) {
	
	$ticks[$j] = array($i,$climbRatings[$climbGradingID][$i]);
	$j++;
}

$climb_tick_labels = json_encode($ticks);

return array($climb_avg_timseries_json,$climb_max_timseries_json,$climb_tick_labels);

}

?>


































