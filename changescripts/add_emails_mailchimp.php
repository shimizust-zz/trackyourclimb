<?php
/*
include '../mailchimp_subscribe.php';
include '../dbconnect.php';

ini_set('max_execution_time',500);
$stmt = $db->prepare("SELECT email FROM users");
$stmt->execute();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	//add email to MailChimp list
	$email = $row['email'];
	$MailChimp = initialize_mailchimp();
	mailchimp_subscribe($email,$MailChimp);

}
*/	
	
?>