<?php

if (isset($_POST['prefsubmit'])) {
	//if user has submitted preferences
	
	
	$climbType1 = $_POST['climbType'];
	//array of booleans whether each climbType is checked
	$climbType2 = array(); 
	$climbType2[] = isset($_POST['showBoulder']);
	$climbType2[] = isset($_POST['showTR']);
	$climbType2[] = isset($_POST['showLead']);
	
	$ascentType1 = $_POST['ascentType'];
	//array of booleans whether each ascentType is checked
	$ascentType2 = array();
	$ascentType2[] = isset($_POST['showProject']);
	$ascentType2[] = isset($_POST['showRedpoint']);
	$ascentType2[] = isset($_POST['showFlash']);
	$ascentType2[] = isset($_POST['showOnsight']);
	
	//get the indices of min/max rating ranges, these are indices, not text values of the grades
	$minBoulder = $_POST['minBoulderRange'];
	$maxBoulder = $_POST['maxBoulderRange'];
	$minTR = $_POST['minTRRange'];
	$maxTR = $_POST['maxTRRange'];
	$minL = $_POST['minLeadRange'];
	$maxL = $_POST['maxLeadRange'];
	
	//get indices of boulder and route grading systems
	$boulderGradingSystemID = $_POST['boulder-rating-select'];
	$routeGradingSystemID = $_POST['route-rating-select'];
	
	//get country abbreviation
	$countryCode = $_POST['country-select'];
	echo $countryCode;
	
	//write to the userprefs database table
	$stmt = $db->prepare("UPDATE userprefs SET show_boulder=:show_boulder,
	show_TR=:show_TR,show_Lead=:show_Lead,show_project=:show_project,
	show_redpoint=:show_redpoint,show_flash=:show_flash,show_onsight=
	:show_onsight,minV=:minV,maxV=:maxV,minTR=:minTR,maxTR=:maxTR,
	minL=:minL,maxL=:maxL,boulderGradingSystemID=:boulderGradingSystemID,
    routeGradingSystemID=:routeGradingSystemID WHERE userid = :userid");
	
	
	$stmt->execute(array(':show_boulder'=>$climbType2[0],':show_TR'=>
	$climbType2[1],':show_Lead'=>$climbType2[2],':show_project'=>
	$ascentType2[0],':show_redpoint'=>$ascentType2[1],':show_flash'=>
	$ascentType2[2],':show_onsight'=>$ascentType2[3],':minV'=>$minBoulder,
	':maxV'=>$maxBoulder,':minTR'=>$minTR,':maxTR'=>$maxTR,':minL'=>$minL,
	':maxL'=>$maxL,':boulderGradingSystemID'=>$boulderGradingSystemID,
	':routeGradingSystemID'=>$routeGradingSystemID,':userid'=>$userid));
	
	//update userdata with user's country
	$stmt2 = $db->prepare("UPDATE userdata SET countryCode=:countryCode WHERE
	userid = :userid");
	$stmt2->execute(array(':countryCode'=>$countryCode,':userid'=>$userid));
	
	//once preferences are set, go to the workout input page
	header('Location: workout-input.php');
}	

?>