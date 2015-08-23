<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

if ($_GET['username']) {
	$username = $_GET['username'];
	
	$stmt = $db->prepare("SELECT users.userid,users.username,
	users.email, userdata.firstname,
	userdata.lastname, userdata.gender, userdata.main_gym, 
	userdata.userimage 
	FROM users
	INNER JOIN userdata ON users.userid = userdata.userid WHERE 
	users.username = :username");
	$stmt->execute(array(':username'=>$username));
	$otheruser_result = $stmt->fetch(PDO::FETCH_ASSOC);

	
	$stmt = $db->prepare("SELECT userimage FROM userdata WHERE userid = :userid_query");
	$stmt->execute(array(':userid_query'=>$otheruser_result['userid']));
	$actual_image_name_result = $stmt->fetch(PDO::FETCH_ASSOC);
	$actual_image_name = $actual_image_name_result['userimage'];

	$path = "userimages/";
	//create image tag
	if (is_null($otheruser_result['userimage'])) {
		//put in default image
		$image = "<img src = \"images/default_user.png\" alt=\"\" style=\"display:block\">";
	} else {
		$image = "<img src='".$path.$actual_image_name."' id=\"photo\"";
	}

	//extract gym name
	$stmt5 = $db->prepare("SELECT gym_name FROM gyms WHERE gymid = :gymid");
	$stmt5->execute(array(':gymid'=>$otheruser_result['main_gym']));
	$main_gym_result = $stmt5->fetch(PDO::FETCH_ASSOC);
	$main_gym_name = $main_gym_result['gym_name'];		
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
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<?php include_once("php_common/analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div class="wrap">
			<div class="main">
				
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="col-sm-12">
								<div id = "userimage">
								<table>
									<tr><td><?php echo $image ?></td></tr>	
								</table>	
								</div>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="col-sm-12">
								<div class="panel panel-default" id = "basicinfopanel">
									<div class="panel-heading">Basic Information</div>
									<div class = "panel-body">
									<table id = "basicinfotable">
										<tr><td><h4>First Name: </h4></td>
										<td><?php echo $otheruser_result['firstname']; ?></td></tr>
										
										<tr><td><h4>Last Name: </h4></td>
										<td><?php echo $otheruser_result['lastname']; ?></td></tr>
										
										<tr><td><h4>Gender: </h4></td>
										<td><?php echo $otheruser_result['gender']; ?></td></tr>
										
										<tr><td><h4>Main Gym: </h4></td>
										<td><?php echo $main_gym_name; ?></td></tr>

									</table>
										
										
									
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
		
		<!-- Also show achievements, like highest rated boulder, TR, etc.-->
		
</body>
</html>
<?php
}
?>