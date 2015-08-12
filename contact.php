<?php
if (isset($_POST['submit'])) {
	$to = 'shimizust@gmail.com';
	$from_name = $_POST['InputName'];
	$from_email = $_POST['InputEmail'];
	$subject = 'TrackYourClimb.com Message from '.$from_name;
	$message = $_POST['InputMessage'];
	$headers = 'From: '.$from_email."\r\n".'Reply-To: '.$from_email.
		"\r\n".'X-Mailer: PHP/'.phpversion();
	$mailsuccess = mail($to,$subject,$message,$headers);
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
		<link rel="stylesheet" type="text/css" href="css/mycss.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/uservoice.js"></script>

		<link rel="stylesheet" type="text/css" href="css/mycss.css">
	</head>

	<body>
		<div id="wrap">
		<div id="main">
			<?php include_once("analyticstracking.php") ?>
			<a href="index.php"><span class="glyphicon glyphicon-circle-arrow-left"></span> Return to main page.</a>
			<div class="container">
				<div class="row">
				  <div class="col-md-12">
				    <h1 class="page-header">Contact</h1>
				    <p class="lead-text">Use this form to send your comments, suggestions, enquiries or bug reports.</p>
					<?php 
					if (isset($_POST['submit']) && $mailsuccess) {
						echo '<div class="alert alert-success"><strong><span class="glyphicon glyphicon-send"></span> Success! Message sent. </strong></div>';
					}
					?>
					
				    
				  </div>
				  <form role="form" action="" method="post" >
				    <div class="col-lg-6">
				      <div class="well well-sm"><strong><i class="glyphicon glyphicon-ok form-control-feedback"></i> Required Field</strong></div>
				      <div class="form-group">
				        <label for="InputName">Your Name</label>
				        <div class="input-group">
				          <input type="text" class="form-control" name="InputName" id="InputName" placeholder="Enter Name" required>
				          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
				      </div>
				      <div class="form-group">
				        <label for="InputEmail">Your Email</label>
				        <div class="input-group">
				          <input type="email" class="form-control" id="InputEmail" name="InputEmail" placeholder="Enter Email" required  >
				          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
				      </div>
				      <div class="form-group">
				        <label for="InputMessage">Message</label>
				        <div class="input-group"
				>
				          <textarea name="InputMessage" id="InputMessage" class="form-control" rows="5" required></textarea>
				          <span class="input-group-addon"><i class="glyphicon glyphicon-ok form-control-feedback"></i></span></div>
				      </div>
				      
				      <input type="submit" name="submit" id="submit" value="Submit" class="btn btn-info pull-right">
				    </div>
				  </form>
				  <hr class="featurette-divider hidden-lg">
				  
				</div>

</div>
			
			</div>
		</div>
		
		
		
		<?php require("footer.php"); ?>
	</body>
</html>