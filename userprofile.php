<?php
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	



//check if modal form for editing user information has been submitted

if (isset($_POST['infosubmit'])) {

	$firstname = $_POST['firstname'];
	$lastname = $_POST['lastname'];
	$email = $_POST['email'];
	$gender = $_POST['gender'];
	$email = $_POST['email'];
	$birthday = $_POST['birthday'];
	$date_climbingstart = $_POST['date_climbingstart']; 
	$gymid = $_POST['gymid'];

	$stmt=$db->prepare("UPDATE userdata SET firstname=:firstname,lastname=
		:lastname,gender=:gender,birthday=:birthday,date_climbingstart=
		:date_climbingstart,main_gym=:main_gym WHERE userid=:userid");
	$stmt->execute(array(':firstname'=>$firstname,':lastname'=>$lastname,
		':gender'=>$gender,':birthday'=>$birthday,':date_climbingstart'=>
		$date_climbingstart,':main_gym'=>$gymid,':userid'=>$userid));
		
	$stmt2=$db->prepare("UPDATE users SET email=:email WHERE userid=:userid");
	$stmt2->execute(array(':email'=>$email,':userid'=>$userid));
}


//extract their user data
$stmt3 = $db->prepare("SELECT * FROM users,userdata WHERE users.userid = :userid
	and userdata.userid = :userid2");
$stmt3->execute(array(':userid'=>$userid,':userid2'=>$userid));
$userdataarray = $stmt3->fetch(PDO::FETCH_ASSOC);

//extract gym name
$stmt5 = $db->prepare("SELECT gym_name FROM gyms WHERE gymid = :gymid");
$stmt5->execute(array(':gymid'=>$userdataarray['main_gym']));
$main_gym_result = $stmt5->fetch(PDO::FETCH_ASSOC);
$main_gym_name = $main_gym_result['gym_name'];

$path = "userimages/";
$stmt4 = $db->prepare("SELECT userimage FROM userdata WHERE userid = :userid");
$stmt4->execute(array(':userid'=>$userid));
$actual_image_name_result = $stmt4->fetch(PDO::FETCH_ASSOC);

$actual_image_name = $actual_image_name_result['userimage'];

//check if there's already a user-uploaded image in the database
if (is_null($actual_image_name)) {
	//display default image otherwise
	$image = "<img src = \"images/default_user.png\" alt=\"\" style=\"display:block\">";
} else {
	$image = "<img src='".$path.$actual_image_name."' id=\"photo\"";
}

$alert = "";
$valid_formats = array("jpg","png","gif","bmp");
if (isset($_POST['upload'])) {
	$name= $_FILES['photoimg']['name'];
	$size = $_FILES['photoimg']['size'];
	if (strlen($name)) {
		list($txt,$ext) = explode(".",$name);
		//return the image filename and extension separately
		
		if (in_array($ext,$valid_formats) && $size<(250*1024)) {
			$max_length = 5;
			if (strlen($txt)<5) {
				$max_length = strlen($txt);
			}
			$actual_image_name = time().substr($txt,0,$max_length).".".$ext;
			//create a unique name for the image
			
			$tmp = $_FILES['photoimg']['tmp_name'];
			if (move_uploaded_file($tmp,$path.$actual_image_name)) {
				//if successfully moved image to path on server
				mysql_query("UPDATE userdata SET userimage='$actual_image_name'
				WHERE userid='$userid'");
				$image = "<img src='".$path.$actual_image_name."' id=\"photo\"";
			}
			else {
				$alert = '<div class="alert alert-danger">File upload unsuccessful.</div>';
			}
		}
		else {
			$alert = '<div class="alert alert-danger">Invalid file format or size. Image files should be <250 KB with the following formats: JPG, PNG, GIF or BMP.</div>';
		}
	}
	else {
		$alert = '<div class="alert alert-warning">Please select an image file and then click Upload.</div>';
	}
}
			

//build up gym option table
$stmt2 = $db->prepare("SELECT gymid, gym_name, city, state FROM gyms
	ORDER BY state");

//find user's main gym
$stmt3 = $db->prepare("SELECT main_gym FROM userdata WHERE userid = :userid");
$stmt3->execute(array(':userid'=>$userid));
$maingym = $stmt3->fetch(PDO::FETCH_ASSOC);
$maingym_id = $maingym['main_gym'];


//separate gyms by state
$old_state = "";
if ($stmt2->execute()) {
	while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		//if the user has a main gym, then make that the selected option, 
		//otherwise use placeholder text "Select your Gym..."
		if ((int)$row['gymid']==(int)$maingym_id) {
			$selected = "selected";
		}
		else {
			$selected = "";
		}
		$new_state = $row['state'];
		if ($old_state == $new_state) {
			//same state, do nothing
		}
		else if ($old_state != $new_state && $old_state != ""){
			if ($old_state != "") {
				$gym_options .= "</optgroup>";
				
			}
			$gym_options .= "<optgroup label = '".$new_state."'>";		
		}
		else if ($old_state == "") {
			$gym_options .= "<optgroup label = '".$new_state."'>";
		}
		$old_state = $new_state;
		$gym_options .= "<option value='".$row['gymid']."' ".$selected.">".$row['gym_name']."</option>";
	}
}
$gym_options .= "</optgroup>";
	
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
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>

	</head>
	
	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div class="wrap">
			<div class="main">
				<div class="container-fluid">
					<?php echo $alert; ?>
				</div>
				<div class="container-fluid">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="col-sm-12">
								<div id = "userimage">
									<table id="userimage-table">
										<tr><td><?php echo $image ?></td></tr>
										<tr align="left">
											<div id="thumbs"></div>
											<!--Note: default action for form is to submit to the same page-->
											<form method="post" enctype="multipart/form-data">
												<td align="left">
												<input type="file" name="photoimg"
												id = "photoimg" /></td></tr>
												<tr><td align="left">
												<input type="submit" name="upload" value="Upload"/>
												</td>
											</tr>
										</form>
								
									</table>	
									<p>Choose an image (<250 KB) and click Upload. Valid formats include JPG, PNG, GIF, or BMP.</p>
								</div>
							</div>
						</div>	
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="col-sm-12">
				
								<div class="panel panel-default" id = "basicinfopanel">
									<div class="panel-heading">Basic Information <a href="#edit" data-toggle="modal" id="editline"><u>edit</u></a></div>
									<div class = "panel-body">
									<table id = "basicinfotable">
										<tr><td><h4>First Name: </h4></td>
										<td><?php echo $userdataarray['firstname']; ?></td></tr>
										
										<tr><td><h4>Last Name: </h4></td>
										<td><?php echo $userdataarray['lastname']; ?></td></tr>
										
										<tr><td><h4>Email: </h4></td>
										<td><?php echo $userdataarray['email']; ?></td></tr>
										
										<tr><td><h4>Gender: </h4></td>
										<td><?php echo $userdataarray['gender']; ?></td></tr>
										
										<tr><td><h4>Birthday (Y/M/D): </h4></td>
										<td><?php echo $userdataarray['birthday']; ?></td></tr>
										
										<tr><td><h4>Main Gym: </h4></td>
										<td><?php echo $main_gym_name; ?></td></tr>
										
										<tr><td><h4>Date Started Climbing (Y/M/D): </h4></td>
										<td><?php echo $userdataarray['date_climbingstart']; ?></td></tr>
									</table>
										
										
									
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</body>
	
	<div class="modal fade" id="edit" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4>Edit User Information</h4>
				</div>
				<form class="userinfo" method="post">
				<div class="modal-body">
					<table id="table-userinfoedit">
						
						<tr><td>First Name: </td>
							<td><input class="input form-control" value="<?php echo $userdataarray['firstname']; ?>" type="text" name="firstname"></td>
						</tr> 
						<tr><td>Last Name: </td>
							<td><input class="input form-control" value="<?php echo $userdataarray['lastname']; ?>" type="text" name="lastname"></td>
						</tr>
						<tr>
							<td>Email: </td>
							<td><input class="input form-control" value="<?php echo $userdataarray['email']; ?>" type="text" name="email"></td>
						</tr>
						<tr>
							<td>Gender: </td>
							<td><select name='gender' class="form-control">
								<option value="Male" <?php echo $userdataarray['gender']=='Male'?'selected':''; ?>>Male</option>
								<option value="Female" <?php echo $userdataarray['gender']=='Female'?'selected':''; ?>>Female</option>
								<option value="Other" <?php echo $userdataarray['gender']=='Other'?'selected':''; ?>>Other</option>
							</select></td>
						</tr>
						<tr>
							<td>Main Gym: </td>
							<td><select id="select-gym" class="form-control" name="gymid">
									<option value="">Select a Gym...</option>
									<?php echo $gym_options; ?>
									
								</select>
							</td>
						</tr>

						<tr>
							<td>Birthday: </td>
							<td><input class="input form-control" value="<?php echo $userdataarray['birthday']; ?>" type="date" name="birthday"></td>
						</tr>
						<tr>
							<td>Date Started Climbing: </td>
							<td><input class="input form-control" value="<?php echo $userdataarray['date_climbingstart']; ?>" type="date" name="date_climbingstart"></td>
						</tr>


					</table>
				</div>

				<div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Cancel</a>
					<button type="submit" class="btn btn-success" name="infosubmit">Save</button>

				</div>
				</form>
			</div>
		</div>
	</div>
</html>
