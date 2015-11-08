<?php

include '../core/bootstrap.php';

class WorkoutDAO {
	
	protected $db;

	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
		
	}
	
	public function getWorkoutInfo($workout_id) {
		/*
		 * Input: $workout_id = workout ID
		 * 
		 * Output: An associative array of workout information,
		 * $workoutInfo = array("userid","date_workout","gymid","boulder_points",
		 * "TR_points","Lead_points","boulder_notes","tr_notes","lead_notes",
		 * "other_notes"
		 */
		
		$selectKeyValue = array("workout_id", $workout_id);
		return DBHelper::performSimpleSelectQuery($this->db, "workouts", $selectKeyValue)[0];
	}
	
	public function saveWorkoutInfo($workout_info) {
		/*
		 * Input: $workout_info = array(userid, date_workout, gymid, 
		 * boulder_points, TR_points, Lead_points, boulder_notes,
		 * tr_notes, lead_notes, other_notes) 
		 * 
		 * Output: array(result, insertID)
		 */
		
		return DBHelper::performInsertQuery($this->db, "workouts", $workout_info);
		
	}
	
	public function updateWorkoutInfo($workout_id, $workout_info) {
		
	}
	
	public function saveWorkoutSegment($workout_id, $workout_segment) {
		/*
		 * Input: $workout_segment = array(climb_type, ascent_type, grade_index, reps)
		 * 
		 * Output: array(result, insertID)
		 */
		
		// Add workout_id to workout_segment array
		$workout_segment["workout_id"] = $workout_id;
		return DBHelper::performInsertQuery($this->db, "workout_segments", $workout_segment);
		
	}
	
	public function updateWorkoutSegments($workout_id, $workout_segments) {
		
	}
	
	public function getWorkoutSegments($workout_id) {
		/*
		 * Input: $workout_id
		 * 
		 * Output: An array of an associative array of workout segment info
		 */
		$selectKeyValue = ["workout_id", $workout_id];
		return DBHelper::performSimpleSelectQuery($this->db, "workout_segments", $selectKeyValue);
	}
	
	public function deleteWorkout($workout_id) {
		$selectKeyValue = ["workout_id", $workout_id];
		return DBHelper::performSimpleDeleteQuery($this->db, "workouts", $selectKeyValue);
	}
}