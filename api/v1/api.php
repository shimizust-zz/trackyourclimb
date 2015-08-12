<?php


require_once 'API.class.php';


class MyAPI extends API 
{
	protected $user;
	
	public function __construct($request,$origin) {
		parent::__construct($request);
		
		
	}
	

	protected function users() {
	/*
		users/me/login POST : verify user credentials
		users/me/data GET : get user data
		users/me/preferences GET : get user preferences
	*/
	
		
	require_once '../../dbconnect.php';

		
	if ($this->args[0] == 'me') {
	
		if ($this->args[1] == 'login') {
			
			if ($this->method=='POST') {
			
				$post = json_decode(file_get_contents('php://input'),true); //return a php array
				$info = $post['info'];
				
				//check if array key exists for username and password
				if (array_key_exists('username',$info) && array_key_exists('pass',$info)) {
					$username = $info['username'];
					$pass = $info['pass'];
					
					$stmt = $db->prepare('SELECT username,pass_hash FROM users WHERE username=?');
					$stmt->execute(array($info['username']));
					$userhash_result = $stmt->fetch(PDO::FETCH_ASSOC);
					$checkuserexists = $stmt->rowCount();
					
					if ($checkuserexists && password_verify($info['pass'],$userhash_result['pass_hash'])) {
						return array("verified"=>"1","error"=>"");
					}
					else {
						return array("verified"=>"0","error"=>"Incorrect username or password");
					}
					
				} else {
					return array("verified"=>"0","error"=>"A required field was not specified. These were in info:".print_r(array_keys($info))." and the info contains: ".print_r($info));
				}
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
    else if empty($this->args) {
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

















































