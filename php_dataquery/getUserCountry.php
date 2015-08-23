<?php

//Find out user's country
//update userdata with user's country
$stmt2 = $db->prepare("SELECT countryCode FROM userdata WHERE
userid = :userid");
$stmt2->execute(array(':userid'=>$userid));
$countryResult = $stmt2->fetch(PDO::FETCH_ASSOC);
$countryCode = $countryResult['countryCode'];

?>