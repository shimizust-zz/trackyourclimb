<?php

include "../core/bootstrap.php";

class UserRecordsDAO {
	
	protected $db;
	private static $fields = array(
			"highestBoulderProject",
			"highestBoulderRedpoint",
			"highestBoulderFlash",
			"highestBoulderOnsight",
			"highestTRProject",
			"highestTRRedpoint",
			"highestTRFlash",
			"highestTROnsight",
			"highestLeadProject",
			"highestLeadRedpoint",
			"highestLeadFlash",
			"highestLeadOnsight"
	);
	private static $USERRECORDS_TABLENAME="userrecords";
	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
		
	}
	
	public function getUserRecords($userid) {
		$selectKeyValue = ["userid", $userid];
		return DBHelper::performSimpleSelectQuery($this->db, 
				self::$USERRECORDS_TABLENAME, $selectKeyValue);
	}
	
	public function createUserRecords($userid, $user_records) {
		return DBHelper::performInsertQuery($this->db, 
				self::$USERRECORDS_TABLENAME, $user_records);
	}
	
	public function updateUserRecords($userid, $user_records) {
		$selectValueKey = ["userid", $userid];
		return DBHelper::performUpdateQuery($this->db,
				self::$USERRECORDS_TABLENAME, $user_records, $selectValueKey);
	}
}

































