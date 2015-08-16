<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'cookiecheck.php';	
		 		
$msg = "";
if (isset($_POST['gym-submit'])) {
	$gym_name = $_POST['gym-name'];
	$gym_address = $_POST['gym-address'];
	$gym_city = $_POST['gym-city'];
	$gym_state = $_POST['gym-state'];
	$gym_website = $_POST['gym-website'];
	$gym_country = $_POST['gym-countryCode'];
	$gym_indoor = $_POST['gym-indoor'];
	
	//determine if the same gym name exists (discount case)
	$stmt = $db->prepare("SELECT * FROM gyms WHERE LOWER(gym_name) = LOWER(:gym_name) AND LOWER(state) = LOWER(:state) AND countryCode=:countryCode AND indoor=:indoor");
	$stmt->execute(array(':gym_name'=>$gym_name,':state'=>$gym_state,':countryCode'=>$gym_country,':indoor'=>$gym_indoor));
	
	if ($stmt->rowCount() > 0) {
		$gym_result = $stmt->fetch(PDO::FETCH_ASSOC);
		//a duplicate gym exists, so don't write to database
		$climbing_area_type = $gym_indoor==1 ? 'gym' : 'crag';
		$msg = '<div class="container">
					<div class="alert alert-danger" style="text-align:center">
						Sorry, the following '.$climbing_area_type.': <a href="gympage.php?gymid='.$gym_result["gymid"].'">'.$gym_result["gym_name"].'</a> has already been added.
					</div>
				</div>';
	}
	else {
		//add gym to database
		$stmt = $db->prepare("INSERT INTO gyms (gym_name,website,city,state,address,countryCode,indoor)
			VALUES (:gym_name,:gym_website,:gym_city,:gym_state,:gym_address,:countryCode,:gym_indoor)");
		$success = $stmt->execute(array(':gym_name'=>$gym_name,':gym_website'=>$gym_website,':gym_city'=>
			$gym_city,':gym_state'=>$gym_state,':gym_address'=>$gym_address,':countryCode'=>$gym_country,':gym_indoor'=>$gym_indoor));
		$gym_id = $db->lastInsertId();
		
		if ($success) {
			$msg = '<div class="container">
					<div class="alert alert-success" style="text-align:center">
						Successfully added <a href="gympage.php?gymid='.$gym_id.'">'.$gym_name.'</a> to the database. Thank you!
					</div>
				</div>';	
		}
	}
}

//Find out user's country
//update userdata with user's country
$stmt2 = $db->prepare("SELECT countryCode FROM userdata WHERE
userid = :userid");
$stmt2->execute(array(':userid'=>$userid));
$countryResult = $stmt2->fetch(PDO::FETCH_ASSOC);
$countryCode = $countryResult['countryCode'];


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
		<script src="js/sitePath.js"></script>
		<script src="js/populateCountryFromSelectCountry.js"></script>
		<script src="js/uservoice.js"></script>
		<script src="js/changeClimbingAreaType.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
	
	</head>

	<body>
		<?php include_once("analyticstracking.php") ?>
		<?php require("navigation.php"); ?>
		
		<?php echo $msg; ?>
		<div class="panel panel-default" id="addgym-panel">
			<div class="panel-heading">
				<h3>Add a Climbing Area</h3>
			</div>
			<div class="panel-body">
			
				
				<div id = "gym-picker">

					
				<label for="country-select">Select a Country:</label>
				<select name="country-select" class="form-control" id="country-select" onchange="updateCountry()" required>
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
				
				<label for="area-type">Select Climbing Area Type:</label>
				<div class="btn-group btn-group-justified" name="area-type">
					<a class="btn btn-default climbingarea-btn active" name="indoor" role="button">Indoor Gym</a>
					<a class="btn btn-default climbingarea-btn" name="outdoor" role="button">Outdoor Crag</a>
				</div>
			
					<br>
				
					<label for="gymid" id="labelforgymid">Existing Gyms: </label>
					<div id="select-gym-div">
						<select id="select-gym" class="form-control" name="gymid">
							
							
						</select>
					</div>
				</div>
			
				
				<br><br>
				<p id="addclimbingarea-inst">Can't find your gym? Enter its details below:</p>
				
				<br>
				<form method="post">
					<div class="form-group">
						<label for="gym-name" id="labelforgymname">Gym Name (Required): </label>
						<input class="form-control" type="text" name="gym-name" required>
					</div>
					<div class="form-group">
						<label for="gym-address">Address: </label>
						<input class="form-control" name="gym-address">
					</div>
					<div class="form-group">
						<label for="gym-city">City (Required): </label>
						<input class="form-control" type="text" name="gym-city" required>
					</div>
					<div class="form-group">
						<label for="gym-state">State/Province/Region (Required. If not applicable, enter the city instead.): </label>
						<div id="state-select">
						<?php 
							if ($countryCode=='US') {
						?>
						
						<select id="states" class="form-control" name="gym-state" required>
							<option value="">Select a State...</option>
							<option value="AL">Alabama</option>
							<option value="AK">Alaska</option>
							<option value="AZ">Arizona</option>
							<option value="AR">Arkansas</option>    
							<option value="CA">California</option>    
							<option value="CO">Colorado</option>    
							<option value="CT">Connecticut</option>    
							<option value="DE">Delaware</option>    
							<option value="DC">District Of Columbia</option>    
							<option value="FL">Florida</option>    
							<option value="GA">Georgia</option>    
							<option value="HI">Hawaii</option>    
							<option value="ID">Idaho</option>    
							<option value="IL">Illinois</option>    
							<option value="IN">Indiana</option>    
							<option value="IA">Iowa</option>    
							<option value="KS">Kansas</option>    
							<option value="KY">Kentucky</option>    
							<option value="LA">Louisiana</option>    
							<option value="ME">Maine</option>
							<option value="MD">Maryland</option>
							<option value="MA">Massachusetts</option>
							<option value="MI">Michigan</option>
							<option value="MN">Minnesota</option>
							<option value="MS">Mississippi</option>
							<option value="MO">Missouri</option>
							<option value="MT">Montana</option>
							<option value="NE">Nebraska</option>    
							<option value="NV">Nevada</option>    
							<option value="NH">New Hampshire</option>    
							<option value="NJ">New Jersey</option>    
							<option value="NM">New Mexico</option>
							<option value="NY">New York</option>    
							<option value="NC">North Carolina</option>
							<option value="ND">North Dakota</option>
							<option value="OH">Ohio</option>    
							<option value="OK">Oklahoma</option>
							<option value="OR">Oregon</option>
							<option value="PA">Pennsylvania</option>
							<option value="RI">Rhode Island</option>
							<option value="SC">South Carolina</option>
							<option value="SD">South Dakota</option>
							<option value="TN">Tennessee</option>
							<option value="TX">Texas</option>
							<option value="UT">Utah</option>
							<option value="VT">Vermont</option>    
							<option value="VA">Virginia</option>
							<option value="WA">Washington</option>    
							<option value="WV">West Virginia</option>    
							<option value="WI">Wisconsin</option>
							<option value="WY">Wyoming</option>
						</select>
						<?php } else { ?>
						<input class="form-control" type="text" name="gym-state" id = "state-input" required>
						<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label for="gym-countryCode">Country:</label>
						<input class="form-control" type="hidden" name="gym-countryCode" id="gym-countryCode" value=""> 
						<input class="form-control" type="text" name="gym-countryCodeDisplay" id="gym-countryCodeDisplay" value="" readonly>
					</div>
					
					<input id="climbingAreaType" value="1" name="gym-indoor" style="display:none">
					
					<div class="form-group">
						<label for="gym-website">Website: </label>
						<div class="input-group">
							<span class="input-group-addon">http://</span>
							<input type="text" class="form-control" placeholder="www.example.com" name="gym-website">
						</div>
					</div>
					<button type="submit" class="btn btn-success" name="gym-submit">Save</button>
				</form>
			</div>
		</div>
		<script> updateCountryGyms() </script>
</body>
</html>





















