<?php

$countryCode = $_GET['countryCode'];

//flag indicating whether the climbing area is indoor/outdoor
$indoor = $_GET['indoor']; 
$climbing_area_type = $indoor==1 ? 'Gyms' : 'Crags';

if (empty($_GET['climbingarea_id'])) {
	$climbingarea_id = null;
}
else {
	$climbingarea_id = $_GET['climbingarea_id'];
}

include '../dbconnect.php';
//include '../cookiecheck.php';

$stmt2 = $db->prepare("SELECT gymid, gym_name, city, state, countryCode FROM gyms WHERE countryCode=:countryCode AND indoor=:indoor ORDER BY state, gym_name ");
//build up gym option table
$old_state = "";
$gym_options = '<select id="select-gym" class="form-control" name="gymid" required ><option value="">Existing '.$climbing_area_type.'...</option>';

if ($stmt2->execute(array(':countryCode'=>$countryCode,':indoor'=>$indoor))) {
	while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		//if the user has a main gym, then make that the selected option, 
		//otherwise use placeholder text "Select your Gym..."
		if ((int)$row['gymid']==(int)$climbingarea_id) {
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

$gym_options .= "</optgroup></select>";
echo $gym_options;

?>