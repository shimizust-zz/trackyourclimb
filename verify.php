<?php
//connect to database
include 'dbconnect.php';



//check if valid query string exists. 
if (isset($_GET['regid'])) {
	//there is an id field present in url string
	
	//check if this id matches with table of verify requests
	$regid_req = $_GET['regid'];
	
	$stmt = $db->prepare('SELECT * FROM verify_requests INNER JOIN
	users ON verify_requests.userid_new=users.userid
	WHERE verify_hash = :regid_req');
	$stmt->execute(array(':regid_req'=>$regid_req));
	$verify_result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($stmt->rowCount() > 0 ) {
		$userid = $verify_result['userid_new'];
		$pass_hash = $verify_result['pass_hash'];
		
		//valid verify_hash, so set user to verified
		$stmt2 = $db->prepare('UPDATE users SET verified=1 WHERE userid=:userid');
		$stmt2->execute(array(':userid'=>$userid));

		$alert = '<div class="alert alert-success">
						Thank you for verifying!
						
						You are now able to <a href="workout-input.php">log climbs</a>. 
					</div>';
					
		//set cookies
		$hour = time() + 56400;
						
		setcookie('ID_my_site', $userid, $hour); 
		setcookie('Key_my_site', $pass_hash, $hour);	
		
		//delete all rows in verify_requests table matching this userid
		$stmt6 = $db->prepare('DELETE FROM verify_requests WHERE 
		userid_new = :userid');
		$stmt6->execute(array(':userid'=>$userid));
	}
	else {
		
		$alert = '<div class="alert alert-danger">
						Invalid verification code.
						Please click on the link sent to your email when you signed up. 
					</div>';

	}
	
}
else {
	header('Location: index.php');
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
				<?php include_once("php_common/analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				Hey There.
				<div class="container-fluid">
					<?php //echo $alert; ?>
				</div>
			</div>
			
			
			
			
		</div>
		
		
		
		<?php require("php_common/footer.php"); ?>
	</body>
</html>