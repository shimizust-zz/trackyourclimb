<?php
include "../core/bootstrap.php";

class UserPrefsDAO {
	
	protected $db;
	const USERPREFS_TABLENAME = "userprefs"; 
	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
	}
	
	public function getUserPrefs($userid) {
		//Return a php array of user prefs for certain userid
		$selectKeyValue = ["userid", $userid];
		return DBHelper::performSimpleSelectQuery($this->db, self::USERPREFS_TABLENAME, $selectKeyValue)[0];
	}
	
}