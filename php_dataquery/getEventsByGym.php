<?php

include '../dbconnect.php';

$gymid = $_GET['gymid'];

$stmt = $db->prepare("SELECT * FROM events INNER JOIN gyms ON events.gymid = gyms.gymid WHERE events.gymid = :gymid AND events.event_enddate >= CURDATE()");
$stmt->execute(array(':gymid'=>$gymid));

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC),JSON_FORCE_OBJECT);

?>