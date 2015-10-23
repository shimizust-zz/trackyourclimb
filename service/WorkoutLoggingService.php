<?php

include "../core/bootstrap.php";

class WorkoutLoggingService {
	
	protected $workoutDAO;
	protected $userrecordDAO;
	
	public function __construct() {
		$this->workoutDAO = new WorkoutDAO();
		$this->userrecordDAO = new UserRecordsDAO();
	}
	
	public function updateWorkoutAbsGrades($workoutID, $workout_info, $workout_segments_rel, $grading_systems) {
		/*
		 * Update an existing workout
		 */
	}
	
	public function saveWorkoutRelGrades($workout_info, $workout_segments_rel, $grading_systems) {
		/*
		 * Input: 
		 * $workout_info is an assc. array of non-climb data for the workout:
		 * array(userid, date_workout, gymid, default_gym, boulder_notes, tr_notes, lead_notes, other_notes)
		 * $workout_segments_rel is an assc. array of all workout segments making up
		 * a workout, using relative grade indices: 
		 * array(array(climb_type, ascent_type, grade_index, reps))
		 * $grading_systems is an assc. array with the grading scheme IDs:
		 * array(boulder, route)
		 */
		
		//Convert to $workout_segments_rel
		var_dump($workout_segments_rel);
		$workout_segments_rel = $this->convertRelToAbsGrades($workout_segments_rel, $grading_systems);
		var_dump($workout_segments_rel);
		return $this->saveWorkoutAbsGrades($workout_info, $workout_segments_rel);
	}
	
	public function saveWorkoutAbsGrades($workout_info, $workout_segments_abs) {
		/*
		 * Input:
		 * $workout_info is an assc. array of non-climb data for the workout:
		 * array(userid, date_workout, gymid, boulder_notes, tr_notes, lead_notes, other_notes)
		 * $workout_segments_abs is an assc. array of all workout segments making up
		 * a workout, using absolute grade indices:
		 * array(array(climbType, ascentType, relGradeIndex, reps))
		 * 
		 * Output:
		 * ["result", "workoutid", "boulderPoints", "trPoints", "leadPoints"]
		 */
		
		$userid = $workout_info['userid'];
		
		// Calculate points for workout
		$pointResults = $this->calcWorkoutPoints($workout_segments_abs);
		$workout_info["boulder_points"] = $pointResults["boulder_points"];
		$workout_info["TR_points"] = $pointResults["tr_points"];
		$workout_info["Lead_points"] = $pointResults["lead_points"];

		// Save workout info and get workoutId
		$results = $this->workoutDAO->saveWorkoutInfo($workout_info);
		$workoutID = $results["insertID"];
		
		// Save workout segments
		foreach ($workout_segments_abs as $workout_segment) {
			$this->workoutDAO->saveWorkoutSegment($workoutID, $workout_segment);
		}
		
		
		// Update user records
		$this->updateRecords($userid, $workout_segments_abs);
		
		return $workoutID;
	}
	
	public function updateRecords($userid, $workout_segments_abs) {
		/*
		 * $userRecords keys of the form {highest}{climbType}{ascentType}
		 * where climbType = ["Boulder", "TR", "Lead"]
		 * and ascentType = ["Project", "Redpoint", "Flash", "Onsight"]
		 */ 
		$climbTypeMap = ["boulder"=>"Boulder", "toprope"=>"TR", "lead"=>"Lead"];
		$ascentTypeMap = ["project"=>"Project", "redpoint"=>"Redpoint", "flash"=>"Flash", 
				"onsight"=>"Onsight"];
		
		$userRecords = $this->userrecordDAO->getUserRecords($userid)[0];
		
		// Now iterate through each workout_segment and see if any records
		// were broken. If so, then set new records.
		$recordsModified = false;
		$currRecords = $userRecords;
		foreach ($workout_segments_abs as $workout_segment) {
			// Map climbType, ascentType to the correct key value
			$climbTypeKey = $climbTypeMap[$workout_segment["climb_type"]];
			$ascentTypeKey = $ascentTypeMap[$workout_segment["ascent_type"]];
			$userRecordKey = "highest".$climbTypeKey.$ascentTypeKey;
			
			$currWorkoutGrade = $workout_segment["grade_index"];
			$currRecordGrade = $currRecords[$userRecordKey];
			if ($currWorkoutGrade > $currRecordGrade) {
				$currRecords[$userRecordKey] = $currWorkoutGrade;
				$recordsModified = true;
			}
		}
		
		if ($recordsModified) {
			unset($currRecords["userid"]);
			$this->userrecordDAO->updateUserRecords($userid, $currRecords);
		}
	}
	
	public function convertRelToAbsGrades($workout_segments_rel, $grading_systems) {
		/*
		 * $workout_segments_rel is an assc. array of all workout segments making up
		 * a workout, using relative grade indices: 
		 * array(array(climb_type, ascent_type, grade_index, reps))
		 */
		
		$boulderGradingSystemID = $grading_systems["boulder"];
		$routeGradingSystemID = $grading_systems["route"];
		
		$gradingConversionService = new GradingConversionService();
		$workout_segments_abs = array();
		
		foreach ($workout_segments_rel as $workout_segment) {
			$climbType = $workout_segment["climb_type"];
			$ascentType = $workout_segment["ascent_type"];
			$relGradeIndex = $workout_segment["grade_index"];
			$reps = $workout_segment["reps"];
			
			if ($climbType == "boulder") {
				$gradingSystemID = $boulderGradingSystemID;
			} else {
				$gradingSystemID = $routeGradingSystemID;
			}
			$absGradeIndex = $gradingConversionService->convertRelToAbsGradeIndex($climbType, $gradingSystemID, $relGradeIndex);
			$workout_segments_abs[] = array("climb_type"=>$climbType, "ascent_type"=>$ascentType, "grade_index"=>$absGradeIndex, "reps"=>$reps);
			
		}
		return $workout_segments_abs;
		
	}
	
	public function calcWorkoutPoints($workout_segments) {
		/*
		 * Input: $workout_segments is an array of all the workout
		 * segments making up a workout (using absolute grade indices)
		 * array(array(climb_type, ascent_type, grade_index, reps))
		 * 
		 * Output: return an array(boulder_points, tr_points, lead_points, total_points)
		 */
	
		$pointTotals = array("boulder_points"=>0, "tr_points"=>0,
				"lead_points"=>0, "total_points"=>0);
	
		$climbTypeMapping = array(
				"boulder" => "boulder_points",
				"toprope" => "tr_points",
				"lead" => "lead_points"
		);
	
		foreach ($workout_segments as $key => $workout_segment) {
			$climbType = $workout_segment["climb_type"];
			$ascentType = $workout_segment["ascent_type"];
			$absGradeIndex = $workout_segment["grade_index"];
			$reps = $workout_segment["reps"];
				
				
			$currPoints = self::calcPointsWorkoutSegment($climbType, $ascentType, $absGradeIndex, $reps);
			$pointTotals[$climbTypeMapping[$climbType]] += round($currPoints);
		}
		$pointTotals["total_points"] = array_sum(array_slice($pointTotals,0,3));
		
		foreach ($pointTotals as $key => $value) {
			$pointTotals[$key] = intval($value);
		}
		
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