<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	
include 'phparray-to-htmltable.php';
include 'BoulderRouteGradingSystems.php';

$numworkoutsperpage = 60;

//extract all workouts by a user
//Special case if someone logged no climbs, then no entry in workout_segments is generated, in which case workout_segments.workout_id would be null. However, workouts.workout_id will always be a valid number. Also, make sure to use left outer join since no accompanying workout segments may exist.
$stmt4 = $db->prepare("SELECT *,workouts.workout_id as w_workout_id, workout_segments.workout_id as s_workout_id FROM workouts LEFT OUTER JOIN 
	workout_segments ON workouts.workout_id = workout_segments.workout_id INNER JOIN gyms on workouts.gymid = gyms.gymid
	WHERE workouts.userid = :userid ORDER BY 
	date_workout DESC, workouts.workout_id DESC, climb_type ASC, 
	grade_index ASC");
$stmt4->execute(array(':userid'=>$userid));

include 'getUserGradingPrefs.php';


$allworkouts = $stmt4->fetchAll(PDO::FETCH_ASSOC);

$prev_id = $allworkouts[0]['w_workout_id'];
$i = 1;

$table_html = "";
$len = count($allworkouts); //number of workout segments
$prev_row = 0;



foreach($allworkouts as $row) {
	$curr_id = $row['w_workout_id'];

	
	//convert workout_array to html if switching to different
	//workout
	if ($curr_id != $prev_id) {
		//Convert climb tables to workout_array
		$workout_array = convertClimbTabletoWorkoutArray($climbTable);
		
		$table_html .= convertArrayToTable($workout_array);
		$table_html .= "<tr><td colspan = 3 style='text-align:left'><b>Notes: </b>".$prev_row['boulder_notes']."</td>
		<td colspan=3 style='text-align:left'><b>Notes: </b> ".$prev_row['tr_notes']."</td><td colspan=3 style='text-align:left'><b>Notes: </b>".
		$prev_row['lead_notes']."</td></tr>
		<tr><td colspan = 9 style='text-align:left'><b>Other Notes:</b> ".$prev_row['other_notes']."</td></tr>";
		$table_html .= "</table></div></div>";
	}
	
	
	if ($i == 1 || $curr_id != $prev_id) {
		
		//start a new panel since either first workout or new workout
		$table_html .= "<div class='panel panel-default pastworkout-panel'>
		<div class='panel-heading'>".$row['date_workout'].": <b>".$row['gym_name']."</b><a class='pull-right btn btn-primary btn-xs link-btn' href='workout-edit.php?wid=".$curr_id."'>Edit Workout</a></div>
		<div class='panel-body'><table class='table table-striped table-bordered'>
		<tr><th colspan=3 >Boulder</th><th colspan=3>Top-Rope</th>
		<th colspan=3>Lead</th></tr>
		<tr><th>Grade</th><th>Ascent Type</th><th>#</th>
		<th>Grade</th><th>Ascent Type</th><th>#</th>
		<th>Grade</th><th>Ascent Type</th><th>#</th></tr>";
	
		//initialize the workout_array
		$workout_array = array();
		$b_ind = 0; //boulder index
		$t_ind = 0; //top rope index
		$l_ind = 0; //lead index
		
		//create associative arrays to group workouts with same display grade and ascent type
		$climbTable = array(); 
		$trTable = array();
		$leadTable = array();
	}
	
	//add the workout to the workout array
	if ($row['ascent_type']=='project') {
		$ascent_type_str = 'attempt';
	}
	else {
		$ascent_type_str = $row['ascent_type'];
	}
	
	
	if ($row['climb_type']=='boulder') {
		
		$grade_text = $boulderConversionTable[$boulderGradingID][$row['grade_index']];
		$climbTable["boulder"."&".$grade_text."&".$ascent_type_str] += $row['reps'];
		
		$colind = 0; 
		$rowind = $b_ind;
		$b_ind++;
		$gradeConversionTable = $boulderConversionTable[$boulderGradingID];
	}
	elseif ($row['climb_type']=='toprope') {
		$grade_text = $routeConversionTable[$routeGradingID][$row['grade_index']];
		$climbTable["toprope"."&".$grade_text."&".$ascent_type_str] += $row['reps'];
		
		$colind = 3;
		$rowind = $t_ind;
		$t_ind++;
		$gradeConversionTable = $routeConversionTable[$routeGradingID];
	}
	elseif ($row['climb_type']=='lead') {
		$grade_text = $routeConversionTable[$routeGradingID][$row['grade_index']];
		$climbTable["lead"."&".$grade_text."&".$ascent_type_str] += $row['reps'];
	
		$colind = 6;
		$rowind = $l_ind;
		$l_ind++;
		$gradeConversionTable = $routeConversionTable[$routeGradingID];
	}

	
	//convert workout_array to html table if last workoutid
	if ($i==$len) {
		//Convert climb tables to workout_array
		$workout_array = convertClimbTabletoWorkoutArray($climbTable);
		
		$table_html .= convertArrayToTable($workout_array);
		
		//then add notes
		$table_html .= "<tr><td colspan = 3  style='text-align:left'><b>Notes: </b>".$row['boulder_notes']."</td>
		<td colspan=3 style=
		'text-align:left'><b>Notes: </b>".$row['tr_notes']."</td><td colspan=3 style='text-align:left'><b>Notes: </b>".
		$row['lead_notes']."</td></tr><tr><td colspan = 9 style='text-align:left'><b>Other Notes: </b>".$row['other_notes']."</td></tr>";
		
		$table_html .= "</table></div></div>";
	}
	
	$i++;
	$prev_id = $curr_id;
	$prev_row = $row;
}



function convertClimbTabletoWorkoutArray($climbTable) {
$b_ind = 0;
$t_ind = 0;
$l_ind = 0;

$workout_array = array();
foreach ($climbTable as $key => $value) {
	//split the key by the delimiter string (&) to get climbType, grade_text, and ascent_type, respectively.
	$key_string = explode('&',$key);
	$climbType = $key_string[0];
	$grade_text = $key_string[1];
	$ascent_type = $key_string[2];

	if ($climbType=='boulder') {
		$colind = 0;
		$row_ind = $b_ind;
		$b_ind++;
	}
	elseif ($climbType=='toprope') {
		$colind = 3;
		$row_ind = $t_ind;
		$t_ind++;
	}
	elseif ($climbType=='lead') {
		$colind = 6;
		$row_ind = $l_ind;
		$l_ind++;
	}
	$workout_array[$row_ind][$colind] = $grade_text;
	$workout_array[$row_ind][$colind+1] = $ascent_type;
	$workout_array[$row_ind][$colind+2] = $value;

}

return $workout_array;
}


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
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script>
		window.onload = function() {
			document.getElementById('workout-download-btn').onclick = function() {

			   var iframe = document.createElement('iframe');
			   iframe.setAttribute("id","download-iframe");
			   iframe.src = 'downloadworkouts.php';
			  
			   document.body.appendChild(iframe);

			};
		};
		</script>
		
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
					<div class="page-header"><h1>Past Workouts</h1></div>
					<button type="button" class="btn btn-primary" id="workout-download-btn">Download All Workouts (.xls)</button>
					
					
					<div id="pastworkouts">
						<?php echo $table_html; ?>
					</div>
				</div>
			</div>
		</div>
		<?php require("footer.php"); ?>
</body>
</html>