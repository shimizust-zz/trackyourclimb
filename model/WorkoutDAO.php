<?php

include '../core/bootstrap.php';

class WorkoutDAO {
	
	protected $db;

	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
		
	}
	
	public function getWorkoutInfo($workout_id) {
		
	}
	
	public function saveWorkoutInfo($workout_info) {
		/*
		 * Input: $workout_data = array(userid, date_workout, gymid, 
		 * boulder_points, TR_points, Lead_points, boulder_notes,
		 * tr_notes, lead_notes, other_notes) 
		 */
		
		//get propvals
		$propVals = array_keys($workout_info);
		$fieldList = implode(",", $propVals);
		$placeholderList = array_walk($propVals, function($value, $key) {
			$value = ":".$value;
		});
		$stmt = $this->db->prepare("INSERT INTO workouts (".
				$fieldList.") VALUES (".$placeholderList.")");
		$result = $stmt->execute(DBHelper::genExecuteArray($workout_info));
		return ["result"=>$result];
	}
	
	public function updateWorkoutInfo($workout_id, $workout_info) {
		
	}
	
	public function saveWorkoutSegments($workout_segments) {
		/*
		 * Input: $workout_segments = 
		 */
	}
	
	public function updateWorkoutSegments($workout_id, $workout_segments) {
		
	}
	
}