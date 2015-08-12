<?php
//Find out user's gym

include 'getUserGym.php';

?>
<div>

			<nav class = "navbar navbar-default" >
				<div class = "container-fluid">

					<a class="navbar-brand" href="main.php"><img src="images/Logo-whitebkgd-xs.png"></a>
					<ul class="nav navbar-nav navbar-left">
						

					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Workouts <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="workout-input.php">Log Workout</a></li>
								<li><a href="past-workouts.php">Past Workouts</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Stats <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="mystats.php">My Stats</a></li>
								<li><a href="sitestats.php">Community Stats</a></li>
								<li><a href="rankings.php">Rankings</a></li>
								<?php 
								if (is_null($main_gymid)==false) {
								?>
								<li><a href="gympage.php?gymid=<?php echo $main_gymid ?>">My Gym Stats</a></li>
								<?php } ?>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Gyms and Crags <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="add-gym.php">Add New Gym/Crag...</a></li>
								<li><a href="gyms.php">View Gyms/Crags</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Events <b class="caret"></b></a>
							<ul class="dropdown-menu">
								<li><a href="eventcalendar.php">Event Calendar</a></li>
								<li><a href="add-event.php">Add an Event</a></li>
							</ul>
						</li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Me <b class="caret"></b></a>
							<ul class="dropdown-menu">	
								<li><a href="main.php">My Dashboard</a></li>
								<li><a href="userprofile.php">My Profile</a></li>
								<li><a href="setworkoutprefs.php">Workout Preferences</a></li>
								<?php 
									//extract own username
									$stmt = $db->prepare("SELECT username FROM users WHERE userid = ?");
									$stmt->execute(array($userid));
									$username_result = $stmt->fetch(PDO::FETCH_ASSOC);
									$username = $username_result['username']; 
								?>
								<li><a href="publicprofile.php?username=<?php echo $username ?>">View Public Profile</a></li>
								<li class="divider"></li>
								<li><a href="logout.php">Sign Out</a></li>
							</ul>
						</li>
					</ul>

				</div>

			</nav>
		</div>