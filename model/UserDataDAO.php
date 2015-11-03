<?php
include "../core/bootstrap.php";

class UserDataDAO {

	protected $db;
	const USERDATA_TABLENAME = "userdata";

	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
	}

	public function getUserData($userid) {
		//Return a php array of userdata for certain userid
		$selectKeyValue = ["userid", $userid];
		return DBHelper::performSimpleSelectQuery($this->db, self::USERDATA_TABLENAME, $selectKeyValue);
	}
	
	public function updateUserData($userid, $propValArray) {
		/*
		 * Input: $userid = user ID
		 * $propValArray = array("prop1"=>val1, "prop2"=>val2), an array of properties to update in the userdata table
		 */
		$selectKeyValue = ["userid", $userid];
		return DBHelper::performUpdateQuery($this->db, self::USERDATA_TABLENAME, $propValArray, $selectKeyValue);
	}
}