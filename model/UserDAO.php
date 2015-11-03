<?php


include './../core/bootstrap.php';


class UserDAO {
	
	protected $db;
	
	public function __construct() {
		$DBManager = new DBConnectionManager();
		$this->db = $DBManager->connect();
	}
	
	public function checkUsernameExists($username) {
		$stmt = $this->db->prepare('SELECT username FROM users WHERE username=?');
		$stmt->execute(array($username));
		$usercheck_query = $stmt->rowCount();
		if ($usercheck_query>0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function checkEmailExists($email) {
	$stmt = $this->db->prepare('SELECT email FROM users WHERE email = ?');
		$stmt->execute(array($email));
		$emailcheck_query = $stmt->rowCount();
		if ($emailcheck_query > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function addUser($username, $password_encrypted, $email) {
		try {
			$stmt = $this->db->prepare('INSERT INTO users (username,pass_hash,email) VALUES
			(:username,:pass,:email)');
			$stmt->execute(array(':username'=>$username,
					':pass'=>$password_encrypted,':email'=>$email));
			$id = $this->db->lastInsertId();
			return $id;
		} catch (PDOException $e) {
			return null;
		}
		
	}
	
	public function initializeUserData($userid) {
		try {
			$stmt = $this->db->prepare('INSERT INTO userdata (userid) VALUES (?)');
			$stmt->execute(array($userid));
			return true;
		} catch (PDOException $e) {
			return null;
		}
	}
	
	public function initializeUserPrefs($userid) {
		try {
			$stmt = $this->db->prepare('INSERT INTO userprefs (userid) VALUES (?)');
			$stmt->execute(array($userid));
			return true;
		} catch (PDOException $e) {
			return null;
		}
	}
	
	public function initializeUserRecords($userid) {
		try {
			//Create entry in userrecords table
			$stmt = $this->db->prepare('INSERT INTO userrecords (userid,highestBoulderProject,
			highestBoulderRedpoint,highestBoulderFlash,highestBoulderOnsight,
			highestTRProject,highestTRRedpoint,highestTRFlash,highestTROnsight,
			highestLeadProject,highestLeadRedpoint,highestLeadFlash,
			highestLeadOnsight) VALUES (:userid,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1)');
				$stmt->execute(array(':userid'=>$userid));
			return true;
		} catch (PDOException $e) {
			return null;
		}
	}
	
	
	public function getNumUsers() {
		//return total number of users
		$stmt = $this->db->prepare("SELECT COUNT(username) FROM users");
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_NUM);
		$numUsers = $result[0];
		return $numUsers;
	}
	
	public function getUserPrefs($userid) {
		//Return a php array of user prefs for certain userid
		$stmt = $this->db->prepare("SELECT * FROM userprefs WHERE userid = ?");
		$stmt->execute(array($userid));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	public function getUserProfile($userid) {
		$stmt = $this->db->prepare("SELECT * FROM userdata WHERE userid = ?");
		$stmt->execute(array($userid));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	public function getUserRecords($userid) {
		$stmt = $this->db->prepare("SELECT * FROM userrecords WHERE userid = ?");
		$stmt->execute(array($userid));
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return $result;
	}
	
	public function setUserPrefs($userid, $changedprefs) {
		//$changedprefs is an associative array of preferences to change
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
				"show_onsight"=>1,"minV"=>15,"maxV"=>15,"minTR"=>24,"maxTR"=>24,
				"minL"=>24,"maxL"=>24,"boulderGradingSystemID"=>2,
				"routeGradingSystemID"=>9);
		
		$prefsarevalid = true;
		$stmtStr = "UPDATE userprefs SET ";
		foreach ($changedprefs as $prefname => $prefvalue) {
			//first check that it's a valid property
			if (in_array($prefname, $validprefs)) {
				//check if not numeric or not within valid range
				if (!(is_numeric($prefvalue) && 
					$prefvalue >= $minValid[$prefname] && 
					$prefvalue <= $maxValid[$prefname])) {
					$prefsarevalid = false;	
					break; //break out of foreach loop if not valid
				}
			} else {
				$prefsarevalid = false;
				break;
			}
		}
		
		if ($prefsarevalid) {
			//prefs are valid, so write to database
			$stmtString = "UPDATE userprefs SET ".
				DBHelper::genPrepareString($changedprefs).
				" WHERE userid=:userid";
			echo $stmtString;
			$stmt = $this->db->prepare($stmtString);
			
			$executeArray = DBHelper::genExecuteArray($changedprefs);
			$executeArray[':userid'] = $userid;
			
			return $stmt->execute($executeArray);
			
		} else {
			return false;
		}
	}
	
	public function setUserProfile($userid, $changedprofile) {
		
		/*
		 * not including:
		 * zipcode
		 * userimage
		 * userimage_thumbnail
		 * height
		 * armspan
		 * apeindex
		 * weight
		 */
		$validprofile = array("email","firstname","lastname","birthday",
				"date_climbingstart","gender",
				"main_gym","aboutme","countryCode","main_crag");
		
		//check validity of each property
		$profileisvalid = true;
		foreach ($changedprofile as $key => $val) {
			if (in_array($key,$validprofile)) {
				if ($key == "email" && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
					//if invalid email
					return ["result" => false, "error" => "Invalid email address."];
				} else if (in_array($key, array("birthday", "date_climbingstart"))) {
					//validate date
					$date = DateTime::createFromFormat('Y-m-d', $val);
					$date_errors = DateTime::getLastErrors();
					if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
						return ["result" => false, "error" => "Invalid date for: [".$key."]"];
					}
				} else if ($key == "gender" && !in_array($val, array("Male","Female","Other"))) {
					return ["result" => false, "error" => "Invalid gender specified"];
				} else if (in_array($key, array("main_gym","main_crag"))) {
					//check that this gym id exists
					$areaType = $key=="main_gym" ? 1 : 0;
					$areaExists = ClimbingAreaDAO::climbingAreaExists($val, $areaType);
					if (!$areaExists) {
						return ["result" => false, "error" => "Climbing area does not exist."];
					}
				} else if ($key == "countryCode") {
					//check that CountryCode exists
					
				}
			} else {
				$profileisvalid = false;
				break;
			}
		}
		
		if ($profileisvalid) {
			$prepStr = DBHelper::genPrepareString($changedprofile);
			$stmtStr = "UPDATE userdata SET ".$prepStr." WHERE userid=:userid";
			
			$stmt = $this->db->prepare($stmtStr);
			$executeArray = DBHelper::genExecuteArray($changedprofile);
			$executeArray[':userid'] = $userid;
			return ["result" => $stmt->execute($executeArray)];
		}
	}
	
	
	public function resetPassword($userid,$password) {
		//TODO
	}
	
	
}




























