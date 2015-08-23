<?php

//extract user grading system prefs
//Check current userprefs
$stmt2 = $db->prepare("SELECT * FROM userprefs WHERE userid = :userid");
$stmt2->execute(array(':userid'=>$userid));
$prefs = $stmt2->fetch(PDO::FETCH_ASSOC);
$boulderGradingID = $prefs['boulderGradingSystemID'];
$routeGradingID = $prefs['routeGradingSystemID'];

?>