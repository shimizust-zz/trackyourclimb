<?php

function getNumClimbs($db) {
	

	//This returns the total number of climbs tracked
	
	$stmt = $db->prepare("SELECT SUM(reps) AS total_climbs FROM workout_segments");
	
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	echo $result['total_climbs'];

}
?>