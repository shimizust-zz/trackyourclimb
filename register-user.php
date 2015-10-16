<?php

//include 'mailchimp_subscribe.php';
include "./core/bootstrap.php";

//make sure no fields are blank
if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2']) {
	die('You did not complete all required fields');
}

//check that both passwords match
if ($_POST['pass']!=$_POST['pass2']) {
	die('Your passwords did not match.');
}

//get the user's username
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['pass'];

$regService = new RegistrationService();
$regUserResult = $regService->registerUser($username, $password, $email);
	
if (!$regUserResult["result"]) {
	die($regUserResult["error"]);
} else {
	$userid = $regUserResult["userid"];
	$passhash = $regUserResult["passhash"];
	
	// if login is ok then we add a cookie
	$hour = time() + 57600;
		
	//Here, we set 2 cookies--one containing the username, and the other
	//containing the hashed password, both of which expire in one hour
	setcookie(ID_my_site, $userid, $hour);
	setcookie(Key_my_site, $passhash, $hour);

	//then redirect them to the new members area
	header("Location: newmember.php");

}
?>