<?php
//Go through all of workout segments from user and re-tabulate the records table.

$stmt = $db->prepare("SELECT * FROM workout_segments INNER JOIN workouts ON workouts.workout_id = workout_segments.workout_id WHERE workouts.userid=?");
$stmt->execute(array($userid));

$userrecords = array_fill(1,12,-1); //initialize userrecords to no records at first
while ($single_workout = $stmt->fetch(PDO::FETCH_ASSOC)) {
	
	switch ($single_workout['climb_type']) {
		case "boulder":
			$record_ind = 1;
			break;
		case "toprope":
			$record_ind = 5;
			break;
		case "lead":
			$record_ind = 9;
			break;
	}
	switch ($single_workout['ascent_type']) {
		case "project":
			break;
		case "redpoint":
			$record_ind += 1;
			break;
		case "flash":
			$record_ind += 2;
			break;
		case "onsight":
			$record_ind += 3;
			break;
	}
	if ($userrecords[$record_ind]<$single_workout['grade_index']) {
		//overwrite that record if the current workout segment beats it
		$userrecords[$record_ind] = $single_workout['grade_index'];
	}
}

//write new records to database
$stmt6 = $db->prepare("UPDATE userrecords SET highestBoulderProject=:hBP,
highestBoulderRedpoint=:hBR,highestBoulderFlash=:hBF,highestBoulderOnsight=:hBO,
highestTRProject=:hTP,highestTRRedpoint=:hTR,highestTRFlash=:hTF,highestTROnsight=:hTO,
highestLeadProject=:hLP,highestLeadRedpoint=:hLR,highestLeadFlash=:hLF,
highestLeadOnsight=:hLO WHERE userid = :userid");
$stmt6->execute(array(':hBP'=>$userrecords[1],':hBR'=>$userrecords[2],
':hBF'=>$userrecords[3],':hBO'=>$userrecords[4],':hTP'=>$userrecords[5],
':hTR'=>$userrecords[6],':hTF'=>$userrecords[7],':hTO'=>$userrecords[8],
':hLP'=>$userrecords[9],':hLR'=>$userrecords[10],':hLF'=>$userrecords[11],
':hLO'=>$userrecords[12],':userid'=>$userid));

?>