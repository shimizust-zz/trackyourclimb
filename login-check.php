<?php
	// makes sure they filled it in (Bootstrap already checks required fields, so this shouldn't run)
 	if(!$_POST['username'] | !$_POST['pass']) {
 		die('You did not fill in a required field.');
 	}
	
	
	try {
		$stmt = $db->prepare('SELECT userid,pass_hash FROM users WHERE username=?');
		$stmt->execute(array($_POST['username']));
		$checkuserexists = $stmt->rowCount();
		$userhash_result = $stmt->fetch(PDO::FETCH_ASSOC);
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	
	
	if ($checkuserexists==0 || !password_verify($_POST['pass'],$userhash_result['pass_hash'])) {
		die('Username or password incorrect or missing. <a href=index.php>Click here to go back.</a>');
	}
	else {
		//username and password is valid, so create a cookie
		$userid = $userhash_result['userid'];
		
			
		if (isset($_POST['remember-me'])) {
			$time = time() + 1210000; //2 weeks
			$remember_me = true;
			}
		else {
			$time = time() + 57600; //16 hours
			$remember_me = 0; //can't do false for some reason
		}
		
		//Here, we set 2 cookies--one containing the username, and the other
		//containing the hashed password, both of which expire in a specific time period
		setcookie('remember-me', $remember_me, $time);
		setcookie('ID_my_site', $userid, $time); 
		setcookie('Key_my_site', $userhash_result['pass_hash'], $time);	 
		
		
		//then redirect them to the members area 
		header("Location: main.php"); 
	}

?>