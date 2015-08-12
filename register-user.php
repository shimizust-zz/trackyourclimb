<?php

include 'mailchimp_subscribe.php';

//make sure no fields are blank
	if (!$_POST['username'] | !$_POST['pass'] | !$_POST['pass2']) {
		die('You did not complete all required fields');
	}
	
	//check if username or email is in use
	
	//get the user's username
	$usercheck = $_POST['username'];
	$emailcheck = $_POST['email'];
	
	
	
	
	$stmt = $db->prepare('SELECT username FROM users WHERE username=?');
	$stmt->execute(array($usercheck));
	$usercheck_query = $stmt->rowCount();
	if ($usercheck_query>0) {
		die('Sorry, the username '.$_POST['username'].'is already in use.');
	}
	$stmt = $db->prepare('SELECT email FROM users WHERE email = ?');
	$stmt->execute(array($emailcheck));
	$emailcheck_query = $stmt->rowCount();
	if ($emailcheck_query > 0) {
		die('Sorry, the email '.$_POST['email'].'is already in use.');
	}
	
	
	
	//check that both passwords match
	if ($_POST['pass']!=$_POST['pass2']) {
		die('Your passwords did not match.');
	}
	
	//encrypt the password
	$_POST['pass'] = password_hash($_POST['pass'],PASSWORD_DEFAULT);

		
	//now insert into the users table and create entries in userdata/userprefs 
	//userrecords tables
	$stmt = $db->prepare('INSERT INTO users (username,pass_hash,email) VALUES
		(:username,:pass,:email)');
	$stmt->execute(array(':username'=>$_POST['username'],
		':pass'=>$_POST['pass'],':email'=>$_POST['email']));
	$id = $db->lastInsertId();
	
	$stmt2 = $db->prepare('INSERT INTO userdata (userid) VALUES (?)');
	$stmt2->execute(array($id));
	
	$stmt3 = $db->prepare('INSERT INTO userprefs (userid) VALUES (?)');
	$stmt3->execute(array($id));
	
	$stmt4 = $db->prepare('INSERT INTO userrecords (userid,highestBoulderProject,
		highestBoulderRedpoint,highestBoulderFlash,highestBoulderOnsight,
		highestTRProject,highestTRRedpoint,highestTRFlash,highestTROnsight,
		highestLeadProject,highestLeadRedpoint,highestLeadFlash,
		highestLeadOnsight) VALUES (:userid,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1,-1)');
	$stmt4->execute(array(':userid'=>$id));
	
	$add_member = $stmt->rowCount()>0 && $stmt2->rowCount()>0 &&
		$stmt3->rowCount()>0 && $stmt4->rowCount()>0;
	
	//add email to MailChimp list
	$MailChimp = initialize_mailchimp();
	mailchimp_subscribe($_POST['email'],$MailChimp);
	
	if ($add_member) {
		// if login is ok then we add a cookie 
		
		//$_POST['username'] = stripslashes($_POST['username']); 
		$hour = time() + 57600; 
			
		//Here, we set 2 cookies--one containing the username, and the other
		//containing the hashed password, both of which expire in one hour
		setcookie(ID_my_site, $id, $hour); 
		setcookie(Key_my_site, $_POST['pass'], $hour);	 
			 
		//then redirect them to the new members area 
		header("Location: newmember.php"); 	
	}
?>