<?php


function calcPoints($climbType,$ascent_ind,$absGradeIndex,$reps) {
//calculate points for a single climb type
	$points = 0;
	$climbFactor = array(1.0,1.5,2.0);
	
	$ascent_factor = array(0.5,1.0,1.15,1.2);
	$boulderToRouteNormalizationRatio = 22.0/30.0; 
	//The max boulder index is 22 and the max route index is 30. 
	
	if ($climbType == 'boulder') {
		$points += $climbFactor[0]*($absGradeIndex+0.5)*100.0*$ascent_factor[$ascent_ind]*$reps;
	}
	elseif ($climbType == 'toprope') {
		$points += $climbFactor[1]*($absGradeIndex+0.5)*$boulderToRouteNormalizationRatio*100.0*$ascent_factor[$ascent_ind]*$reps;
	}
	elseif ($climbType == 'lead') {
		$points += $climbFactor[2]*($absGradeIndex+0.5)*$boulderToRouteNormalizationRatio*100.0*$ascent_factor[$ascent_ind]*$reps;
	}
	return $points;
}



?>