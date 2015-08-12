<?php

require_once('DBConnectionManager.php');
class ClimbingAreaDAO {

	public function __construct() {
		$DBManager = new DBConnectionManager();
		$db = $DBManager->connect();
	}
	
	/*
	* This function takes in a country code ($countryCode) and climbing area type, $isIndoor. 
	* $isIndoor = TRUE (indoor)
	* $isIndoor = FALSE (outdoor)
	*/
	public function getGymList($countryCode,$isIndoor) {


		if ($isIndoor == FALSE) {
			$stmt = $db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE countryCode = :countryCode ORDER BY state ASC, gym_name ASC");
			$stmt->execute(array(':countryCode'=>$countryCode));
		} else {
			$stmt = $db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE countryCode = :countryCode AND indoor = :indoor ORDER BY state ASC, gym_name ASC");
			$stmt->execute(array(':countryCode'=>$countryCode,':indoor'=>$indoor));
		}

		$gyms_list = array();
		$currState = "";
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$currState = $row['state'];
			$gyms_list[$currState][] = array($row['gymid'],$row['gym_name']);
		}
	}
	
}



?>