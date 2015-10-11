<?php

/*
Endpoints/Args:
- users
	- | GET : get number of users
	- me/login | POST : verify user credentials
	- me/data | GET : get user data
	- me/preferences | GET : get user preferences
- 
*/

require_once 'APIBase.php';
require '../../db.php';

class MyAPI extends APIBase 
{
	protected $user;
	protected $db;
	
	public function __construct($request,$origin) {
		parent::__construct($request);
		
		//$args is is an array of arguments after the endpoint
		$this->db = DB::getDBConnection();
	}
	
	protected function verifyUser() {
		//Verify user making request using HTTP Basic Auth
		//Return an array stating whether user is verified and the userid
		// {'verified': {0 or 1}, 'userid'=>{userid}}
		
		$headers = getallheaders();
		$auth = $headers["Authorization"];
		if ($auth) { //property exists
			//Get decoded password
			$userpwd = explode(":",base64_decode(explode(" ", $auth)[1]), 2); //only look for first instance of colon (allowing ':' in password)
			$username = $userpwd[0];
			$pwd = $userpwd[1];
			
			return $this->_verifyUsernamePassword($username, $pwd);
		} else {
			return array('verified'=>'0');
		}
	}
	
	private function _verifyUsernamePassword($username, $password) {
		$stmt = $this->db->prepare('SELECT userid,username,pass_hash FROM users WHERE username=?');
		$stmt->execute(array($username));
		$userhash_result = $stmt->fetch(PDO::FETCH_ASSOC);
		$checkuserexists = $stmt->rowCount();
		
		if ($checkuserexists && password_verify($password,$userhash_result['pass_hash'])) {
			return array('verified'=>'1','userid'=>$userhash_result['userid']);
		}
		else {
			return array('verified'=>'0');
		}
	}
	

	protected function users() {
	/*
	 * users/ GET : get number of users in system
	*/

		if (empty($this->args) && $this->method=='GET') {
			//return number of users
			$stmt = $this->db->prepare("SELECT COUNT(username) FROM users");
			$stmt->execute();
			$users_result = $stmt->fetch(PDO::FETCH_NUM);
			return array("number"=>$users_result[0]);	
		} 
		
		else if ($this->args[0] == 'user' && $this->args[1] == 'valid' && $this->method == 'GET' && count($this->args) == 2) {
			//validate user using Basic Auth and return whether user is valid along with userid
			return $this->verifyUser();	
		} 
		
		else if (is_numeric($this->args[0])) {
			$userid = $this->args[0];
			if ($this->args[1] == 'prefs' && $this->method == 'GET') {
				// get whether the user is verified, and return user preferences
				$verifiedResult = $this->verifyUser();
				if ($verifiedResult['verified']) {
					$stmt = $this->db->prepare("SELECT * FROM userprefs WHERE userid = ?");
					$stmt->execute(array($userid));
					$userprefs_result = $stmt->fetch(PDO::FETCH_ASSOC);
					return $userprefs_result;
				} else {
					return $verifiedResult;
				}				
			}
			
			else if ($this->args[1] == 'prefs' && $this->method == 'POST') {
				//modify user prefs
				$verifiedResult = $this->verifyUser();
				if ($verifiedResult['verified']) {
					//decode json in post body
					$info = json_decode($_POST['info'],true);
					
					$show_boulder = $info['show_boulder'];
					$show_TR = $info['show_TR'];
					//$show_
					
					$stmt = $this->db->prepare("SELECT * FROM userprefs WHERE userid = ?");
					$stmt->execute(array($userid));
					$userprefs_result = $stmt->fetch(PDO::FETCH_ASSOC);
					return $userprefs_result;
				} else {
					return $verifiedResult;
				}
			}
			
			else if ($this->args[1] == 'profile' && $this->method == 'GET') {
				//get userprofile data
				$verifiedResult = $this->verifyUser();
				if ($verifiedResult['verified']) {
					$stmt = $this->db->prepare("SELECT * FROM userdata WHERE userid = ?");
					$stmt->execute(array($userid));
					$userprofile_result = $stmt->fetch(PDO::FETCH_ASSOC);
					return $userprofile_result;
				} else {
					return $verifiedResult;
				}
			}
			
		}
	}
	
