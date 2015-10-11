<?php

//File for testing REST API
//TODO: replace with phpunit


class APITesting {
	
	const preURL = 'http://localhost/climbtracker/api/v1/';
	const apiKeyStr = 'apikey=123abc';
	const username = 'shimizu';
	const password = 'pi3141';
	
	public function testUsers() {
		//test number of users
		$URL = self::preURL.'users?'.self::apiKeyStr;
		$method = 'GET';
		$ch = self::initCurl($URL, $method);
		$result = self::getPrintResults($ch, $URL, $method);
	
		//test users/user/valid
		$URL=self::preURL.'users/user/valid?'.self::apiKeyStr;;
		$result = self::performAuthGET($URL);
		$userid = json_decode($result, true)['userid'];
	
		//test getting user prefs
		$URL=self::preURL.'users/'.$userid.'/prefs?'.self::apiKeyStr;
		$result = self::performAuthGET($URL);

		//test getting user profile data
		$URL=self::preURL.'users/'.$userid.'/profile?'.self::apiKeyStr;
		$result = self::performAuthGET($URL);
		
		
	}
	
	protected static function performAuthGET($URL) {
		$method = 'GET';
		$ch = self::initCurl($URL, $method);
		$ch = self::setCurlBasicAuth($ch, self::username, self::password);
		$result = self::getPrintResults($ch, $URL, $method);
		return $result;
	}
	
	protected static function getPrintResults($ch, $URL, $method) {
		$result = curl_exec($ch);
		echo "URL ($method): $URL: $result<br>";
		curl_close($ch);
		return $result;
	}
	
	protected static function initCurl($URL, $method) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); //When running curl_exec($ch), return output as a string instead of printing it out directly.
		curl_setopt($ch, CURLOPT_HEADER, false); //Don't output the response header
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		
		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'Accept: */*';

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		return $ch;
	}
	
	protected static function setCurlBasicAuth($ch, $username, $password) {
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
		return $ch;
	}
}

APITesting::testUsers();




?>