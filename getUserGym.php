<?php
//get user's main gym, if any

$stmt2 = $db->prepare("SELECT main_gym FROM userdata WHERE
userid = :userid");
$stmt2->execute(array(':userid'=>$userid));
$maingymResult = $stmt2->fetch(PDO::FETCH_ASSOC);
$main_gymid = $maingymResult['main_gym'];

?>