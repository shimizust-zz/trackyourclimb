<?php 
include './core/bootstrap.php';

//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	


$userService = new UserService();

if (isset($_POST['prefsubmit'])) {
	$userPrefs = array();
	
	$userPrefs = [(int)isset($_POST['showBoulder']), (int)isset($_POST['showTR']), (int)isset($_POST['showLead']),
			(int)isset($_POST['showProject']), (int)isset($_POST['showRedpoint']), (int)isset($_POST['showFlash']), (int)isset($_POST['showOnsight']),
			$_POST['minBoulderRange'], $_POST['maxBoulderRange'],
			$_POST['minTRRange'], $_POST['maxTRRange'],
			$_POST['minLeadRange'], $_POST['maxLeadRange'],
			$_POST['boulder-rating-select'], $_POST['route-rating-select']
	];
	$success = $userService->setUserPrefs($userid, $userPrefs);
	
	$countryCode = $_POST['country-select'];
	$userService->setUserCountryCode($userid, $countryCode);
	
	//once preferences are set, go to the workout input page
	if ($success) {
		header('Location: workout-input.php');
	} else {
		throw new Exception("Workout preferences could not be saved.");
	}
}


//Check current userprefs
$prefs = $userService->getUserPrefs($userid);
$boulderGradingID = $prefs['boulderGradingSystemID'];
$routeGradingID = $prefs['routeGradingSystemID'];

$showBoulder = $prefs['show_boulder'];
$showTR = $prefs['show_TR'];
$showLead = $prefs['show_Lead'];
$showProject = $prefs['show_project'];
$showRedpoint = $prefs['show_redpoint'];
$showFlash = $prefs['show_flash'];
$showOnsight = $prefs['show_onsight'];

$selectMinBoulder = $prefs['minV'];
$selectMaxBoulder = $prefs['maxV'];
$selectMinTR = $prefs['minTR'];
$selectMaxTR = $prefs['maxTR'];
$selectMinL = $prefs['minL'];
$selectMaxL = $prefs['maxL'];		
		
//Find out user's country
$countryCode = $userService->getUserCountryCode($userid);
	
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
		
		<script>
			var boulderGradingID = <?php echo $boulderGradingID ?>;
			var routeGradingID = <?php echo $routeGradingID ?>;
		
			var selectMinBoulder = <?php echo $selectMinBoulder ?>;
			var selectMaxBoulder = <?php echo $selectMaxBoulder ?>;
			var selectMinTR = <?php echo $selectMinTR ?>;
			var selectMaxTR = <?php echo $selectMaxTR ?>;
			var selectMinL = <?php echo $selectMinL ?>;
			var selectMaxL = <?php echo $selectMaxL ?>;
		</script>
		<script src="js/BoulderRouteGradingSystems.js"></script>
		<script src="js/preferences.js"></script>
		<script src="js/uservoice.js"></script>
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<?php include_once("php_common/analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		

		<div class="generaldiv panel panel-default">
			<div class = "panel-heading">
				<h4>Set Your Workout Preferences</h4>
			</div>
			
			<form method="post" action="#">
			<div class="panel-body">
				<p>What country are you from?</p>
				<select name="country-select" id="country-select" class="form-control" required>
					<option value="">(please select a country)</option>
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
				<br><br>
				
				<p>Choose your bouldering grading system:</p>
				<select name="boulder-rating-select" id="boulder-rating-select" class="form-control" required>
					<option value="0" <?php echo $boulderGradingID==0?'selected':'';?>>Hueco (V-Scale)</option>
					<option value="1" <?php echo $boulderGradingID==1?'selected':'';?>>Fontainebleau</option>
					<option value="2" <?php echo $boulderGradingID==2?'selected':'';?>>Brazilian</option>
				</select>
				<br><br>
				
				<p>Choose your top-rope and lead grading system:</p>
				<select name="route-rating-select" id="route-rating-select" class="form-control" required>
					<option value="0" <?php echo $routeGradingID==0?'selected':'';?>>YDS</option>
					<option value="1" <?php echo $routeGradingID==1?'selected':'';?>>French</option>
					<option value="2" <?php echo $routeGradingID==2?'selected':'';?>>British Tech</option>
					<option value="3" <?php echo $routeGradingID==3?'selected':'';?>>Ewbank (Australia)</option>
					<option value="4" <?php echo $routeGradingID==4?'selected':'';?>>Ewbank (South Africa)</option>
					<option value="5" <?php echo $routeGradingID==5?'selected':'';?>>UIAA</option>
					<option value="6" <?php echo $routeGradingID==6?'selected':'';?>>Saxon</option>
					<option value="7" <?php echo $routeGradingID==7?'selected':'';?>>Norwegian</option>
					<option value="8" <?php echo $routeGradingID==8?'selected':'';?>>Finnish</option>
					<option value="9" <?php echo $routeGradingID==9?'selected':'';?>>Brazilian</option>
				</select>
				<br><br>
			
			
				<p>What types of climbing do you do?</p>
				<input type="checkbox" name="showBoulder" value="boulder" <?php echo $showBoulder?'checked':'';?>>
				Bouldering &nbsp;&nbsp;      	
				<input type="checkbox" name="showTR" value="TR" <?php echo $showTR?'checked':'';?>>
				Top-Roping &nbsp;&nbsp;    	
				<input type="checkbox" name="showLead" value="Lead" <?php echo $showLead?'checked':'';?>>
				Leading<br><br>
				
				<p>What kinds of ascents do you want to keep track of?</p>
				<input type="checkbox" name="showProject" value="Project" <?php echo $showProject?'checked':'';?>>
				Attempt &nbsp;&nbsp;      	
				<input type="checkbox" name="showRedpoint" value="Redpoint" <?php echo $showRedpoint?'checked':'';?>>
				Redpoint &nbsp;&nbsp;  
				<input type="checkbox" name="showFlash" value="Flash" <?php echo $showFlash?'checked':'';?>>
				Flash &nbsp;&nbsp;   	
				<input type="checkbox" name="showOnsight" value="Onsight" <?php echo $showOnsight?'checked':'';?>>
				Onsight<br><br><br>
				
				<p>What is the range of difficulty you want to track? (You can always change this later)</p>
				<div id="ratingrange">
				</div>
				
				<button id="prefsubmit" name="prefsubmit" class="btn btn-success">
								Save
				</button>
			</form>
		</div>
		</div>
		
</body>
</html>
