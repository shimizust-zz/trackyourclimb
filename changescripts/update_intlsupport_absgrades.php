<?php 
/*
//connect to database
include '../dbconnect.php';
	


include '../YDSratings_var.php';
include '../BoulderRouteGradingSystems.php';

//update the workout segments, userrecords to absolute grades
//update userdata countryCode to US
//update gyms to US

$stmt = $db->prepare("SELECT * FROM workout_segments");
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	//read in the climbtype and grade_index and convert to absolute grade index
	$grade_index_curr = $row['grade_index'];
	
	//convert to absolute grade
	if ($row['climb_type'] == 'boulder') {
		$grade_index_abs = $boulderGradeMapAbsGradeInd[0]["V".$grade_index_curr];
	}
	elseif ($row['climb_type'] == 'toprope' || $row['climb_type'] == 'lead') {
		$grade_index_abs = $routeGradeMapAbsGradeInd[0][$YDSratings_text[$row['grade_index']]];
	}
	
	//update value in database
	$stmt2 = $db->prepare("UPDATE workout_segments SET grade_index = :grade_index_abs WHERE segment_id = :segment_id");
	$stmt2->execute(array(':grade_index_abs'=>$grade_index_abs,':segment_id'=>$row['segment_id']));
}

//update userrecords
$stmt3 = $db->prepare("SELECT * FROM userrecords");
$stmt3->execute();
while ($row = $stmt3->fetch(PDO::FETCH_BOTH)) {
	//read in records and convert to absolute grade index
	$curr_records = array();
	for ($i = 1;$i <= 12;$i++) {
		$curr_records[] = $row[$i];
	}
	
	//convert to absolute grade. Make sure to leave "-1" values the same
	$abs_records = array_fill(0,12,-1);
	for ($i=0;$i<=3;$i++) {
		if ($curr_records[$i] >= 0) {
			//not -1
			$abs_records[$i] = $boulderGradeMapAbsGradeInd[0]["V".$curr_records[$i]];
		}
	}
	for ($i=4;$i<=11;$i++) {
		if ($curr_records[$i] >= 0) {
			//not -1
			$abs_records[$i] = $routeGradeMapAbsGradeInd[0][$YDSratings_text[$curr_records[$i]]];
		}
	}
	
	//update values into database
	$stmt4 = $db->prepare("UPDATE userrecords SET highestBoulderProject=:hBP,	highestBoulderRedpoint=:hBR,highestBoulderFlash=:hBF,highestBoulderOnsight=:hBO,highestTRProject=:hTP,highestTRRedpoint=:hTR,highestTRFlash=:hTF,highestTROnsight=:hTO,highestLeadProject=:hLP,highestLeadRedpoint=:hLR,highestLeadFlash=:hLF,highestLeadOnsight=:hLO WHERE userid = :userid");
	$stmt4->execute(array(':hBP'=>$abs_records[0],':hBR'=>$abs_records[1],
	':hBF'=>$abs_records[2],':hBO'=>$abs_records[3],':hTP'=>$abs_records[4],
	':hTR'=>$abs_records[5],':hTF'=>$abs_records[6],':hTO'=>$abs_records[7],
	':hLP'=>$abs_records[8],':hLR'=>$abs_records[9],':hLF'=>$abs_records[10],
	':hLO'=>$abs_records[11],':userid'=>$row['userid']));
}

//update country codes to US for everyone currently
$stmt5 = $db->prepare("UPDATE userdata SET countryCode = 'US'");
$stmt5->execute();

//update gyms to US currently
$stmt6 = $db->prepare("UPDATE gyms SET countryCode = 'US'");
$stmt6->execute();

echo 'finished 2';


*/

?>