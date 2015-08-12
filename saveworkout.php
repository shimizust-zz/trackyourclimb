<?php
//Read in submitted workout and write to workout tables
include 'calcPoints.php';
include 'BoulderRouteGradingSystems.php';


//check that register form is submitted 
if (isset($_POST['workoutsubmit'])) {
	
	
	$workoutdate = $_POST['workoutdate']; 
	$gymid = $_POST['gymid'];
	
	if (isset($_POST['default-gym'])) {
		
		//check if indoor gym or outdoor crag
		//update default climbing area accordingly
		if (isset($_POST['gym-indoor'])) {
			if ($_POST['gym-indoor'] == 1) {
				//indoor
				$stmt = $db->prepare("UPDATE userdata SET main_gym=:gymid WHERE userid=:userid");
				
			}
			else {
				//outdoor
				$stmt = $db->prepare("UPDATE userdata SET main_crag=:gymid WHERE userid=:userid");
			}
			$stmt->execute(array(':gymid'=>$gymid,':userid'=>$userid));
		}
		
		
		//set default country
		$stmt = $db->prepare("SELECT countryCode FROM gyms WHERE gymid=:gymid");
		$stmt->execute(array(':gymid'=>$gymid));
		$countryCodeResult = $stmt->fetch(PDO::FETCH_ASSOC);
		$countryCode = $countryCodeResult['countryCode'];
		
		$stmt = $db->prepare("UPDATE userdata SET countryCode=:countryCode WHERE userid=:userid");
		$stmt->execute(array(':countryCode'=>$countryCode,':userid'=>$userid));
	}

	//Extract user's current preferred grading system 
	$stmt = $db->prepare("SELECT boulderGradingSystemID, routeGradingSystemID FROM userprefs WHERE userid = :userid");
	$stmt->execute(array(':userid'=>$userid));
	$gradingResult = $stmt->fetch(PDO::FETCH_ASSOC);
	$boulderGradingSystemID = $gradingResult['boulderGradingSystemID'];
	$routeGradingSystemID = $gradingResult['routeGradingSystemID'];
	
	
	$ascentTypes = array(
		0 => "Project",
		1 => "Redpoint",
		2 => "Flash",
		3 => "Onsight");
	$numAscentTypes = 3;	
	
	//Keep track of the highest grades of each climbing type and ascent type
	//each array is in order [project,redpoint,flash,onsight]
	$maxBoulder = array(0,0,0,0);
	$maxTR = array(0,0,0,0);
	$maxLead = array(0,0,0,0);
	
	

	
	//extract notes on each climbing type
	$boulderNotes = $_POST['boulderNotes'];
	$TRNotes = $_POST['TRNotes'];
	$LeadNotes = $_POST['LeadNotes'];
	$OtherNotes = $_POST['OtherNotes'];
	
	//Create workout entry (doesn't have points logged yet)
	$stmt = $db->prepare("INSERT INTO workouts (userid,date_workout,gymid,boulder_notes,tr_notes,lead_notes,other_notes) 
	VALUES (:userid,:date_workout,:gymid,:boulder_notes,:tr_notes,
	:lead_notes,:other_notes)");
	
	$stmt->execute(array(':userid'=>$userid,':date_workout'=>$workoutdate,
	':gymid'=>$gymid,':boulder_notes'=>$boulderNotes,':tr_notes'=>
	$TRNotes,':lead_notes'=>$LeadNotes,':other_notes'=>$OtherNotes));
	
	$workoutid = $db->lastInsertId(); 
	
	//keep track of total points
	$boulder_points = 0;
	$tr_points = 0;
	$lead_points = 0;
	
	
	$maxBoulderGradeInd = count($boulderRatings[$boulderGradingSystemID])-1;
	$maxRouteGradeInd = count($routeRatings[$routeGradingSystemID])-1;
	
	for ($j = 0;$j<=$numAscentTypes;$j++) {
		//record any boulder climbs
		for ($i = 0;$i<=$maxBoulderGradeInd;$i++) {
			$climbType = 'boulder';
			//construct varname of hidden field containing all the bouldering fields
			$varname = 'num'.$ascentTypes[$j].'B'.$i;
			$reps = $_POST[$varname];
			if ($reps > 0) {
				
				
				$grade_text = $boulderRatings[$boulderGradingSystemID][$i];
				$absGradeIndex = $boulderGradeMapAbsGradeInd[$boulderGradingSystemID][$grade_text];
				$maxBoulder[$j] = $absGradeIndex; //keep updating max boulder for each ascent type
				
				//add this entry to the workout_segments table
				$stmt2 = $db->prepare("INSERT INTO workout_segments 
				(workout_id,climb_type,ascent_type,grade_index,
				reps) VALUES (:workout_id,:climb_type,:ascent_type,
				:grade_index,:reps)");
				$stmt2->execute(array(':workout_id'=>$workoutid,
				':climb_type'=>$climbType,':ascent_type'=>strtolower($ascentTypes[$j]),
				':grade_index'=>$absGradeIndex,':reps'=>
				$reps));
				
				$boulder_points += calcPoints($climbType,$j,$absGradeIndex,$reps);
			}
		}
			
		for ($k=0;$k<=$maxRouteGradeInd;$k++) {
			$TRvarname = 'num'.$ascentTypes[$j].'TR'.$k;
			$Lvarname = 'num'.$ascentTypes[$j].'L'.$k;
			
			$reps = $_POST[$TRvarname]; 
			if ($reps > 0) {
				$climbType = 'toprope';
				
				
				$grade_text = $routeRatings[$routeGradingSystemID][$k];
				$absGradeIndex = $routeGradeMapAbsGradeInd[$routeGradingSystemID][$grade_text];
				$maxTR[$j] = $absGradeIndex; //update max top-rope climb (grade index)
				
				//add this entry to the workout_segments table
				$stmt3 = $db->prepare("INSERT INTO workout_segments 
				(workout_id,climb_type,ascent_type,grade_index,
				reps) VALUES (:workout_id,:climb_type,:ascent_type,
				:grade_index,:reps)");
				$stmt3->execute(array(':workout_id'=>$workoutid,
				':climb_type'=>$climbType,':ascent_type'=>strtolower($ascentTypes[$j]),
				':grade_index'=>$absGradeIndex,':reps'=>$reps));
				
				$tr_points += calcPoints($climbType,$j,$absGradeIndex,$reps);
			}
			
			$reps = $_POST[$Lvarname]; 
			if ($reps > 0) {
				$climbType = 'lead';
				
				
				$grade_text = $routeRatings[$routeGradingSystemID][$k];
				$absGradeIndex = $routeGradeMapAbsGradeInd[$routeGradingSystemID][$grade_text];
				$maxLead[$j] = $absGradeIndex;
				
				//add this entry to the workout_segments table
				$stmt4 = $db->prepare("INSERT INTO workout_segments 
				(workout_id,climb_type,ascent_type,grade_index,
				reps) VALUES (:workout_id,:climb_type,:ascent_type,
				:grade_index,:reps)");
				$stmt4->execute(array(':workout_id'=>$workoutid,
				':climb_type'=>$climbType,':ascent_type'=>strtolower($ascentTypes[$j]),
				':grade_index'=>$absGradeIndex,':reps'=>$reps));
				
				$lead_points += calcPoints($climbType,$j,$absGradeIndex,$reps);
			}
		}	
	}
		
	//At this point, all workout segments should be logged
	//Now, update the workouts table with final point tallies
	$stmt5 = $db->prepare("UPDATE workouts SET boulder_points = 
	:boulder_points, TR_points = :tr_points, Lead_points = :lead_points WHERE 
	workout_id = :workoutid");
	$stmt5->execute(array(':boulder_points'=>round($boulder_points),':tr_points'=>
	round($tr_points),':lead_points'=>round($lead_points),':workoutid'=>$workoutid));
	
}
else {
	header("Location: workout-input.php");
}
?>