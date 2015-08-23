<?php 
	
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	
include 'php_dataquery/getCountryNameFromCode.php';	 		
include 'php_common/cleanURL.php';

//URL should have an event id query attached
if (isset($_GET['event_id'])) {
	$event_id = $_GET['event_id'];
	
	//extract event details
	$stmt = $db->prepare("SELECT *,GROUP_CONCAT(DISTINCT tag_desc_name ORDER BY event_eventtags.event_tagid SEPARATOR ',') AS tags FROM events JOIN event_eventtags ON events.event_id = event_eventtags.event_id JOIN eventtags ON event_eventtags.event_tagid = eventtags.event_tagid JOIN gyms ON events.gymid = gyms.gymid WHERE events.event_id = :event_id");
	$stmt->execute(array(':event_id'=>$event_id));
	$event_result = $stmt->fetch(PDO::FETCH_ASSOC);
	
	$event_tags = explode(",",$event_result['tags']); //text of event tags
	
	$startdate = date_create_from_format('Y-m-d H:i:s',$event_result['event_startdate']."12:00:00");
	$enddate = date_create_from_format('Y-m-d H:i:s',$event_result['event_enddate']."12:00:00");
}
				
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Gym Climbing Tracker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("php_common/analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
				<div class="page-header"><h1>Event Details</h1></div>
				
				<div class="row">
					<div class="col-xs-12 col-sm-10">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h2 style="text-align:center"><?php echo $event_result['event_name'];?></h2>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-xs-12 col-sm-6">
										<h4 class="event-details"><b>Gym: </b><a href="gympage.php?gymid=<?php echo $event_result['gymid']?>"><?php echo $event_result['gym_name']?></a></h4>
										
										<h4 class="event-details"><b>Address: </b><?php echo $event_result['address']?></h4>
										
										<h4 class="event-details"><b>City: </b><?php echo $event_result['city']?></h4>
										
										<h4 class="event-details"><b>State: </b><?php echo $event_result['state']?></h4>
										
										<h4 class="event-details"><b>Country: </b><?php echo  country_code_to_country($event_result['countryCode'])?></h4>
									</div>
									
									<?php
										$website_URL = cleanURL($event_result['event_website']);
										$facebook_URL = cleanURL($event_result['event_facebook']);
										
										
									?>
									<div class="col-xs-12 col-sm-6">
										<?php if ($event_result['event_startdate']==$event_result['event_enddate']) {
										?>
										<h4 class="event-details"><b>Date: </b><?php echo date("l, F j, Y",date_format($startdate,'U'));
										}
										else {?>
										<h4 class="event-details"><b>Start Date: </b><?php echo date("l, F j, Y",date_format($startdate,'U'));?>
										<h4 class="event-details"><b>End Date: </b><?php echo date("l, F j, Y",date_format($enddate,'U'));}?>
										
										<h4 class="event-details" style="display:block"><b>Event Website: </b></h4><a href=<?php echo $website_URL?> target="_blank" style="word-wrap:break-word"><?php echo $website_URL?></a>
										<br>
										<h4 class="event-details" style="display:block"><b>Facebook Page: </b></h4><a href=<?php echo $facebook_URL?> target="_blank" style="word-wrap:break-word"><?php echo $facebook_URL?></a>
									</div>
								</div>
								<hr>
								<h4 class="event-details"><b>Event Details:</b></h4>
								<pre style="font-family: 'Helvetica Neue',Helvetica,Arial,sans-serif;white-space:pre-wrap;word-break:normal"><?php echo $event_result['event_desc']?></pre>
								
								<hr>
								<iframe
								  width="100%"
								  height="350"
								  frameborder="0" 
								  style="border:0"
								  src="https://www.google.com/maps/embed/v1/place?key=AIzaSyA1IDASvrpzer9RkhxiOb3FCT1_tUziFZ0
									&q=<?php echo $event_result['gym_name'].'+'.$event_result['address'].'+'.$event_result['city'];?>">
								</iframe>
							
									
							</div>
						</div>
					
					</div>
					
					<div class="col-xs-12 col-sm-2">
					
						<h4>Event Tags</h4><hr>
						<?php 
						foreach ($event_tags as $curr_tag) {
						?>
						<span class="label label-info" style="display:inline-block;margin-bottom:5px"><?php echo $curr_tag?></span>
						
						<?php 
						}
						?>
					</div>
					</div>
				
				</div>
			</div>
			
			
			
			
		</div>
		
		
		
		<?php require("php_common/footer.php"); ?>
	</body>
</html>
