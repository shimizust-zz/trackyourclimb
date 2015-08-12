<?php
//Note: define $gymOptions before calling this file, with the placeholder text
$stmt2 = $db->prepare("SELECT gymid, gym_name, city, state, countryCode FROM gyms WHERE countryCode=:countryCode ORDER BY state, gym_name ");
//build up gym option table
$old_state = "";

if ($stmt2->execute(array(':countryCode'=>$countryCode))) {
	while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
		//if the user has a main gym, then make that the selected option, 
		//otherwise use placeholder text "Select your Gym..."
		if ((int)$row['gymid']==(int)$main_gymid) {
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