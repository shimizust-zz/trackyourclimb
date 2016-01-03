<?php
include './core/bootstrap.php';

//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';               
       
$workoutId = $_GET['wid'];     
$workoutLoggingService = new WorkoutLoggingService();

$workoutInfo = $workoutLoggingService->getWorkoutInfo($workoutId);
$workoutUserId = $workoutInfo["userid"];

// Check that user of the workout_id_prev matches that of the cookie userid
if ($userid != $workoutUserId) {
    header('HTTP/1.1 500 Internal Server Error');
} else {
    $workoutLoggingService->deleteWorkout($workoutId);

    //update records table
    include 'update-records-absolute.php';
}

?>