	protected function workouts() {
	/*
		workouts/workout POST : add a workout for specified user
		workouts/workout/{workoutid} GET : retrieve workout details for specified workout index
		workouts GET : retrieve aggregate information about workouts. Query parameters:
			'data' = 'number' -> returns the number of workouts 
			
	*/
	
		require '../../dbconnect.php';
			
		if ($this->args[0] == 'workout') {

			if ($this->method=='POST') {
				//add a workout
				
				$info = json_decode($_POST['info'],true);
				
				//verify user credentials
				$verified = $this->_verifyuser($info);
				
				if ($verified['verified']) {
					$userid = $verified['userid'];
					$gymid = $info['gymid'];
					$date_workout = $info['date'];
					
					$boulder = $info['boulder'];
					$boulder_notes = $info['boulder_notes'];
					$tr = $info['tr'];
					$tr_notes = $info['tr_notes'];
					$lead = $info['lead'];
					$lead_notes = $info['lead_notes'];
					$other_notes = $info['other_notes'];
					
					//Create workout entry (doesn't have points logged yet)
					$stmt = $db->prepare("INSERT INTO workouts (userid,date_workout,gymid,boulder_notes,tr_notes,lead_notes,other_notes) 
					VALUES (:userid,:date_workout,:gymid,:boulder_notes,:tr_notes,
					:lead_notes,:other_notes)");
					
					$stmt->execute(array(':userid'=>$userid,':date_workout'=>$date_workout,
					':gymid'=>$gymid,':boulder_notes'=>$boulder_notes,':tr_notes'=>
					$tr_notes,':lead_notes'=>$lead_notes,':other_notes'=>$other_notes));
					
					$workoutid = $db->lastInsertId(); 
					
					
					
					return $gymid;
				}
				
			}
			
			
		}
		else if (empty($this->args)) {
			if ($this->method=="GET") {
				if ($_GET['data']=="number") {
					//return the total number of workouts logged
					
				}
			}
		}
	
	}
	
	private function _verifyuser($info) {
	/**
		Checks that all fields are present and username/pass matches. $info comes directly from json-decoded $_POST['info'] (so json_decode($_POST['info'],true) ).
		
		Return 1 if user is verified, 0 otherwise
	*/
		require '../../dbconnect.php';
		
					
		//check if array key exists for username and password
		if (array_key_exists('username',$info) && array_key_exists('pass',$info)) {
			$username = $info['username'];
			$pass = $info['pass'];
			
			$stmt = $db->prepare('SELECT userid,username,pass_hash FROM users WHERE username=?');
			$stmt->execute(array($info['username']));
			$userhash_result = $stmt->fetch(PDO::FETCH_ASSOC);
			$checkuserexists = $stmt->rowCount();
			
			if ($checkuserexists && password_verify($info['pass'],$userhash_result['pass_hash'])) {
				return array('verified'=>'1','userid'=>$userhash_result['userid']);
			}
			else {
				return array('verified'=>'0','userid'=>'0');
			}
			
		} else {
			return array('verified'=>'0','userid'=>'0');
		}
			
		}
}

//The code below actually implements the API and creates a new API object to handle requests
// Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
	
	//At this point, the URI should look like the following after being rewritten by .htaccess: Original URI: trackyourclimb.com/api/v1/users/user/1?apikey=123abc
	//Rewritten URI: trackyourclimb.com/api/v1/api.php?request=users/user/1&apikey=123abc
	if (!array_key_exists('apikey',$_GET)) {
		throw new Exception('No API Key provided');
	} else if ($_GET['apikey']!= '123abc') {
		throw new Exception('Invalid API Key');
	}
		
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);

    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => $e->getMessage()));
}

?>

















































