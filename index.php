<?php
include "./core/bootstrap.php";
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Track Your Climb</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!-- Latest compiled and minified CSS -->
		<meta name="description" content="Track Your Climb is a simple climbing tracker for both indoors and outdoors. Track all of your bouldering, top-roping and lead climbing workouts at the gym or crag. Monitor your progress over time and see how you compare with others.">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css">

		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/smoothscroll.js"></script>
		
		<script type="text/javascript">
			//Have first input focused when open login or register modals
			$(document).ready(function() {
				$("#login-modal").on("shown.bs.modal", function() {
					console.log("modal shown");
					$(this).find("#logininput-modal").focus();
				});
			});
			$(document).ready(function() {
				$("#register-modal").on("shown.bs.modal", function() {
					console.log("modal shown");
					$(this).find("#registerinput-modal").focus();
				});
			});
		</script>
		<script src="js/uservoice.js"></script>
		<link href='http://fonts.googleapis.com/css?family=Lato:900' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="style.php/mycss.scss">
		<style>
			h1 {
				font-size: 5.5em;
			}
			h2 {
				font-size: 4em;
			}
			h3 {
				font-size: 3em;
			}
			h4 {
				font-size: 2em;
			}
			h5 {
				font-size:1.6em;
			}
			p {
				font-size: 1em;
			}
			ul {
				text-align: left;
				color: black;
			}
			
		</style>
	</head>
	<body>
		<div id="wrap">
			<div class="main">
				<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				
				
				<?php include_once("php_common/analyticstracking.php") ?>
<?php 

//connect to database
include 'dbconnect.php';

$userService = new UserService();
include 'php_dataquery/getNumClimbs.php';
include 'php_dataquery/getNumGyms.php';
$numUsers = $userService->getNumUsers();
/*
 * This login page has the following scenarios:
 * 1) If user already has valid cookies for the site, then send them to
 * their user page
 * 2) If they have submitted the login form, check if valid credentials
 * and if so, send them to their user page
 * 3) Else if they haven't submitted the login form, present the login form
 * for them to fill out.
 * 
 */


 //Checks if there is a login cookie
 if(isset($_COOKIE['ID_my_site'])) {
 //if there is, it logs you in and directs you to the members page

	
 	$userid = $_COOKIE['ID_my_site'];
	$pass = $_COOKIE['Key_my_site']; //this is the pass_hash

	$stmt = $db->prepare('SELECT * FROM users WHERE userid = ?');
	$stmt->execute(array($userid));
	$info = $stmt->fetch(PDO::FETCH_ASSOC);
	
	if ($stmt->rowCount()>0) {
		if ($pass != $info['pass_hash']) {
			//no valid cookie key, so show rest of the page
		}

		else {
			header("Location: main.php");
		}
	}
 }



 //if the login form is submitted 

