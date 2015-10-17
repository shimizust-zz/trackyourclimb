<?php


include "./../core/bootstrap.php";
include (realpath(dirname(__FILE__).'/../mailchimp_subscribe.php'));

class RegistrationService {
	
	public function __construct() {

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
	
}













