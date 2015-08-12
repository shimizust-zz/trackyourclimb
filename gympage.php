<?php

include 'dbconnect.php';
include 'cookiecheck.php';
if (isset($_GET['gymid'])) {
	$gymid = $_GET['gymid'];
}
else {
	header('Location: gyms.php');
}

//get gym name
$stmt = $db->prepare("SELECT * FROM gyms WHERE gymid=:gymid");
$stmt->execute(array(':gymid'=>$gymid));
$result = $stmt->fetch(PDO::FETCH_ASSOC);
include 'php_dataquery/getCountryNameFromCode.php';

include 'genDefaultRankingTable.php';

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

		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>

		<script src="flot/jquery.flot.js"></script>	
		<script src="flot/jquery.flot.axislabels.js"></script>
		<script src="flot/jquery.flot.categories.js"></script>
		<script src="flot/jquery.flot.tickrotor.js"></script>
		<script src="flot/jquery.flot.resize.js"></script>
		<script src="js/sitePath.js"></script>
		
		<script src="js/rankings/get_update_rankings.js"></script>
		<script src="js/plots/get_update_ClimbDataAll.js"></script>
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script>
			var gymid = <?php echo $gymid?>;
			var initialTimeFrame = 'month';
			
			//run function from get_update_rankings.js
			getboulderRankingData('grade','boulder',initialTimeFrame,'all',gymid,<?php echo $userid?>);
			getleadRankingData('grade','lead',initialTimeFrame,'all',gymid,<?php echo $userid?>);
			getTRRankingData('grade','TR',initialTimeFrame,'all',gymid,<?php echo $userid?>);
			
			//can't plot hidden Flot plots here because jQuery has trouble obtaining dimensions of hidden elements. Instead, bind .plot functions to the tab .click event.

			
		</script>
		<script>
		$(document).ready(function() {

			//$('#gym-tabs li:eq(1) a').tab('show');

			var climb_dist = document.getElementById("climb-dist-tab");
			$(climb_dist).click(function() {
			
				//first show the tab
				$('#gym-tabs li:eq(2) a').tab('show');
			
				getTotalBoulderData('RFO','-1',<?php echo $userid ?>,gymid);
				getTotalTRData('RFO','-1',<?php echo $userid ?>,gymid);
				getTotalLeadData('RFO','-1',<?php echo $userid ?>,gymid);
				

			});
			
			var activeTab = null;
			$('a[data-toggle="tab"]').on('shown',function(e) {
				activeTab = e.target;
				getTotalBoulderData('RFO','-1',<?php echo $userid ?>,gymid);
				getTotalTRData('RFO','-1',<?php echo $userid ?>,gymid);
				getTotalLeadData('RFO','-1',<?php echo $userid ?>,gymid);
			});
		});
		$('#climb-dist').on('hide',function() {
			console.log('ehjklwe');
		});


		</script>
		<script src="js/uservoice.js"></script>
	</head>

	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
				<div class="page-header"><h1><?php echo $result['gym_name']?></h1></div>
				
				<ul class="nav nav-tabs" role="tablist" id="gym-tabs">
				  <li class="active"><a href="#about-gym" role="tab" data-toggle="tab">Gym Info</a></li>
				  <li><a href="#gym-ranking" role="tab" data-toggle="tab">Gym Rankings</a></li>
				  <li><a href="#climb-dist" role="tab" data-toggle="tab" id="climb-dist-tab">Climb Distribution</a></li>
				  
				</ul>
				
				<!-- Tab panes -->
				<div class="tab-content">
				  <div class="tab-pane fade in active" id="about-gym">
					  <div id="gym-list">
						<a href="gym-edit.php?gymid=<?php echo $gymid;?>"><p style="text-align:right">Edit Gym Details...</p></a>
						<ul class="list-group">
							<?php
								$URL = $result['website'];
								if(strpos($URL, "http://")!== false) $URL = $URL;
								else $URL = "http://$URL";
							?>
							<li class="list-group-item"><b>Website:</b> <a href=<?php echo $URL?> target="_blank"><?php echo $URL?></a></li>
							<li class="list-group-item"><b>Address:</b> <?php echo $result['address']?></li>
							<li class="list-group-item"><b>City:</b> <?php echo $result['city']?></li>
							<li class="list-group-item"><b>State:</b> <?php echo $result['state']?></li>
							<li class="list-group-item"><b>Country:</b> <?php echo country_code_to_country($result['countryCode'])?></li>
						</ul>
					</div>
				  
				  
				  </div>
				  <div class="tab-pane fade" id="gym-ranking">
					<div class="container">
						
							
							<div><h3 id="ranking-header">Boulder Rankings</h3></div>
							<?php genRankingTable('boulder',$gym_options,$userid,$gymid); ?>
							
							
							<div><h3 id="ranking-header">Top-Rope Rankings</h3></div>
							<?php genRankingTable('TR',$gym_options,$userid,$gymid); ?>
							
							<div><h3 id="ranking-header">Lead Rankings</h3></div>
							<?php genRankingTable('lead',$gym_options,$userid,$gymid); ?>
							
							
					</div>
	
				  
				  </div>

				  
				  <div class="tab-pane fade" id="climb-dist">
				  <!--Plot a histogram of all climbing grades (so number of climbs vs. grade)-->
					<h3>Boulder Grade Distribution</h3>
					<select id="ascentSelect" onchange="getTotalBoulderData(this.value,'-1',<?php echo $userid ?>,gymid)">
						<option value="Project" >Attempt</option>
						<option value="Redpoint" >Redpoint</option>
						<option value="Flash">Flash</option>
						<option value="Onsight">Onsight</option>
						<option value="RFO" selected>>=Redpoint</option>
					</select>
					<div class="gradehistogram" id = "bouldergradehistogram">
						
					</div>
					
					<h3>Top Rope Grade Distribution</h3>
					<select id="ascentSelect" onchange="getTotalTRData(this.value,'-1',<?php echo $userid ?>,gymid)">
						<option value="Project" >Attempt</option>
						<option value="Redpoint">Redpoint</option>
						<option value="Flash">Flash</option>
						<option value="Onsight">Onsight</option>
						<option value="RFO" selected>>=Redpoint</option>
					</select>
					<div class="gradehistogram" id = "trgradehistogram">
						
					</div>
					
					<h3>Lead Grade Distribution</h3>
					<select id="ascentSelect" onchange="getTotalLeadData(this.value,'-1',<?php echo $userid ?>,gymid)">
						<option value="Project" >Attempt</option>
						<option value="Redpoint">Redpoint</option>
						<option value="Flash">Flash</option>
						<option value="Onsight">Onsight</option>
						<option value="RFO" selected>>=Redpoint</option>
					</select>
					<div class="gradehistogram" id = "leadgradehistogram">
						
					</div>
					
				  </div>
				  
				</div>
				
				</div>
				<p id = "scroll-padding"></p>
				
				
				
			</div>
			
			
			
			
		</div>
		<script>
			//change initial time frame selection to be initialTimeFrame
			
			var climbType = ['boulder','TR','lead'];
			for (var i=0; i<3; i++) {
				$('#'+climbType[i]+'TimeFrame option[value='+initialTimeFrame+']').attr("selected","selected");
			}
			
		</script>
		
		
		
		<?php require("footer.php"); ?>
	</body>
	
</html>
