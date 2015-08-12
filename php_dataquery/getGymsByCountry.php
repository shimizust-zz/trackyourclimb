<?php

include '../dbconnect.php';

$countryCode = $_GET['countryCode'];

if (isset($_GET['indoor'])) {
	$indoor = $_GET['indoor'];
} else {
	$indoor = -1;
}

if ($indoor == -1) {
	$stmt = $db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE countryCode = :countryCode ORDER BY state ASC, gym_name ASC");
	$stmt->execute(array(':countryCode'=>$countryCode));
} else {
	$stmt = $db->prepare("SELECT gymid,gym_name,state FROM gyms WHERE countryCode = :countryCode AND indoor = :indoor ORDER BY state ASC, gym_name ASC");
	$stmt->execute(array(':countryCode'=>$countryCode,':indoor'=>$indoor));
}


$gyms_list = array();
$currState = "";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$currState = $row['state'];
	$gyms_list[$currState][] = array($row['gymid'],$row['gym_name']);
}

echo json_encode($gyms_list);

?>