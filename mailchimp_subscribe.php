<?php
include 'mailchimp-api-master/src/Drewm/MailChimp.php';

function initialize_mailchimp() {
	$siteprop_array = parse_ini_file("siteproperties.ini");
	$apikey = $siteprop_array["mailchimp_apikey"];
	$MailChimp = new \Drewm\MailChimp($apikey);
	return $MailChimp;
}

function mailchimp_subscribe($email,$MailChimp) {
	//subscribe a single user email to the MailChimp TrackYourClimb newsletter

	$siteprop_array = parse_ini_file("siteproperties.ini");
	
	//subscribe to TrackYourClimb Newsletter
	$listid = $siteprop_array["mailchimp_listid"];
	
	$result = $MailChimp->call('lists/subscribe',array(
		'id' => $listid,
		'email' => array('email'=>$email),
		'send_welcome' => false,
		'update_existing' => true,
		'double_optin' => false,
		));
	
}
?>