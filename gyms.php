<?php 
include "./core/bootstrap.php";

//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

//get user's country
$userService = new UserService();
$countryCode = $userService->getUserCountryCode($userid);

//for each country, extract the number of gyms
$stmt = $db->prepare("SELECT * FROM gyms");
$stmt->execute();
$gym_country_num = array();
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	$gym_country_num[strtolower($row['countryCode'])]++;
}		
include 'php_dataquery/getCountryNameFromCode.php';
$countryName = country_code_to_country($countryCode);

?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Climbing Tracker</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<!-- Latest compiled and minified CSS -->
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>

		<script src="bower_components/jqvmap/jqvmap/jquery.vmap.js"></script>
		<script src="bower_components/jqvmap/jqvmap/maps/jquery.vmap.world.js"></script>
		
		<script>
			var gym_country_num = <?php echo json_encode($gym_country_num)?>;

		</script>
		<script src="js/sitePath.js"></script>
		<script src="js/uservoice.js"></script>
		<link rel="stylesheet" type="text/css" href="bower_components/jqvmap/jqvmap/jqvmap.css">
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		
	</head>
	
	<body>
		<div id="wrap">
			<div id="main">
				<?php include_once("php_common/analyticstracking.php") ?>
				<?php require("navigation.php"); ?>
				
				<div class="container">
					<div class="page-header"><h1>Climbing Areas</h1></div>
			
					
					<p>Click on a country to see its climbing areas</p>
					<div class="container-fluid" style="height:450px;">
					<div id="vmap" style="width: 700px; height: 500px; margin: 0 auto;"></div>
					</div>
					
					<script src="js/writeGymList.js"></script>
					<script>
						var countryCode = '<?php echo $countryCode ?>';
						var region = '<?php echo $countryName ?>';
						countryCode = countryCode.toLowerCase();
						url2 = sitePath()+"/php_dataquery/getGymsByCountry.php?countryCode="+countryCode+"&indoor=1";
						listID = "gymList";
							$.ajax({
								url: url2,
								dataType: 'json',
								success: function(data) {
									//Note: inner functions have access to variables in outer function
									
									writeGymList(data,region,listID);
								},
								error: function(){
									alert('Error in AJAX call');
								}
							});
							
						url4 = sitePath()+"/php_dataquery/getGymsByCountry.php?countryCode="+countryCode+"&indoor=0";
						listID2 = "cragList";
							$.ajax({
								url: url4,
								dataType: 'json',
								success: function(data) {
									//Note: inner functions have access to variables in outer function
									
									writeGymList(data,region,listID2);
								},
								error: function(){
									alert('Error in AJAX call');
								}
							});
					</script>
					<script>
	
					$('#vmap').vectorMap({
						map: 'world_en',
						backgroundColor: null,
						color: '#ffffff',
						hoverOpacity: 0.7,
						selectedColor: '#666666',
						enableZoom: true,
						showTooltip: true,
						values: gym_country_num,
						scaleColors: ['#C8EEFF', '#006491'],
						normalizeFunction: 'polynomial',
						onRegionClick: function(element,code,region)
						{
							var clickedCountry = code.toUpperCase();
							//create an ajax request to populate a list of gyms
							
							//call PHP function with these parameters to get data
							$.ajaxSetup({cache:false});
							
							
							url1 = sitePath()+"/php_dataquery/getGymsByCountry.php?countryCode="+clickedCountry+"&indoor=1";
							listID = "gymList";
							$.ajax({
								url: url1,
								dataType: 'json',
								success: function(data) {
									//Note: inner functions have access to variables in outer function
									writeGymList(data,region,listID);
								},
								error: function(){
									alert('Error in AJAX call');
								}
							});
							
							url3 = sitePath()+"/php_dataquery/getGymsByCountry.php?countryCode="+clickedCountry+"&indoor=0";
							listID2 = "cragList";
							$.ajax({
								url: url3,
								dataType: 'json',
								success: function(data) {
									//Note: inner functions have access to variables in outer function
									writeGymList(data,region,listID2);
								},
								error: function(){
									alert('Error in AJAX call');
								}
							});
						}
						
				
					});

</script>
		<div class="row">
		<h2 id="countryHeader"><?php echo $countryName ?></h2><hr>
			<div class="col-sm-6">
				<h3>Indoor Gyms</h3>
				<div id="gymList">
					

				</div>
			</div>
			<div class="col-sm-6">
				<h3>Outdoor Crags</h3>
				<div id="cragList">
					
				
				</div>
			</div>
		</div>
		
			<div id="scroll-padding">
			</div>
				
				</div>
			</div>
		
		</div>
		
		
		
		<?php require("php_common/footer.php"); ?>
	</body>
</html>
