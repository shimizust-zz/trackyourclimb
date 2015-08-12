<?php
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';				
			
$workout_id_prev = $_GET['wid'];
$showChangesSaved = 0; 

//check if edits have been submitted
if (isset($_POST['workoutsubmit'])) {
	//save workout as a new workout (even though it was edited)
	include 'saveworkout.php';
	
	//delete the previous workout
	$stmt5 = $db->prepare("DELETE FROM workouts WHERE workout_id = :workoutid");
	$stmt5->execute(array(':workoutid'=>$workout_id_prev));
	
	//Now, change the previous workout id to the new workout id (from 'saveworkout.php')
	$workout_id_prev = $workoutid;
	
	//display a message that changes have been saved.
	$showChangesSaved = 1;
	
	//update records table
	include 'update-records-absolute.php';
}


//Check if the userid corresponding to $workout_id_prev matches
$stmt = $db->prepare("SELECT userid FROM workouts WHERE workout_id=:workoutid");
$stmt->execute(array(':workoutid'=>$workout_id_prev));
$userid_wid = $stmt->fetch();
$userid_wid = $userid_wid['userid'];

if ($userid != $userid_wid) {
	//user of the workout_id_prev does not match the userid of the cookies
	header('Location: past-workouts.php');
}
//if it matches, continue rendering the edit workout page


			
//extract their user preferences
$stmt = $db->prepare("SELECT * FROM userprefs WHERE userid 
	= '$userid'");
$stmt->execute();
$userprefs = $stmt->fetch(PDO::FETCH_ASSOC);

$show_boulder = $userprefs['show_boulder'];
$show_TR = $userprefs['show_TR'];
$show_Lead = $userprefs['show_Lead'];

$show_project = $userprefs['show_project'];
$show_redpoint = $userprefs['show_redpoint'];
$show_flash = $userprefs['show_flash'];
$show_onsight = $userprefs['show_onsight'];

$minB = $userprefs['minV'];
$maxB = $userprefs['maxV'];
$minTR = $userprefs['minTR'];
$maxTR = $userprefs['maxTR'];
$minL = $userprefs['minL'];
$maxL = $userprefs['maxL'];

//extract grading system used for this workout (use userprefs)
$boulderGradingID = $userprefs['boulderGradingSystemID'];
$routeGradingID = $userprefs['routeGradingSystemID'];

//build up gym option table
$stmt2 = $db->prepare("SELECT gymid, gym_name, city, state FROM gyms
	ORDER BY state");

//find the gym used in the workout
$stmt3 = $db->prepare("SELECT * FROM workouts WHERE workout_id = :workoutid");
$stmt3->execute(array(':workoutid'=>$workout_id_prev));
$workout_info = $stmt3->fetch(PDO::FETCH_ASSOC);
$main_gymid = $workout_info['gymid'];


//find countryCode associated with gymid
$stmt4 = $db->prepare("SELECT countryCode FROM gyms WHERE gymid=:gymid");
$stmt4->execute(array(':gymid'=>$main_gymid));
$countryCodeResult = $stmt4->fetch(PDO::FETCH_ASSOC);
$countryCode = $countryCodeResult['countryCode'];


//find date of workout
$date_workout = $workout_info['date_workout'];
list($year,$month,$day) = explode('-',date('Y-m-d',strtotime($date_workout)));
$month = $month-1; //months start at 0 = January

//separate gyms by state
$gymOptions = '<option value="">Select a Gym...</option>';
include 'genGymOptions.php';


//extract workout segments corresponding to the workout_id
$stmt4 = $db->prepare("SELECT climb_type,ascent_type,grade_index,reps FROM workout_segments WHERE workout_id = :workoutid");
$stmt4->execute(array(':workoutid'=>$workout_id_prev));
$workoutsegments = $stmt4->fetchAll(PDO::FETCH_ASSOC);

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
		<link rel="stylesheet" type="text/css" href="css/datepicker.css">
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		
		<script src="js/bootstrap.js"></script>
		<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
		<script src="js/bootstrap-datepicker.js"></script>
		<script>
		$(document).ready(function() {
				$('.datepicker').datepicker({
				todayBtn : "linked",
				todayHighlight : true,	
				autoclose : true,
				format: "yyyy-mm-dd"
			});
			$('.datepicker').datepicker('update',new Date(<?php echo $year.','.$month.','.$day; ?>));
			});
		</script>
		<script>
			//define variables that *InputTable.js scripts will use
			var show_project = <?php echo $show_project ?>;
			var show_redpoint = <?php echo $show_redpoint ?>;
			var show_flash = <?php echo $show_flash ?>;
			var show_onsight = <?php echo $show_onsight ?>;
			
			var hideProject = show_project ? '' : ' style="display:none"';
			var hideRedpoint = show_redpoint ? '' : ' style="display:none"';
			var hideFlash = show_flash ? '' : ' style="display:none"';
			var hideOnsight = show_onsight ? '' : ' style="display:none"';
			
			var minB = <?php echo $minB ?>;
			var maxB = <?php echo $maxB ?>;
			var minTR = <?php echo $minTR ?>;
			var maxTR = <?php echo $maxTR ?>;
			var minL = <?php echo $minL ?>;
			var maxL = <?php echo $maxL ?>;
			
			var boulderGradingID = <?php echo $boulderGradingID ?>;
			var routeGradingID = <?php echo $routeGradingID ?>;
		</script>
		<script src="js/BoulderRouteGradingSystems.js"></script>
		<script src="js/workoutedit-script.js"></script>
		<script src="js/inputTableShared.js"></script>
		<script src="js/workoutInputTable.js"></script>
		<script src="js/sitePath.js"></script>
		<script src="js/populateCountryFromSelectCountry.js"></script>
		<script>
			var workoutsegments = <?php echo json_encode($workoutsegments); ?>;
			var workoutinfo = <?php echo json_encode($workout_info); ?>;
		</script>
		<script src="js/uservoice.js"></script>
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		<script src="selectize/dist/js/standalone/selectize.js"></script>
		<script src ="js/validation/dateCheck.js"></script>
		
		<link rel="stylesheet" type="text/css" href="selectize/dist/css/selectize.bootstrap3.css">
		
	</head>

	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require("navigation.php"); ?>


		<div class="wrapper">
		<?php
		if ($showChangesSaved==1) {
		?>
		<div class="container">
			<div class="alert alert-info">
				Changes have been successfully applied!
			</div>
		</div>
		<?php } ?>
		<div class="container">
		<div class="page-header">
			<h1>Edit Workout</h1>
		</div>
		</div>
		<form action="workout-edit.php?wid=<?php echo $workout_id_prev; ?>" method="post" id="workout-form" onsubmit="return dateCheck()">
		<!--Gym Picker-->
		<div id = "gym-date-pickers">
			<div id = "gym-picker">
				<label for="country-select">Select a Country:</label>
				<select name="country-select" class="form-control" id="country-select" onchange="updateCountryGyms()" required>
					<option value="">Please select a country</option>
					<option value="--" <?php echo $countryCode=="--"?'selected':'';?>>none</option>
					<option value="AF" <?php echo $countryCode=="AF"?'selected':'';?>>Afghanistan</option>
					<option value="AL" <?php echo $countryCode=="AL"?'selected':'';?>>Albania</option>
					<option value="DZ" <?php echo $countryCode=="DZ"?'selected':'';?>>Algeria</option>
					<option value="AS" <?php echo $countryCode=="AS"?'selected':'';?>>American Samoa</option>
					<option value="AD" <?php echo $countryCode=="AD"?'selected':'';?>>Andorra</option>
					<option value="AO" <?php echo $countryCode=="AO"?'selected':'';?>>Angola</option>
					<option value="AI" <?php echo $countryCode=="AI"?'selected':'';?>>Anguilla</option>
					<option value="AQ" <?php echo $countryCode=="AQ"?'selected':'';?>>Antarctica</option>
					<option value="AG" <?php echo $countryCode=="AG"?'selected':'';?>>Antigua and Barbuda</option>
					<option value="AR" <?php echo $countryCode=="AR"?'selected':'';?>>Argentina</option>
					<option value="AM" <?php echo $countryCode=="AM"?'selected':'';?>>Armenia</option>
					<option value="AW" <?php echo $countryCode=="AW"?'selected':'';?>>Aruba</option>
					<option value="AU" <?php echo $countryCode=="AU"?'selected':'';?>>Australia</option>
					<option value="AT" <?php echo $countryCode=="AT"?'selected':'';?>>Austria</option>
					<option value="AZ" <?php echo $countryCode=="AZ"?'selected':'';?>>Azerbaijan</option>
					<option value="BS" <?php echo $countryCode=="BS"?'selected':'';?>>Bahamas</option>
					<option value="BH" <?php echo $countryCode=="BH"?'selected':'';?>>Bahrain</option>
					<option value="BD" <?php echo $countryCode=="BD"?'selected':'';?>>Bangladesh</option>
					<option value="BB" <?php echo $countryCode=="BB"?'selected':'';?>>Barbados</option>
					<option value="BY" <?php echo $countryCode=="BY"?'selected':'';?>>Belarus</option>
					<option value="BE" <?php echo $countryCode=="BE"?'selected':'';?>>Belgium</option>
					<option value="BZ" <?php echo $countryCode=="BZ"?'selected':'';?>>Belize</option>
					<option value="BJ" <?php echo $countryCode=="BJ"?'selected':'';?>>Benin</option>
					<option value="BM" <?php echo $countryCode=="BM"?'selected':'';?>>Bermuda</option>
					<option value="BT" <?php echo $countryCode=="BT"?'selected':'';?>>Bhutan</option>
					<option value="BO" <?php echo $countryCode=="BO"?'selected':'';?>>Bolivia</option>
					<option value="BA" <?php echo $countryCode=="BA"?'selected':'';?>>Bosnia and Herzegowina</option>
					<option value="BW" <?php echo $countryCode=="BW"?'selected':'';?>>Botswana</option>
					<option value="BV" <?php echo $countryCode=="BV"?'selected':'';?>>Bouvet Island</option>
					<option value="BR" <?php echo $countryCode=="BR"?'selected':'';?>>Brazil</option>
					<option value="IO" <?php echo $countryCode=="IO"?'selected':'';?>>British Indian Ocean Territory</option>
					<option value="BN" <?php echo $countryCode=="BN"?'selected':'';?>>Brunei Darussalam</option>
					<option value="BG" <?php echo $countryCode=="BG"?'selected':'';?>>Bulgaria</option>
					<option value="BF" <?php echo $countryCode=="BF"?'selected':'';?>>Burkina Faso</option>
					<option value="BI" <?php echo $countryCode=="BI"?'selected':'';?>>Burundi</option>
					<option value="KH" <?php echo $countryCode=="KH"?'selected':'';?>>Cambodia</option>
					<option value="CM" <?php echo $countryCode=="CM"?'selected':'';?>>Cameroon</option>
					<option value="CA" <?php echo $countryCode=="CA"?'selected':'';?>>Canada</option>
					<option value="CV" <?php echo $countryCode=="CV"?'selected':'';?>>Cape Verde</option>
					<option value="KY" <?php echo $countryCode=="KY"?'selected':'';?>>Cayman Islands</option>
					<option value="CF" <?php echo $countryCode=="CF"?'selected':'';?>>Central African Republic</option>
					<option value="TD" <?php echo $countryCode=="TD"?'selected':'';?>>Chad</option>
					<option value="CL" <?php echo $countryCode=="CL"?'selected':'';?>>Chile</option>
					<option value="CN" <?php echo $countryCode=="CN"?'selected':'';?>>China</option>
					<option value="CX" <?php echo $countryCode=="CX"?'selected':'';?>>Christmas Island</option>
					<option value="CC" <?php echo $countryCode=="CC"?'selected':'';?>>Cocos (Keeling) Islands</option>
					<option value="CO" <?php echo $countryCode=="CO"?'selected':'';?>>Colombia</option>
					<option value="KM" <?php echo $countryCode=="KM"?'selected':'';?>>Comoros</option>
					<option value="CG" <?php echo $countryCode=="CG"?'selected':'';?>>Congo</option>
					<option value="CD" <?php echo $countryCode=="CD"?'selected':'';?>>Congo, the Democratic Republic of the</option>
					<option value="CK" <?php echo $countryCode=="CK"?'selected':'';?>>Cook Islands</option>
					<option value="CR" <?php echo $countryCode=="CR"?'selected':'';?>>Costa Rica</option>
					<option value="CI" <?php echo $countryCode=="CI"?'selected':'';?>>Cote d'Ivoire</option>
					<option value="HR" <?php echo $countryCode=="HR"?'selected':'';?>>Croatia (Hrvatska)</option>
					<option value="CU" <?php echo $countryCode=="CU"?'selected':'';?>>Cuba</option>
					<option value="CY" <?php echo $countryCode=="CY"?'selected':'';?>>Cyprus</option>
					<option value="CZ" <?php echo $countryCode=="CZ"?'selected':'';?>>Czech Republic</option>
					<option value="DK" <?php echo $countryCode=="DK"?'selected':'';?>>Denmark</option>
					<option value="DJ" <?php echo $countryCode=="DJ"?'selected':'';?>>Djibouti</option>
					<option value="DM" <?php echo $countryCode=="DM"?'selected':'';?>>Dominica</option>
					<option value="DO" <?php echo $countryCode=="DO"?'selected':'';?>>Dominican Republic</option>
					<option value="TP" <?php echo $countryCode=="TP"?'selected':'';?>>East Timor</option>
					<option value="EC" <?php echo $countryCode=="EC"?'selected':'';?>>Ecuador</option>
					<option value="EG" <?php echo $countryCode=="EG"?'selected':'';?>>Egypt</option>
					<option value="SV" <?php echo $countryCode=="SV"?'selected':'';?>>El Salvador</option>
					<option value="GQ" <?php echo $countryCode=="GQ"?'selected':'';?>>Equatorial Guinea</option>
					<option value="ER" <?php echo $countryCode=="ER"?'selected':'';?>>Eritrea</option>
					<option value="EE" <?php echo $countryCode=="EE"?'selected':'';?>>Estonia</option>
					<option value="ET" <?php echo $countryCode=="ET"?'selected':'';?>>Ethiopia</option>
					<option value="FK" <?php echo $countryCode=="FK"?'selected':'';?>>Falkland Islands (Malvinas)</option>
					<option value="FO" <?php echo $countryCode=="FO"?'selected':'';?>>Faroe Islands</option>
					<option value="FJ" <?php echo $countryCode=="FJ"?'selected':'';?>>Fiji</option>
					<option value="FI" <?php echo $countryCode=="FI"?'selected':'';?>>Finland</option>
					<option value="FR" <?php echo $countryCode=="FR"?'selected':'';?>>France</option>
					<option value="FX" <?php echo $countryCode=="FX"?'selected':'';?>>France, Metropolitan</option>
					<option value="GF" <?php echo $countryCode=="GF"?'selected':'';?>>French Guiana</option>
					<option value="PF" <?php echo $countryCode=="PF"?'selected':'';?>>French Polynesia</option>
					<option value="TF" <?php echo $countryCode=="TF"?'selected':'';?>>French Southern Territories</option>
					<option value="GA" <?php echo $countryCode=="GA"?'selected':'';?>>Gabon</option>
					<option value="GM" <?php echo $countryCode=="GM"?'selected':'';?>>Gambia</option>
					<option value="GE" <?php echo $countryCode=="GE"?'selected':'';?>>Georgia</option>
					<option value="DE" <?php echo $countryCode=="DE"?'selected':'';?>>Germany</option>
					<option value="GH" <?php echo $countryCode=="GH"?'selected':'';?>>Ghana</option>
					<option value="GI" <?php echo $countryCode=="GI"?'selected':'';?>>Gibraltar</option>
					<option value="GR" <?php echo $countryCode=="GR"?'selected':'';?>>Greece</option>
					<option value="GL" <?php echo $countryCode=="GL"?'selected':'';?>>Greenland</option>
					<option value="GD" <?php echo $countryCode=="GD"?'selected':'';?>>Grenada</option>
					<option value="GP" <?php echo $countryCode=="GP"?'selected':'';?>>Guadeloupe</option>
					<option value="GU" <?php echo $countryCode=="GU"?'selected':'';?>>Guam</option>
					<option value="GT" <?php echo $countryCode=="GT"?'selected':'';?>>Guatemala</option>
					<option value="GN" <?php echo $countryCode=="GN"?'selected':'';?>>Guinea</option>
					<option value="GW" <?php echo $countryCode=="GW"?'selected':'';?>>Guinea-Bissau</option>
					<option value="GY" <?php echo $countryCode=="GY"?'selected':'';?>>Guyana</option>
					<option value="HT" <?php echo $countryCode=="HT"?'selected':'';?>>Haiti</option>
					<option value="HM" <?php echo $countryCode=="HM"?'selected':'';?>>Heard and Mc Donald Islands</option>
					<option value="VA" <?php echo $countryCode=="VA"?'selected':'';?>>Holy See (Vatican City State)</option>
					<option value="HN" <?php echo $countryCode=="HN"?'selected':'';?>>Honduras</option>
					<option value="HK" <?php echo $countryCode=="HK"?'selected':'';?>>Hong Kong</option>
					<option value="HU" <?php echo $countryCode=="HU"?'selected':'';?>>Hungary</option>
					<option value="IS" <?php echo $countryCode=="IS"?'selected':'';?>>Iceland</option>
					<option value="IN" <?php echo $countryCode=="IN"?'selected':'';?>>India</option>
					<option value="ID" <?php echo $countryCode=="ID"?'selected':'';?>>Indonesia</option>
					<option value="IR" <?php echo $countryCode=="IR"?'selected':'';?>>Iran (Islamic Republic of)</option>
					<option value="IQ" <?php echo $countryCode=="IQ"?'selected':'';?>>Iraq</option>
					<option value="IE" <?php echo $countryCode=="IE"?'selected':'';?>>Ireland</option>
					<option value="IL" <?php echo $countryCode=="IL"?'selected':'';?>>Israel</option>
					<option value="IT" <?php echo $countryCode=="IT"?'selected':'';?>>Italy</option>
					<option value="JM" <?php echo $countryCode=="JM"?'selected':'';?>>Jamaica</option>
					<option value="JP" <?php echo $countryCode=="JP"?'selected':'';?>>Japan</option>
					<option value="JO" <?php echo $countryCode=="JO"?'selected':'';?>>Jordan</option>
					<option value="KZ" <?php echo $countryCode=="KZ"?'selected':'';?>>Kazakhstan</option>
					<option value="KE" <?php echo $countryCode=="KE"?'selected':'';?>>Kenya</option>
					<option value="KI" <?php echo $countryCode=="KI"?'selected':'';?>>Kiribati</option>
					<option value="KP" <?php echo $countryCode=="KP"?'selected':'';?>>Korea, Democratic People's Republic of</option>
					<option value="KR" <?php echo $countryCode=="KR"?'selected':'';?>>Korea, Republic of</option>
					<option value="KW" <?php echo $countryCode=="KW"?'selected':'';?>>Kuwait</option>
					<option value="KG" <?php echo $countryCode=="KG"?'selected':'';?>>Kyrgyzstan</option>
					<option value="LA" <?php echo $countryCode=="LA"?'selected':'';?>>Lao People's Democratic Republic</option>
					<option value="LV" <?php echo $countryCode=="LV"?'selected':'';?>>Latvia</option>
					<option value="LB" <?php echo $countryCode=="LB"?'selected':'';?>>Lebanon</option>
					<option value="LS" <?php echo $countryCode=="LS"?'selected':'';?>>Lesotho</option>
					<option value="LR" <?php echo $countryCode=="LR"?'selected':'';?>>Liberia</option>
					<option value="LY" <?php echo $countryCode=="LY"?'selected':'';?>>Libyan Arab Jamahiriya</option>
					<option value="LI" <?php echo $countryCode=="LI"?'selected':'';?>>Liechtenstein</option>
					<option value="LT" <?php echo $countryCode=="LT"?'selected':'';?>>Lithuania</option>
					<option value="LU" <?php echo $countryCode=="LU"?'selected':'';?>>Luxembourg</option>
					<option value="MO" <?php echo $countryCode=="MO"?'selected':'';?>>Macau</option>
					<option value="MK" <?php echo $countryCode=="MK"?'selected':'';?>>Macedonia, The Former Yugoslav Republic of</option>
					<option value="MG" <?php echo $countryCode=="MG"?'selected':'';?>>Madagascar</option>
					<option value="MW" <?php echo $countryCode=="MW"?'selected':'';?>>Malawi</option>
					<option value="MY" <?php echo $countryCode=="MY"?'selected':'';?>>Malaysia</option>
					<option value="MV" <?php echo $countryCode=="MV"?'selected':'';?>>Maldives</option>
					<option value="ML" <?php echo $countryCode=="ML"?'selected':'';?>>Mali</option>
					<option value="MT" <?php echo $countryCode=="MT"?'selected':'';?>>Malta</option>
					<option value="MH" <?php echo $countryCode=="MH"?'selected':'';?>>Marshall Islands</option>
					<option value="MQ" <?php echo $countryCode=="MQ"?'selected':'';?>>Martinique</option>
					<option value="MR" <?php echo $countryCode=="MR"?'selected':'';?>>Mauritania</option>
					<option value="MU" <?php echo $countryCode=="MU"?'selected':'';?>>Mauritius</option>
					<option value="YT" <?php echo $countryCode=="YT"?'selected':'';?>>Mayotte</option>
					<option value="MX" <?php echo $countryCode=="MX"?'selected':'';?>>Mexico</option>
					<option value="FM" <?php echo $countryCode=="FM"?'selected':'';?>>Micronesia, Federated States of</option>
					<option value="MD" <?php echo $countryCode=="MD"?'selected':'';?>>Moldova, Republic of</option>
					<option value="MC" <?php echo $countryCode=="MC"?'selected':'';?>>Monaco</option>
					<option value="MN" <?php echo $countryCode=="MN"?'selected':'';?>>Mongolia</option>
					<option value="MS" <?php echo $countryCode=="MS"?'selected':'';?>>Montserrat</option>
					<option value="MA" <?php echo $countryCode=="MA"?'selected':'';?>>Morocco</option>
					<option value="MZ" <?php echo $countryCode=="MZ"?'selected':'';?>>Mozambique</option>
					<option value="MM" <?php echo $countryCode=="MM"?'selected':'';?>>Myanmar</option>
					<option value="NA" <?php echo $countryCode=="NA"?'selected':'';?>>Namibia</option>
					<option value="NR" <?php echo $countryCode=="NR"?'selected':'';?>>Nauru</option>
					<option value="NP" <?php echo $countryCode=="NP"?'selected':'';?>>Nepal</option>
					<option value="NL" <?php echo $countryCode=="NL"?'selected':'';?>>Netherlands</option>
					<option value="AN" <?php echo $countryCode=="AN"?'selected':'';?>>Netherlands Antilles</option>
					<option value="NC" <?php echo $countryCode=="NC"?'selected':'';?>>New Caledonia</option>
					<option value="NZ" <?php echo $countryCode=="NZ"?'selected':'';?>>New Zealand</option>
					<option value="NI" <?php echo $countryCode=="NI"?'selected':'';?>>Nicaragua</option>
					<option value="NE" <?php echo $countryCode=="NE"?'selected':'';?>>Niger</option>
					<option value="NG" <?php echo $countryCode=="NG"?'selected':'';?>>Nigeria</option>
					<option value="NU" <?php echo $countryCode=="NU"?'selected':'';?>>Niue</option>
					<option value="NF" <?php echo $countryCode=="NF"?'selected':'';?>>Norfolk Island</option>
					<option value="MP" <?php echo $countryCode=="MP"?'selected':'';?>>Northern Mariana Islands</option>
					<option value="NO" <?php echo $countryCode=="NO"?'selected':'';?>>Norway</option>
					<option value="OM" <?php echo $countryCode=="OM"?'selected':'';?>>Oman</option>
					<option value="PK" <?php echo $countryCode=="PK"?'selected':'';?>>Pakistan</option>
					<option value="PW" <?php echo $countryCode=="PW"?'selected':'';?>>Palau</option>
					<option value="PA" <?php echo $countryCode=="PA"?'selected':'';?>>Panama</option>
					<option value="PG" <?php echo $countryCode=="PG"?'selected':'';?>>Papua New Guinea</option>
					<option value="PY" <?php echo $countryCode=="PY"?'selected':'';?>>Paraguay</option>
					<option value="PE" <?php echo $countryCode=="PE"?'selected':'';?>>Peru</option>
					<option value="PH" <?php echo $countryCode=="PH"?'selected':'';?>>Philippines</option>
					<option value="PN" <?php echo $countryCode=="PN"?'selected':'';?>>Pitcairn</option>
					<option value="PL" <?php echo $countryCode=="PL"?'selected':'';?>>Poland</option>
					<option value="PT" <?php echo $countryCode=="PT"?'selected':'';?>>Portugal</option>
					<option value="PR" <?php echo $countryCode=="PR"?'selected':'';?>>Puerto Rico</option>
					<option value="QA" <?php echo $countryCode=="QA"?'selected':'';?>>Qatar</option>
					<option value="RE" <?php echo $countryCode=="RE"?'selected':'';?>>Reunion</option>
					<option value="RO" <?php echo $countryCode=="RO"?'selected':'';?>>Romania</option>
					<option value="RU" <?php echo $countryCode=="RU"?'selected':'';?>>Russian Federation</option>
					<option value="RW" <?php echo $countryCode=="RW"?'selected':'';?>>Rwanda</option>
					<option value="KN" <?php echo $countryCode=="KN"?'selected':'';?>>Saint Kitts and Nevis</option> 
					<option value="LC" <?php echo $countryCode=="LC"?'selected':'';?>>Saint LUCIA</option>
					<option value="VC" <?php echo $countryCode=="VC"?'selected':'';?>>Saint Vincent and the Grenadines</option>
					<option value="WS" <?php echo $countryCode=="WS"?'selected':'';?>>Samoa</option>
					<option value="SM" <?php echo $countryCode=="SM"?'selected':'';?>>San Marino</option>
					<option value="ST" <?php echo $countryCode=="ST"?'selected':'';?>>Sao Tome and Principe</option> 
					<option value="SA" <?php echo $countryCode=="SA"?'selected':'';?>>Saudi Arabia</option>
					<option value="SN" <?php echo $countryCode=="SN"?'selected':'';?>>Senegal</option>
					<option value="SC" <?php echo $countryCode=="SC"?'selected':'';?>>Seychelles</option>
					<option value="SL" <?php echo $countryCode=="SL"?'selected':'';?>>Sierra Leone</option>
					<option value="SG" <?php echo $countryCode=="SG"?'selected':'';?>>Singapore</option>
					<option value="SK" <?php echo $countryCode=="SK"?'selected':'';?>>Slovakia (Slovak Republic)</option>
					<option value="SI" <?php echo $countryCode=="SI"?'selected':'';?>>Slovenia</option>
					<option value="SB" <?php echo $countryCode=="SB"?'selected':'';?>>Solomon Islands</option>
					<option value="SO" <?php echo $countryCode=="SO"?'selected':'';?>>Somalia</option>
					<option value="ZA" <?php echo $countryCode=="ZA"?'selected':'';?>>South Africa</option>
					<option value="GS" <?php echo $countryCode=="GS"?'selected':'';?>>South Georgia and the South Sandwich Islands</option>
					<option value="ES" <?php echo $countryCode=="ES"?'selected':'';?>>Spain</option>
					<option value="LK" <?php echo $countryCode=="LK"?'selected':'';?>>Sri Lanka</option>
					<option value="SH" <?php echo $countryCode=="SH"?'selected':'';?>>St. Helena</option>
					<option value="PM" <?php echo $countryCode=="PM"?'selected':'';?>>St. Pierre and Miquelon</option>
					<option value="SD" <?php echo $countryCode=="SD"?'selected':'';?>>Sudan</option>
					<option value="SR" <?php echo $countryCode=="SR"?'selected':'';?>>Suriname</option>
					<option value="SJ" <?php echo $countryCode=="SJ"?'selected':'';?>>Svalbard and Jan Mayen Islands</option>
					<option value="SZ" <?php echo $countryCode=="SZ"?'selected':'';?>>Swaziland</option>
					<option value="SE" <?php echo $countryCode=="SE"?'selected':'';?>>Sweden</option>
					<option value="CH" <?php echo $countryCode=="CH"?'selected':'';?>>Switzerland</option>
					<option value="SY" <?php echo $countryCode=="SY"?'selected':'';?>>Syrian Arab Republic</option>
					<option value="TW" <?php echo $countryCode=="TW"?'selected':'';?>>Taiwan, Province of China</option>
					<option value="TJ" <?php echo $countryCode=="TJ"?'selected':'';?>>Tajikistan</option>
					<option value="TZ" <?php echo $countryCode=="TZ"?'selected':'';?>>Tanzania, United Republic of</option>
					<option value="TH" <?php echo $countryCode=="TH"?'selected':'';?>>Thailand</option>
					<option value="TG" <?php echo $countryCode=="TG"?'selected':'';?>>Togo</option>
					<option value="TK" <?php echo $countryCode=="TK"?'selected':'';?>>Tokelau</option>
					<option value="TO" <?php echo $countryCode=="TO"?'selected':'';?>>Tonga</option>
					<option value="TT" <?php echo $countryCode=="TT"?'selected':'';?>>Trinidad and Tobago</option>
					<option value="TN" <?php echo $countryCode=="TN"?'selected':'';?>>Tunisia</option>
					<option value="TR" <?php echo $countryCode=="TR"?'selected':'';?>>Turkey</option>
					<option value="TM" <?php echo $countryCode=="TM"?'selected':'';?>>Turkmenistan</option>
					<option value="TC" <?php echo $countryCode=="TC"?'selected':'';?>>Turks and Caicos Islands</option>
					<option value="TV" <?php echo $countryCode=="TV"?'selected':'';?>>Tuvalu</option>
					<option value="UG" <?php echo $countryCode=="UG"?'selected':'';?>>Uganda</option>
					<option value="UA" <?php echo $countryCode=="UA"?'selected':'';?>>Ukraine</option>
					<option value="AE" <?php echo $countryCode=="AE"?'selected':'';?>>United Arab Emirates</option>
					<option value="GB" <?php echo $countryCode=="GB"?'selected':'';?>>United Kingdom</option>
					<option value="US" <?php echo $countryCode=="US"?'selected':'';?>>United States</option>
					<option value="UM" <?php echo $countryCode=="UM"?'selected':'';?>>United States Minor Outlying Islands</option>
					<option value="UY" <?php echo $countryCode=="UY"?'selected':'';?>>Uruguay</option>
					<option value="UZ" <?php echo $countryCode=="UZ"?'selected':'';?>>Uzbekistan</option>
					<option value="VU" <?php echo $countryCode=="VU"?'selected':'';?>>Vanuatu</option>
					<option value="VE" <?php echo $countryCode=="VE"?'selected':'';?>>Venezuela</option>
					<option value="VN" <?php echo $countryCode=="VN"?'selected':'';?>>Viet Nam</option>
					<option value="VG" <?php echo $countryCode=="VG"?'selected':'';?>>Virgin Islands (British)</option>
					<option value="VI" <?php echo $countryCode=="VI"?'selected':'';?>>Virgin Islands (U.S.)</option>
					<option value="WF" <?php echo $countryCode=="WF"?'selected':'';?>>Wallis and Futuna Islands</option>
					<option value="EH" <?php echo $countryCode=="EH"?'selected':'';?>>Western Sahara</option>
					<option value="YE" <?php echo $countryCode=="YE"?'selected':'';?>>Yemen</option>
					<option value="YU" <?php echo $countryCode=="YU"?'selected':'';?>>Yugoslavia</option>
					<option value="ZM" <?php echo $countryCode=="ZM"?'selected':'';?>>Zambia</option>
					<option value="ZW" <?php echo $countryCode=="ZW"?'selected':'';?>>Zimbabwe</option>
				</select>
				<br>
			
				<label for="gymid">Climbing Area: <a href="add-gym.php" id="add-gym-text">
					(Add a new climbing area)</a> </label>
				<div id="select-gym-div">
					<select id="select-gym" name="gymid" required>
						<option value="">Select a Gym...</option>
						<?php echo $gym_options; ?>
						
					</select>
				</div>
			</div>
			
			<script>
				$("#select-gym").selectize({
					create: false,
					sortField: {
						field: 'text',
						direction: 'asc'
					}
				});
			</script>
			
			<!--Date Picker-->	
			
			<div class="input-group date" id="workout-date">
				<label for="workoutdate">Workout Date: </label>
				<input type="text" name="workoutdate" class="form-control datepicker">
			</div>
			
			
			</div>
			
		<div id="log"></div>
		
		<div class="panel-group" id="accordion">
			<div class = "panel panel-default" 
			<?php echo $show_boulder ? '' : 'style="display:none'?>">
				<div class="panel-heading">
					<h4 class="panel-title"><a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne"> <span class="glyphicon glyphicon-chevron-down"></span> Bouldering </a></h4>
				</div>
				<div id="collapseOne" class="panel-collapse collapse in" id="boulderinput">
					<div class="panel-body">
						<script>  
							workoutInputTable('B',boulderRatings,boulderGradeMapAbsGradeInd,boulderGradingID,minB,maxB);
						</script>					
						<div>
							<p>Bouldering Notes: </p>
							<textarea name="boulderNotes" cols="50" rows="5"id="BoulderNotes"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class = "panel panel-default" 
				<?php echo $show_TR ? '' : 'style="display:none'?>">
				<div class="panel-heading">
					<h4 class="panel-title"><a data-toggle="collapse" data-target="#collapseTwo" href="#collapseTwo"> <span class="glyphicon glyphicon-chevron-down"></span> Top-Roping </a></h4>
				</div>
				<div id="collapseTwo" class="panel-collapse collapse <?php echo $show_boulder ? '' : 'in'?>">
					<div class="panel-body">
						<script>  
							workoutInputTable('TR',routeRatings,routeGradeMapAbsGradeInd,routeGradingID,minTR,maxTR);
						</script>
						
						<div>
							<p>Top-Roping Notes: </p>
							<textarea name="TRNotes" cols="50" rows="5" id = "TRNotes"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class = "panel panel-default"
				<?php echo $show_Lead ? '' : 'style="display:none'?>">
				<div class="panel-heading">
					<h4 class="panel-title"><a data-toggle="collapse" data-target="#collapseThree" href="#collapseThree"> <span class="glyphicon glyphicon-chevron-down"></span> Leading </a></h4>
				</div>
				<div id="collapseThree" class="panel-collapse collapse <?php echo (!$show_boulder)&&(!$show_TR) ? 'in' : ''?>">
					<div class="panel-body">
						<script>  
							workoutInputTable('L',routeRatings,routeGradeMapAbsGradeInd,routeGradingID,minL,maxL);
						</script>	
						
						<div>
							<p>Leading Notes: </p>
							<textarea name="LeadNotes" cols="50" rows="5" id = "LeadNotes"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class = "panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title"><a data-toggle="collapse" data-target="#collapseFour" href="#collapseFour"> <span class="glyphicon glyphicon-chevron-down"></span> Other (Hangboard, Campus board, etc.) </a></h4>
				</div>
				<div id="collapseFour" class="panel-collapse collapse">
					<div class="panel-body">
						<div>
							<p>Other Notes: </p>
							<textarea name="OtherNotes" cols="50" rows="6" id = "OtherNotes"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>

		<button type="submit" name="workoutsubmit" class="btn btn-default btn-success" id="btn-submit-workout">
			Save Edits To Workout
		</button>
		
		</form>
		
		<script src="js/workoutedit-populate.js"></script>
		
		<div id="push"></div>
		</div>
		

	</body>
</html>