if (isset($_POST['loginsubmit'])) {
	//check login credentials and set up cookies
	include 'login-check.php';
} 
//if the registration form is submitted
else if (isset($_POST['registersubmit'])) {
	include 'register-user.php';
}
//else if login form is not submitted, then show the login form
else {
?>

		<div class="navbar navbar-default">
			<div class = "container-fluid" id="index-navbar-container">
				<form class="navbar-form navbar-left" id="learnmore-navbar">
					<a class="btn btn-info smoothScroll" href="#howitworks" id="learnmore-btn">Learn More</a>
				</form>
				<form class="navbar-form navbar-right">
					<a class="btn btn-primary" id="register-btn" href="#register-modal" data-toggle="modal" style="color:#FFFFFF" id="register-btn">Register</a>
					
					<a class="btn btn-primary" id="login-btn" href="#login-modal" data-toggle="modal" style="color:#FFFFFF" id="login-btn">Login</a>
				</form>
				
			</div>
		</div>	

		
		<div class = "jumbotron" id="indexpage-jumbotron" title="Track Your Climb Beta: A Simple Climbing Tracker">
			<div id="splashtitle-mobile">
				<h1>TRACK YOUR CLIMB</h1>
				<h4>A SIMPLE CLIMBING TRACKER</h4>
			</div>
		</div>
		
		
		<div class="jumbotron jumbotron-frontpage">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<h4>Recent News</h4><hr>
						<h5>Follow on Twitter for the latest site updates and news</h5>
						
						<a class="twitter-timeline" href="https://twitter.com/TrackYourClimb" data-widget-id="515850954487263234">Tweets by @TrackYourClimb</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script> 

					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="col-md-12">
							<h4>Number of Climbs Logged:</h4> 
							<h2><?php getNumClimbs($db) ?></h2>
						</div>
						<div class="col-md-12">
						<hr>
							<h4>Number of Climbers: <h4><h2><?php echo $numUsers; ?></h2>
						</div>
						<div class="col-md-12">
						<hr>
							<h4>Number of Gyms:</h4> <h2><?php echo $numGyms; ?></h2>
						</div>
						<div class="col-md-12">
						<hr>
							<h4>Number of Countries:</h4> <h2><?php echo $numCountries; ?></h2>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="jumbotron jumbotron-frontpage" id="howitworks">
		<h2>HOW IT WORKS</h2>
		<hr>
		<div class="container-fluid">
			<div class="row">
				<div class="col-xs-12">
					<h3>TRACK YOUR CLIMBS</h3>
					
					<div class="col-xs-12 col-sm-5">
						<img src = "images/IphoneScreenshotMobile.png" style="width:100%;height:auto;">
						
					</div>
					<div class="col-xs-12 col-sm-2" style="padding-top:5%">
						<h4>OR</h4>
					</div>
					<div class="col-xs-12 col-sm-5">
						<img src = "images/ComputerScreenshotonPC.png" style="width:100%;height:auto;">
						
					</div>
					<div class="col-xs-12">
					</div>
					<div class="col-xs-12 col-sm-5">
						<h5>Open the site on any mobile browser and log climbs at the gym. </h5>
					</div>
					<div class="col-xs-12 col-sm-2">&nbsp</div>
					<div class="col-xs-12 col-sm-5">
						<h5>Remember what you climbed and input them later on your computer.</h5>
					</div>
					<div class="col-xs-12">
					<img src="images/WorkoutInput-InnerShadow.png" id="howitworks-image">
					</div>
					<h5>Easily input boulder, top-rope and lead climbs along with ascent types. All major grading systems are supported.</h5>
					<hr>
					<div class="col-xs-12">
						<h3>YOUR STATS</h3>
						
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/BoulderDistribution.png" id="img-shadow">
							</div>
							<div id="img-shadow-div">
								<img src="images/PieChart.png" id="img-shadow">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/BoulderProgression.png" id="img-shadow">
							</div>
							<div id="img-shadow-div">
								<img src="images/TimeProgression.png" id="img-shadow">
							</div>
						</div>
						
					</div>
					<h5>Look at your progression over time, climb distribution, and keep track of your highest climbs</h5>
						<hr>
					<div class="col-xs-12">
						<h3>COMMUNITY AND GYM STATS</h3>
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/WorldMap.png" id="img-shadow">
							</div>
							<h5>Explore gyms from all over the world</h5>
							
							<div id="img-shadow-div">
								<img src="images/GymClimbDistribution.png" id="img-shadow">
							</div>
							<h5>See what grades are climbed most at your gym and across the entire site</h5>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/GymRankings.png" id="img-shadow">
							</div>
							<h5>Rankings at each gym and for the entire site</h5>
							
							<div id="img-shadow-div">
								<img src="images/BoulderClimberHistogram.png" id="img-shadow">
							</div>
							<h5>View the global distribution of climbing ability.</h5>
						</div>
						
					</div>
					
					<div class="col-xs-12"><hr>
						<h3>LOCAL CLIMBING EVENTS</h3>
						<h5>Ever wanted to see local climbing events in your area?</h5>
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/AddEvent.png" id="img-shadow">
							</div>
							<h5>Easily add all the details of your gym's event.</h5>
							<div id="img-shadow-div">
								<img src="images/EventCalendar.png" id="img-shadow">
							</div>
							<h5>See Calendar of upcoming events.</h5>
						</div>
						<div class="col-xs-12 col-sm-6">
							<div id="img-shadow-div">
								<img src="images/ClimbingEventsFeed.png" id="img-shadow">
							</div>
							<h5>Find upcoming climbing events in your dashboard feed.</h5>
							
							<div id="img-shadow-div">
								<img src="images/EventPage.png" id="img-shadow">
							</div>
							<h5>Get all the details of the event and get out there!</h5>
						</div>
					</div>
					
			</div>
		
		
			

		</div>



<?php
}
?>







</div>
</div>



<?php require("php_common/footer.php"); ?>
</body>

<div class="modal fade" id="login-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Login</h3>
			</div>
			<form class="userlogin" method="post">
				<div class="modal-body">
					<label class="control-label" for="username">Username: </label>
					<input class="input form-control" type="text" name="username" autocorrect="off" autocapitalize="off" required="" id="logininput-modal">
					
					<label class="control-label" for="pass">Password: </label>
					<input class="input form-control" type="password" name="pass" required=""></td>
					
					<label><input type="checkbox" name="remember-me">Remember Me</label>
		
				</div>

				<div class="modal-footer">
					<a href="forgot.php">Forgot username or password?</a>
					<a class="btn btn-default" data-dismiss="modal">Cancel</a>
					<button type="submit" class="btn btn-success" name="loginsubmit">Login</button>
					
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="register-modal" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h3>Register</h3>
			</div>
			<form class="userlogin" method="post">
				<div class="modal-body">
					<label class="control-label" for="email">Email: </label>
					<input class="input form-control" type="email" name="email" autocorrect="off" autocapitalize="off" required="" id="registerinput-modal">
				
					<label class="control-label" for="username">Username: </label>
					<input class="input form-control" type="text" name="username" autocorrect="off" autocapitalize="off" required="">
					
					<label class="control-label" for="pass">Password: </label>
					<input class="input form-control" type="password" name="pass" required="">
					
					<label class="control-label" for="pass2">Confirm Password: </label>
					<input class="input form-control" type="password" name="pass2" required="">
		
				</div>

				<div class="modal-footer">
					<a class="btn btn-default" data-dismiss="modal">Cancel</a>
					<button type="submit" class="btn btn-success" name="registersubmit">Sign Up</button>
					
				</div>
			</form>
		</div>
	</div>
</div>



</html>
