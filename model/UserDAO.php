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




























