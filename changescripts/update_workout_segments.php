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
	:bouldernotes");
	$stmt3->execute(array(':bouldernotes'=>$bouldernotes));
	
	
	//update projects
	$startind = 3;
	$endind = 66;
	for ($i=$startind;$i<=$endind;$i++) {
		if ($i>=3 && $i<=18) {
			$ascent_type = 'project';
		}
		elseif ($i>=19 && $i<=34) {
			$ascent_type = 'redpoint';
		}
		elseif ($i>=35 && $i<=50) {
			$ascent_type = 'flash';
		}
		elseif ($i>=51 && $i<=66) {
			$ascent_type = 'onsight';
		}
		
		if ($workout[$i]>0) {
			//if actually logged a climb at this grade,
			//input into workout_segments
			$grade_index = ($i-3)%16; //V grade
			$grade_text = 'V'.$grade_index;
			
			
			$stmt2 = $db->prepare("INSERT INTO workout_segments 
			(workout_id,climb_type,ascent_type,grade_index,
			grade_text,reps) VALUES (:workout_id,:climb_type,
			:ascent_type,:grade_index,:grade_text,:reps)");
			$stmt2->execute(array(':workout_id'=>$workoutid,
			':climb_type'=>'boulder',':ascent_type'=>$ascent_type,
			':grade_index'=>$grade_index,':grade_text'=>$grade_text,
			':reps'=>$workout[$i]));
			
		}
	}
}
		

//Update TR workouts
$YDSratings_text = array("<=5.5","5.6","5.7","5.8","5.9","5.10a","5.10b","5.10c","5.10d",
	"5.11a","5.11b","5.11c","5.11d","5.12a","5.12b","5.12c","5.12d","5.13a",
	"5.13b","5.13c","5.13d","5.14a","5.14b","5.14c","5.14d","5.15a","5.15b",
	"5.15c","5.15d");


$stmt4 = $db->prepare("SELECT * FROM tr_workouts");
$stmt4->execute();

while($workout = $stmt4->fetch(PDO::FETCH_NUM)) {
	$workoutid = $workout[0];
	$trpoints = $workout[1];
	$trnotes = $workout[2];
	
	$stmt5 = $db->prepare("UPDATE workouts SET tr_notes=
	:trnotes");
	$stmt5->execute(array(':trnotes'=>$trnotes));
	
	
	//update projects
	$startind = 3;
	$endind = 118;
	for ($i=$startind;$i<=$endind;$i++) {
		if ($i>=3 && $i<=31) {
			$ascent_type = 'project';
		}
		elseif ($i>=32 && $i<=60) {
			$ascent_type = 'redpoint';
		}
		elseif ($i>=61 && $i<=89) {
			$ascent_type = 'flash';
		}
		elseif ($i>=90 && $i<=$endind) {
			$ascent_type = 'onsight';
		}
		
		if ($workout[$i]>0) {
			//if actually logged a climb at this grade,
			//input into workout_segments
			$grade_index = ($i-3)%29; //TR grade, 
			$grade_text = $YDSratings_text[$grade_index];
			
			
			$stmt6 = $db->prepare("INSERT INTO workout_segments 
			(workout_id,climb_type,ascent_type,grade_index,
			grade_text,reps) VALUES (:workout_id,:climb_type,
			:ascent_type,:grade_index,:grade_text,:reps)");
			$stmt6->execute(array(':workout_id'=>$workoutid,
			':climb_type'=>'toprope',':ascent_type'=>$ascent_type,
			':grade_index'=>$grade_index,':grade_text'=>$grade_text,
			':reps'=>$workout[$i]));
			
		}
	}
}

//update lead workouts
$stmt7 = $db->prepare("SELECT * FROM lead_workouts");
$stmt7->execute();

while($workout = $stmt7->fetch(PDO::FETCH_NUM)) {
	$workoutid = $workout[0];
	$leadpoints = $workout[1];
	$leadnotes = $workout[2];
	
	$stmt8 = $db->prepare("UPDATE workouts SET lead_notes=
	:leadnotes");
	$stmt8->execute(array(':leadnotes'=>$leadnotes));
	
	
	//update projects
	$startind = 3;
	$endind = 118;
	for ($i=$startind;$i<=$endind;$i++) {
		if ($i>=3 && $i<=31) {
			$ascent_type = 'project';
		}
		elseif ($i>=32 && $i<=60) {
			$ascent_type = 'redpoint';
		}
		elseif ($i>=61 && $i<=89) {
			$ascent_type = 'flash';
		}
		elseif ($i>=90 && $i<=$endind) {
			$ascent_type = 'onsight';
		}
		
		if ($workout[$i]>0) {
			//if actually logged a climb at this grade,
			//input into workout_segments
			$grade_index = ($i-3)%29; //lead grade, 
			$grade_text = $YDSratings_text[$grade_index];
			
			
			$stmt9 = $db->prepare("INSERT INTO workout_segments 
			(workout_id,climb_type,ascent_type,grade_index,
			grade_text,reps) VALUES (:workout_id,:climb_type,
			:ascent_type,:grade_index,:grade_text,:reps)");
			$stmt9->execute(array(':workout_id'=>$workoutid,
			':climb_type'=>'lead',':ascent_type'=>$ascent_type,
			':grade_index'=>$grade_index,':grade_text'=>$grade_text,
			':reps'=>$workout[$i]));
			
		}
	}
}
 
 */
?>