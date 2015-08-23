<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

include 'genDefaultRankingTable.php';


//Have 3 rankings tables for bouldering, top-roping and leading
//Within each table, user can choose time frame (Last week, Last 30 days, Last year, All time), ascent type, highest grade or most points		

//Call an update js function upon modifying dropdown menus, which will create an ajax request and call php to carry out the query
//Then have it update the rankings table	


//find user's main gym
$stmt3 = $db->prepare("SELECT main_gym FROM userdata WHERE userid = :userid");
$stmt3->execute(array(':userid'=>$userid));
$maingym = $stmt3->fetch(PDO::FETCH_ASSOC);
$maingym_id = $maingym['main_gym'];

//build up gym option table
$stmt2 = $db->prepare("SELECT gymid, gym_name, city, state FROM gyms
	ORDER BY state");
$old_state = "";
$gym_options = "<option value='-1' selected>All Gyms</option>";
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
		$selected = ""; //uncomment this to have the user's main gym selected
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
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/sitePath.js"></script>
		
		<script src="js/uservoice.js"></script>
		
		
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<?php include_once("php_common/analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<div class="container" id="rankings-container">
			<div class="page-header"><h1>Rankings</h1></div>
				
				<div><h3 id="ranking-header">Boulder Rankings</h3></div>
				<?php genRankingTable('boulder',$gym_options,$userid,'-1'); ?>
				
				
				<div><h3 id="ranking-header">Top-Rope Rankings</h3></div>
				<?php genRankingTable('TR',$gym_options,$userid,'-1'); ?>
				
				<div><h3 id="ranking-header">Lead Rankings</h3></div>
				<?php genRankingTable('lead',$gym_options,$userid,'-1'); ?>
					
		</div>
		<p id = "scroll-padding"></p>
		
		<script src="js/rankings/get_update_rankings.js"></script>
		<script>
			//run function from get_update_rankings.js
			getboulderRankingData('grade','boulder','week','all','-1',<?php echo $userid?>);
			getleadRankingData('grade','lead','week','all','-1',<?php echo $userid?>);
			getTRRankingData('grade','TR','week','all','-1',<?php echo $userid?>);
		</script>
</body>
</html>
