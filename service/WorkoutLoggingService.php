<?php

include "../core/bootstrap.php";

class WorkoutLoggingService {
	
	public function saveWorkout($workout_info, $workout_segments) {
		
	}
	
	public function calcWorkoutPoints($workout_segments) {
		//Input: $workout_segments is an array of all the workout
		//segments making up a workout:
		//array(array(climbType, ascentType, absGradeIndex, reps))
	
		//Output: return an array(boulder_points, tr_points, lead_points, total_points)
		$pointTotals = array("boulder_points"=>0, "tr_points"=>0,
				"lead_points"=>0, "total_points"=>0);
	
		$climbTypeMapping = array(
				"boulder" => "boulder_points",
				"toprope" => "tr_points",
				"lead" => "lead_points"
		);
	
		foreach ($workout_segments as $key => $workout_segment) {
			$climbType = $workout_segment["climbType"];
			$ascentType = $workout_segment["ascentType"];
			$absGradeIndex = $workout_segment["absGradeIndex"];
			$reps = $workout_segment["reps"];
				
				
			$currPoints = self::calcPointsWorkoutSegment($climbType, $ascentType, $absGradeIndex, $reps);
			$pointTotals[$climbTypeMapping[$climbType]] += $currPoints;
		}
		$pointTotals["total_points"] = array_sum(array_slice($pointTotals,0,3));
	
		return $pointTotals;
	}
	
	private static function calcPointsWorkoutSegment($climbType,
			$ascentType, $absGradeIndex, $reps) {
				//calculate points for a single workout segment
				$points = 0;
	
				$climbFactor = array(1.0,1.5,2.0);
				$ascent_factor = array(0.5,1.0,1.15,1.2);
				$boulderToRouteNormalizationRatio = 22.0/30.0;
				//The max boulder index is 22 and the max route index is 30.
	
				$ascentTypeMapping = array(
						"project" => 0,
						"redpoint" => 1,
						"flash" => 2,
						"onsight" => 3
				);
				$ascentInd = $ascentTypeMapping[$ascentType];
	
				if ($climbType == 'boulder') {
					$points += $climbFactor[0]*($absGradeIndex+0.5)*100.0*$ascent_factor[$ascentInd]*$reps;
				}
				elseif ($climbType == 'toprope') {
					$points += $climbFactor[1]*($absGradeIndex+0.5)*$boulderToRouteNormalizationRatio*100.0*$ascent_factor[$ascentInd]*$reps;
				}
				elseif ($climbType == 'lead') {
					$points += $climbFactor[2]*($absGradeIndex+0.5)*$boulderToRouteNormalizationRatio*100.0*$ascent_factor[$ascentInd]*$reps;
				}
	
				return $points;
	}
}