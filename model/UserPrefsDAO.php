<?php
include "../core/bootstrap.php";

class UserPrefsDAO extends AbstractDAO {
	
	protected $db;
	const USERPREFS_TABLENAME = "userprefs";
	
	/** @const */
	private static $USERPREFS_COL = ["show_boulder", "show_TR", "show_Lead",
			"show_project", "show_redpoint", "show_flash", "show_onsight",
			"minV", "maxV", "minTR", "maxTR", "minL", "maxL",
			"boulderGradingSystemID", "routeGradingSystemID"
	];
	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
	}
	
	public function getUserPrefs($userid) {
		//Return a php array of user prefs for certain userid
		$selectKeyValue = ["userid", $userid];
		return DBHelper::performSimpleSelectQuery($this->db, self::USERPREFS_TABLENAME, $selectKeyValue)[0];
	}
	
	public function setUserPrefs($userid, $changedprefs) {
		/*
		 * $changedprefs is a non-associative array with parameters in order,
		 * corresponding to $USERPREFS_COL
		 */
		
		// Convert $changedprefs to associative array
		$changedprefs = self::convertToAssocArray(self::$USERPREFS_COL, $changedprefs);

		$validprefs = array("show_boulder","show_TR","show_Lead","show_project",
				"show_redpoint","show_flash","show_onsight","minV","maxV",
				"minTR","maxTR","minL","maxL","boulderGradingSystemID",
				"routeGradingSystemID");
		$minValid = array("show_boulder"=>0,"show_TR"=>0,"show_Lead"=>0,
				"show_project"=>0,"show_redpoint"=>0,"show_flash"=>0,
				"show_onsight"=>0,"minV"=>0,"maxV"=>0,"minTR"=>0,"maxTR"=>0,
				"minL"=>0,"maxL"=>0,"boulderGradingSystemID"=>0,
				"routeGradingSystemID"=>0);
		$maxValid = array("show_boulder"=>1,"show_TR"=>1,"show_Lead"=>1,
				"show_project"=>1,"show_redpoint"=>1,"show_flash"=>1,
				"show_onsight"=>1,"minV"=>22,"maxV"=>22,"minTR"=>30,"maxTR"=>30,
				"minL"=>30,"maxL"=>30,"boulderGradingSystemID"=>2,
				"routeGradingSystemID"=>9);
	
		$prefsarevalid = true;

		foreach ($changedprefs as $prefname => $prefvalue) {	
			//check if not numeric or not within valid range
			if (!(is_numeric($prefvalue) &&
					$prefvalue >= $minValid[$prefname] &&
					$prefvalue <= $maxValid[$prefname])) {
						$prefsarevalid = false;
						throw new Exception("The following key-value is not valid: ".$prefname."=>".$prefvalue);
						break; //break out of foreach loop if not valid
			}
		}
	
		if ($prefsarevalid) {
			//prefs are valid, so write to database
			$selectKeyValue = ["userid", $userid];
			return DBHelper::performUpdateQuery($this->db, self::USERPREFS_TABLENAME, $changedprefs, $selectKeyValue);
		} else {
			return false;
		}
	}
	
}