<?php
 

 //Checks if there is a valid cookie. If so, do nothing; otherwise,
 //redirect to the index page
 
 if(isset($_COOKIE['ID_my_site'])) {
 //if there is, it logs you in and directs you to the members page
 	
 	$userid = $_COOKIE['ID_my_site'];
	$pass = $_COOKIE['Key_my_site'];
	$remember_me = $_COOKIE['remember-me'];
	
	$stmt = $db->prepare('SELECT * FROM users WHERE userid = ?');
	$stmt->execute(array($userid));
	$info = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($stmt->rowCount()>0) {
		if ($pass != $info['pass_hash']) {
			//no valid cookie key, go to main login page
			header('Location: index.php');
		}
		else {
			//a valid cookie id and key, so extend cookie lifetime to a 
			//specific time period from now. Basically, the user will be logged out after a 
			//certain time period from the last point of activity.
			
			if ($remember_me) {
				$time = time() + 1210000; //2 weeks
			}
			else {
				$time = time() + 56400; //16 hours
			}

		
			setcookie('ID_my_site', $userid, $time); 
			setcookie('Key_my_site', $pass, $time);	 
			setcookie('remember-me', $remember_me, $time);
		}		
	}
	else {
		//not a valid user
		header('Location: index.php');
	}
 }
 else {
 	//no cookie
 	header('Location: index.php');
 }
?>