<?php
$stmt = $db->prepare("SELECT COUNT(*) FROM users");
$stmt->execute();
$numUserResult = $stmt->fetch(PDO::FETCH_NUM);
$numUsers = $numUserResult[0];
?>