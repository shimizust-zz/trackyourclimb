<?php
//get user's local events, if any

//determine if user has a main gym set
$stmt = $db->prepare("SELECT *,userdata.countryCode AS user_countryCode FROM userdata LEFT JOIN gyms ON userdata.main_gym = gyms.gymid WHERE userid = :userid");
$stmt->execute(array(':userid'=>$userid));
$maingymResult = $stmt->fetch(PDO::FETCH_ASSOC);
$main_gymid = $maingymResult['main_gym'];
$user_country = $maingymResult['user_countryCode']; //all user's should have a country regardless of if they have a main gym set

if (is_null($main_gymid)) {
	//user's main gym not set, so just pull upcoming events from user's country

	$header_event_text = "<h4>Upcoming Climbing Events in ".$user_country."</h4><h5> <a href='userprofile.php'>(Set main gym for finer location detail)</a></h5>";
	
	$stmt2 = $db->prepare("SELECT *,DATE_FORMAT(events.event_startdate,'%W %b %e %Y') as startdate,DATE_FORMAT(events.event_enddate,'%W %b %e %Y') as enddate,GROUP_CONCAT(DISTINCT tag_desc_name ORDER BY event_eventtags.event_tagid SEPARATOR ',') AS tags FROM events LEFT JOIN event_eventtags ON events.event_id = event_eventtags.event_id LEFT JOIN eventtags ON event_eventtags.event_tagid = eventtags.event_tagid JOIN gyms ON events.gymid=gyms.gymid WHERE gyms.countryCode = :user_country AND events.event_enddate >= CURDATE() GROUP BY events.event_id ORDER BY events.event_startdate LIMIT 5");
	$stmt2->execute(array(':user_country'=>$user_country));
	
}
else {
	//extract all events within the same country and state as the main_gym
	$user_state = $maingymResult['state'];

	$header_event_text = "<h4>Upcoming Climbing Events in ".$user_state.", ".$user_country."</h4>";
	
	$stmt2 = $db->prepare("SELECT *,DATE_FORMAT(events.event_startdate,'%W %b %e %Y') as startdate,DATE_FORMAT(events.event_enddate,'%W %b %e %Y') as enddate,GROUP_CONCAT(DISTINCT tag_desc_name ORDER BY event_eventtags.event_tagid SEPARATOR ',') AS tags FROM events LEFT JOIN event_eventtags ON events.event_id = event_eventtags.event_id LEFT JOIN eventtags ON event_eventtags.event_tagid = eventtags.event_tagid JOIN gyms ON events.gymid=gyms.gymid WHERE gyms.state = :user_state AND gyms.countryCode = :user_country AND events.event_enddate >= CURDATE()GROUP BY events.event_id ORDER BY events.event_startdate LIMIT 5");
	$stmt2->execute(array(":user_state"=>$user_state,":user_country"=>$user_country));
	
	
}

$event_result = "<br>";
$results = $stmt2->fetchAll(PDO::FETCH_ASSOC);
if (empty($results)) {
	$event_result = "<div class='event-box'><h4>No events found. Add an event <a href='add-event.php'>here.</a></h4></div>";
}

foreach ($results as $row) {
	$startdate = explode(" ",$row['startdate']);
	$enddate = explode(" ",$row['enddate']);
	
	//check if months are the same
	if ($startdate[1]==$enddate[1]) {
		$month = $startdate[1];
	}
	else {
		$month = $startdate[1]."-".$enddate[1];
	}
	
	//check if days are the same
	if ($startdate[0]==$enddate[0]) {
		$day_num = $startdate[2];
		$day_str = $startdate[0];
	}
	else {
		$day_num = $startdate[2]."-".$enddate[2];
		$day_str = $startdate[0]."-".PHP_EOL.$enddate[0];
	}
	
	//get truncated event details
	$event_details = substr($row['event_desc'],0,100)." ...";
	
	//get tag string
	$tag_str = "";
	$event_tags = explode(",",$row['tags']);
	foreach ($event_tags as $curr_tag) {
		$tag_str = $tag_str."<span class='label label-info event-tags'>".$curr_tag."</span>";
	}
	
	$event_result = $event_result."<a href='event.php?event_id=".$row['event_id']."'><div class='event-box'><div class='row'><div class='col-sm-3'><div class='col-sm-12'><p>".$month."</p></div><div class='col-sm-12'><h4 style='text-align:left'>".$day_num."</h4></div><div class='col-sm-12'><p>".$day_str."</p></div></div><div class='col-sm-9'><div class='col-sm-12'><p>".$row['gym_name']."</p></div><div class='col-sm-12'><h4 style='text-align:left'>".$row['event_name']."</h4></div><div class='col-sm-12'><pre class='event-desc'>".$event_details."</pre></div><div class='col-sm-12'>".$tag_str."</div></div></div></div></a>";
}



?>






























