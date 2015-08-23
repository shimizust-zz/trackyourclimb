<?php 
	
//connect to database
include 'dbconnect.php';

$notvalidemail = "";
$error_message = "";
$resetpwd = 0; //whether to display reset password form
$pwdsnotsame = "";
$notvalidusername = "";



//check if valid query string exists. If so, then show the password reset form
if (isset($_GET['id'])) {
	//there is an id field present in url string
	
	//check if this id matches with table of password change requests
	$id_req = $_GET['id'];
	$stmt3 = $db->prepare('SELECT * FROM password_change_requests INNER JOIN
	users ON password_change_requests.username=users.username
	WHERE request_hash = :id_req');
	$stmt3->execute(array(':id_req'=>$id_req));
	
	if ($stmt3->rowCount() > 0) {
		//valid request_hash	
		$request_result = $stmt3->fetch(PDO::FETCH_ASSOC);
		
		//check if password reset request has expired
		$time_request = strtotime($request_result['time_request']);
		//convert to UNIX timestamp
		
		$curtime = time();
		if (($curtime-$time_request) > 86400) {
			//request has expired, so delete this request from table	
			$stmt4 = $db->prepare('DELETE FROM password_change_requests WHERE 
			request_hash = :id_req');
			$stmt4->execute(array(':id_req'=>$request_result['request_hash']));
		} 
		else {
			//request is within valid timeframe, so direct to password reset
			$resetpwd = 1; //show reset password form instead
			$username_req = $request_result['username'];
			$userid_req = $request_result['userid'];
			
			//check if the reset password form was submitted
			if (isset($_POST['resetsubmit'])) {
				//check if the username matches username_req

				
				if ($_POST['username']==$username_req) {
					//usernames match
					
					//check that both passwords are the same
					if ($_POST['pass']==$_POST['pass2']) {
						//update passwords in users table
						$pass_hash = password_hash($_POST['pass'],PASSWORD_DEFAULT);
						
						$stmt5 = $db->prepare("UPDATE users SET pass_hash = :pass_hash WHERE
						username = :username");
						$stmt5->execute(array(':pass_hash'=>$pass_hash,':username'=>$_POST['username']));
						
						$hour = time() + 56400;
						
						setcookie('ID_my_site', $userid_req, $hour); 
						setcookie('Key_my_site', $pass_hash, $hour);	
						
						//delete all rows in password requests table matching this username
						$stmt6 = $db->prepare('DELETE FROM password_change_requests WHERE 
						username = :username');
						$stmt6->execute(array(':username'=>$_POST['username']));
						
						//redirect to main page
						header("Location: index.php");
					}
					else {
						//passwords don't match
						$pwdsnotsame = "<p class='bg-danger'>Passwords don't match</p>";
					}
					
				}
				else {
					//usernames don't match
					$resetpwd = 1;
					$notvalidusername = "<p class='bg-danger'>Username invalid</p>";
				}
			}
		}
	}
	else {
		$error_message = "<p class='bg-danger'>Invalid password reset link.</p>";
	}
	
}

elseif (isset($_POST['forgotsubmit'])) {
	//check if email is valid or not
	$stmt = $db->prepare('SELECT email,username FROM users WHERE email = ?');
	$stmt->execute(array($_POST['email']));
	$emailcheck_query = $stmt->rowCount();
	

	if ($emailcheck_query > 0) {
		//email is valid
		//retrieve username corresponding to the email address
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$username_req = $result['username'];
		$email_req = $result['email'];
		$request_hash = hash('sha256',randString(12));

		//add an entry to the database with random hash, username, and email
		$stmt2 = $db->prepare('INSERT INTO password_change_requests (username,
		time_request,request_hash) 
		VALUES (:username_req,:date,:request_hash)');
		$stmt2->execute(array(':username_req'=>$username_req,':date'=>date('Y-m-d H:i:s'),':request_hash'=>$request_hash));
		//send a message to this email with password reset details 
		
		//should be a url with the following:
		//www.trackyourclimb.com/forgot.php?id=$request_hash
		$to = $email_req;
		$from_name = 'Admin';
		$from_email = 'admin@trackyourclimb.com';
		$subject = 'TrackYourClimb.com Password Reset';
		$message = "Hello,\r\n\r\n We received a request to reset your password. Please click on the following link within 24 hours to reset your password: \r\n".
			"http://www.trackyourclimb.com/forgot.php?id=".$request_hash."\r\n\r\nYour username is: ".$username_req."\r\n\r\nDo not reply to this email.";
		$headers = 'From: '.$from_email."\r\n".'Reply-To: '.$from_email.
			"\r\n".'X-Mailer: PHP/'.phpversion();
		$mailsuccess = mail($to,$subject,$message,$headers);
		
		$messagesent = "<p class='bg-success'>A message has been sent to your email address with password reset details.</p>";
	}
	else {
		//display message that email not found for a registered user
		$notvalidemail = "<p class='bg-danger'>".$_POST['email']." does not match that of a registered user.</p>";
	}
}
		 		
				
function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789')
{
    $str = '';
    $count = strlen($charset);
    while ($length--) {
        $str .= $charset[mt_rand(0, $count-1)];
    }
    return $str;
}				
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gym Climbing Tracker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>


		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<div id="wrap">
			<div id="main">
			<?php include_once("analyticstracking.php") ?>
			
			<a href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Return to main page.</a>
			
			<!--website content here-->
			<?php if ($resetpwd == 0) { ?>
				
				<form class="form-horizontal" id="forgotform" action="<?php echo $_SERVER['PHP_SELF']?>" method='POST'>
					<div class="panel panel-default input-panel">
						<div class="panel-heading">
							<h4>Forgot Username or Password?</h4>
						</div>
						<div class="panel-body">
							<?php echo $error_message ?>
							<p>If you've forgotten your username or password, enter the email you signed up with. You'll receive your username and a link to reset your password.</p>
							<div class="control-group center">
								<label class="control-label" for="email">Email: </label>
								<div class="controls">
									<input id="email" name="email" type="email" placeholder="" class="input-xlarge" required>
									<?php echo $notvalidemail; ?>
								</div>
							</div>
							<?php echo $messagesent; ?>
							<!-- Button -->
							<div class="control-group center">
								<!--This label just creates a space between password field and login button-->
								<label class="control-label" for="forgotsubmit"></label>
								<div class="controls">
									<button id="forgotsubmit" name="forgotsubmit" class="btn btn-primary">
										Submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</form>
			<?php } elseif ($resetpwd == 1) {	?>

				<form class="form-horizontal" id="resetform" action="<?php echo $_SERVER['PHP_SELF'].'?id='.$id_req ?>" method='POST'>
					<div class="panel panel-default input-panel">
						<div class="panel-heading">
							<h4>Reset Password</h4>
						</div>
						<div class="panel-body">

							<div class="control-group center">
								<label class="control-label" for="username">Username: </label>
								<div class="controls">
									<input id="username" name="username" type="text" placeholder="" class="input-xlarge" required>
								</div>
								<?php echo $notvalidusername; ?>
								
								<label class="control-label" for="pass">New Password: </label>
								<div class="controls">
									<input id="pass" name="pass" type="password" placeholder="" class="input-xlarge" required>
								</div>
								
								<label class="control-label" for="pass2">Confirm New Password: </label>
								<div class="controls">
									<input id="pass2" name="pass2" type="password" placeholder="" class="input-xlarge" required>
								</div>
								<?php echo $pwdsnotsame; ?>
							</div>
							
							<!-- Button -->
							<div class="control-group center">
								<!--This label just creates a space between password field and login button-->
								<label class="control-label" for="resetsubmit"></label>
								<div class="controls">
									<button id="resetsubmit" name="resetsubmit" class="btn btn-primary">
										Submit</button>
								</div>
							</div>
						</div>
						
					</div>
				</form>
				
			<?php } ?>
			</div>
		</div>
		
		
		
		<?php require("footer.php"); ?>
	</body>
</html>
