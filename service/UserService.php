<?php


include "./../core/bootstrap.php";
include (realpath(dirname(__FILE__).'/../mailchimp_subscribe.php'));

class UserService {
	
	protected $userPrefsDAO;
	protected $userDataDAO;
	
	public function __construct() {
		$this->userPrefsDAO = new UserPrefsDAO();
		$this->userDataDAO = new UserDataDAO();
	}
	
	public function registerUser($username,$password,$email) {
		/*
		 * Perform validation and create all required entries for new user in database
		 * 
		 * Input: $password is in plaintext
		 * Ouput: ["result" => true, "userid" => {userid}, "passhash" => {passhash}] if successful
		 *        ["result" => false, "error" => {error message}] if not successful
		 */

		// Perform check that username/email is not already taken
		// and password is valid (not empty)
		$userDAO = new UserDAO();
		
		if ($userDAO->checkUsernameExists($username)) {
			return ["result" => false, "error" => "Username is already in use."];
		}
		
		if ($userDAO->checkEmailExists($email)) {
			return ["result" => false, "error" => "Email is already in use."];
		}
	
		if (empty($password)) {
			return ["result" => false, "error" => "Password should be non-empty"];
		}
		$password_encrypted = password_hash($password,PASSWORD_DEFAULT);
	
		//return newly-created userID (null if unsuccessful)
		$userid = $userDAO->addUser($username, $password_encrypted, $email);
		
		//initialize empty row in userdata, userprefs, and userrecords table
		$userdataSuccess = $userDAO->initializeUserData($userid);
		$userprefsSuccess = $userDAO->initializeUserPrefs($userid);
		$userrecordsSuccess = $userDAO->initializeUserRecords($userid);
		
		//add email to MailChimp list
		$MailChimp = initialize_mailchimp();
		mailchimp_subscribe($_POST['email'],$MailChimp);
	
		//check if user added successfully
		$add_member = !is_null($userid) && $userdataSuccess &&
		$userprefsSuccess && $userrecordsSuccess;
	
		if ($add_member) {
			return ["result" => true, "userid" => $userid, "passhash" => $password_encrypted];
		} else {
			return ["result" => false, "error" => "Database error: Could not successfully create user"];
		}
	}
	
	public function getUserPrefs($userid) {
		return $this->userPrefsDAO->getUserPrefs($userid);
	}
	
	public function setUserPrefs($userid, $userPrefs) {
		/*
		 * Set user preferences
		 * Input: $userPrefs is an non-associative array with values in order,
		 * correpsonding to the following keys:
		 * "show_boulder","show_TR","show_Lead","show_project",
		 * "show_redpoint","show_flash","show_onsight","minV","maxV",
		 * "minTR","maxTR","minL","maxL","boulderGradingSystemID",
		 * "routeGradingSystemID"
		 */
		
		return $this->userPrefsDAO->setUserPrefs($userid, $userPrefs);
	}
	
	public function getUserCountryCode($userid) {
		return $this->userDataDAO->getUserData($userid)[0]["countryCode"];
	}
	
	public function setUserCountryCode($userid, $countryCode) {
		$propValArray["countryCode"] = $countryCode;
		$this->userDataDAO->updateUserData($userid, $propValArray);
	}
	
	public function getUserMainClimbingAreas($userid) {
		$userDataResult = $this->userDataDAO->getUserData($userid)[0];
		$mainClimbingAreas["main_gym"] = $userDataResult["main_gym"];
		$mainClimbingAreas["main_crag"] = $userDataResult["main_crag"];
		return $mainClimbingAreas;
	}
	
	public function setUserMainGym($userid, $mainGym) {
		$propValArray["main_gym"] = $mainGym;
		$this->userDataDAO->updateUserData($userid, $propValArray);
	}
	
	public function setUserMainCrag($userid, $mainCrag) {
		$propValArray["main_crag"] = $mainCrag;
		$this->userDataDAO->updateUserData($userid, $propValArray);
	}
	
	public function getUserGradingSystems($userid) {
		/*
		 * Return the grading system IDs as an associative array
		 * ["boulder"=>boulder_id,"route"=>route_id]
		 */
		$gradingIDResult = $this->userPrefsDAO->getUserPrefs($userid);
		$gradingSystems["boulder"] = $gradingIDResult["boulderGradingSystemID"];
		$gradingSystems["route"] = $gradingIDResult["routeGradingSystemID"];
		return $gradingSystems;
	}
	
}













