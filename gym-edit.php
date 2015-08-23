<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	

include 'php_dataquery/getCountryNameFromCode.php';
$success = 0;
if (isset($_GET['gymid'])) {
	$gymid = $_GET['gymid'];
	
	$stmt = $db->prepare("SELECT * FROM gyms WHERE gymid = :gymid");
	$stmt->execute(array(':gymid'=>$gymid));
	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
}

if (isset($_POST['gymedit-submit']) && isset($_GET['gymid'])) {
	
	$website = $_POST['gym-website'];
	$address = $_POST['gym-address'];
	$city = $_POST['gym-city'];
	
	$stmt = $db->prepare("UPDATE gyms SET website=:website,city=:city,address=:address WHERE gymid=:gymid");
	$stmt->execute(array(':website'=>$website,':address'=>$address,':city'=>$city,':gymid'=>$gymid));
	
	$success = 1;
	
	
	$stmt = $db->prepare("SELECT * FROM gyms WHERE gymid = :gymid");
	$stmt->execute(array(':gymid'=>$gymid));
	$result = $stmt->fetch(PDO::FETCH_ASSOC);	
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
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
				<?php if ($success==1) { ?>
					<div class="alert alert-success">
						Changes were successfully saved!
					</div>
				<?php } ?>
				</div>
				<div class="panel panel-default inputs-panel">
					<div class="panel-heading">
						<h3>Edit Details for <?php echo $result['gym_name']?></h3>
					</div>
					<div class="panel-body">
						<form method="post">
							
							<label for="gym-website"> Website</label>
							<input class="form-control" type="text" name="gym-website" value="<?php echo $result['website']?>">
							<br>
							
							<label for="gym-address">Address</label>
							<input class="form-control" type="text" name="gym-address" value="<?php echo $result['address']?>">
							<br>
							
							<label for="gym-city">City</label>
							<input class="form-control" type="text" name="gym-city" value="<?php echo $result['city']?>">
							<br>
							
							<label for="gym-state">State</label>
							<input class="form-control" type="text" name="gym-state" value="<?php echo $result['state']?>" readonly>
							<br>
							
							<label for="gym-country">Country</label>
							<input class="form-control" type="text" name="gym-country" value="<?php echo country_code_to_country($result['countryCode'])?>" readonly>
							<p><b>To change the state or country of an existing gym, submit a request using this <a href="contact.php" class="alink-underline">contact form</a></b></p>
							<br>
							
							<button type="submit" class="btn btn-success" name="gymedit-submit">Save Changes</button>
						
							
						</form>
					</div>
				</div>
			</div>
			
			
			
			
		</div>
		
		
		
		<?php require("footer.php"); ?>
	</body>
</html>
