<?php

include "../core/bootstrap.php";
class ClimbingAreaDAO {

	protected $db;
	const CLIMBINGAREA_TABLENAME = "gyms";
	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
	}
	
	/*
	* This function takes in a country code ($countryCode) and climbing area type, $isIndoor. 
	* $isIndoor = TRUE (indoor)
	* $isIndoor = FALSE (outdoor)
	*/
	public function getGymList($countryCode, $isIndoor) {
		
		$indoor = 1;
		if (!$isIndoor) {
			$indoor = 0; 
		}
		
		if ($countryCode == "any") { 
			//return gyms from all countries
			$stmt = $this->db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE indoor=:indoor ORDER BY state ASC, gym_name ASC");
			$stmt->execute(array(':indoor'=>$indoor));
		} else {
			$stmt = $this->db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE countryCode = :countryCode AND indoor = :indoor ORDER BY state ASC, gym_name ASC");
			$stmt->execute(array(':countryCode'=>$countryCode,':indoor'=>$indoor));
		}
	
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
		/*$gyms_list = array();
		$currState = "";
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$currState = $row['state'];
			$gyms_list[$currState][] = array($row['gymid'],$row['gym_name']);
		}*/
	}
	
	public function climbingAreaExists($areaID, $indoor) {
		//convenience function to check if a climbing area exists
		//Inputs: $areaid = proposed climbing area id
		//		  $indoor = 0 (outdoor), 1 (indoor)
		//Output: true (if exists), false (does not exist)
		
		$stmt = $this->db->prepare("SELECT * FROM gyms WHERE gymid=:gymid AND indoor=:indoor LIMIT 1");
		$stmt->execute(array(':gymid'=>$areaID,':indoor'=>$indoor));
		return $stmt->fetch(PDO::FETCH_NUM) ? true : false;
	}
	
	public function getClimbingAreaProperty($selectKeyValue) {
		return DBHelper::performSimpleSelectQuery($this->db, self::CLIMBINGAREA_TABLENAME, $selectKeyValue);
	}
	
	
}



?>