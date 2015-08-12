<?php

//connect to database
include '../dbconnect.php';

$stmt = $db->prepare("SELECT * FROM events");
$stmt->execute();

$events_all_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$event_list = array();
$event_list['success'] = 1;
$event_list['result'] = array();

foreach ($events_all_result as $event) {
	$event_startdate = date_create_from_format('Y-m-d H:i:s',$event['event_startdate']."12:00:00");
	//echo date_format($event_startdate,'Y-m-d H:i:s');
	$event_enddate = date_create_from_format('Y-m-d H:i:s',$event['event_enddate']."12:00:00");
	
	$event_startdate = (float)date_format($event_startdate,'U')*1000.0;
	$event_enddate = (float)date_format($event_enddate,'U')*1000.0;
	if ($event_enddate == $event_startdate) {
		$event_enddate = $event_startdate+2000000;
	}
	
	$event_list['result'][] = array('id'=>$event['event_id'],"title"=>$event['event_name'],"url"=>"event.php?event_id={$event['event_id']}","class"=>"event-warning","start"=>$event_startdate,"end"=>$event_enddate);
}
echo json_encode($event_list);

?>