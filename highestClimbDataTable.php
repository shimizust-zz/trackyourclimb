<?php
//Create a table showing max grades for Bouldering, TR, Lead for different
//ascent types

include 'BoulderRouteGradingSystems.php';

$stmt = $db->prepare("SELECT * FROM userrecords WHERE userid = ?");
$stmt->execute(array($userid));
$result = $stmt->fetch(PDO::FETCH_BOTH);


//extract their user preferences
$stmt2 = $db->prepare("SELECT * FROM userprefs WHERE userid 
	= :userid");
$stmt2->execute(array(':userid'=>$userid));
$userprefs = $stmt2->fetch(PDO::FETCH_ASSOC);
$boulderGradingID = $userprefs['boulderGradingSystemID'];
$routeGradingID = $userprefs['routeGradingSystemID'];
	
$records_table = '<h3>My Records</h3><table class="table table-striped table-hover table-bordered"
id = "records-table">
<tr><th></th><th>Attempt</th><th>Redpoint</th><th>Flash</th><th>Onsight</th></tr>';

$climbTypes = array('Boulder','Top Rope','Lead');
$ascentTypes = array('Project','Redpoint','Flash','Onsight');
for ($i = 0; $i < 3; $i++) {
	$records_table .= "<tr><th>".$climbTypes[$i]."</th>";
	for ($j = 0; $j < 4; $j++) {
		if ($result[$i*4+$j+1]>=0) {
			if ($i == 0) {
				//bouldering
				$grade_text = $boulderConversionTable[$boulderGradingID][$result[$i*4+$j+1]];
				
			}
			else {
				//TR or lead
				$grade_text = $routeConversionTable[$routeGradingID][$result[$i*4+$j+1]];
			}
		}
		else {
			$grade_text = "";
		}
		
		$records_table .= "<td>".$grade_text."</td>";
	}
	$records_table .= "</tr>";
}
$records_table .= "</table>";


echo $records_table;

?>