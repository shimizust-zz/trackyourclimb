<?php

//for testing api

$fields = array("username"=>"tester",'pass'=>'pass','gymid'=>2,'date'=>'2014-11-09','boulder_notes'=>"",'tr_notes'=>"hey there",'lead_notes'=>"what up",'other_notes'=>'test msg','boulder'=>array(0,0,0),'tr'=>array(1,1,1),'lead'=>array(2,2,2));

$fields = json_encode($fields);


$ch = curl_init('http://localhost/climbtracker/api/v1/workouts/workout?apikey=123abc');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_setopt($ch, CURLOPT_POSTFIELDS, array('info'=>$fields));
curl_exec($ch);
curl_close($ch);

?>