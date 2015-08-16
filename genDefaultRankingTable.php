<?php 

function genRankingTable($climbType,$gym_options,$userid_req,$gymid) {

	//$climbType = {'boulder','TR','lead'}

	echo("<div class='form-inline'>
			<label for='".$climbType."'TimeFrame'>Choose Time Frame:</label>
			<select class='form-control' id='".$climbType."TimeFrame'
 				onchange=\"get".$climbType."RankingData('grade','".$climbType."',this.value,'all',{$gymid},'".$userid_req."')\">
				<option value='week' selected>Past Week</option>
				<option value='month'>Past Month</option>
				<option value='year'>Past Year</option>
				<option value='alltime'>All Time</option>
			</select>
		</div>");
	
	
	/*
	 * options for filtering ranking data
	echo('<div class="form-inline">
			<label for="'.$climbType.'TimeFrame">Choose Time Frame:</label>
			<select class="form-control" id="'.$climbType.'TimeFrame"
				onchange="get'.$climbType.'RankingData("grade","'.$climbType.'",this.value,
					document.getElementById("'.$climbType.'Gender").value),
					document.getElementById("'.$climbType.'SelectGym").value">
				<option value="week">Past Week</option>
				<option value="month" selected>Past Month</option>
				<option value="year">Past Year</option>
				<option value="alltime">All Time</option>
			</select>
			<label for="'.$climbType.'Gender">Gender:</label>
			<select class="form-control" id="'.$climbType.'Gender" 
				onchange="get'.$climbType.'RankingData("grade","'.$climbType.'",
					document.getElementById("'.$climbType.'TimeFrame").value,
					this.value),
					document.getElementById("'.$climbType.'SelectGym").value">
				<option value="male">Male</option>
				<option value="female">Female</option>
				<option value="all" selected>All</option>
			</select>
	
			<label for="gymid">Choose Gyms: </label>
			<select class="form-control" id="'.$climbType.'SelectGym" name="gymid"
					onchange="get'.$climbType.'RankingData("grade","'.$climbType.'",
					document.getElementById("'.$climbType.'TimeFrame").value,
					document.getElementById("'.$climbType.'Gender").value),
					this.value">'.$gym_options.'
	
			</select>
					
				
	
		</div>
	 * 
	 */
	 echo('
			<table class="table table-striped table-hover table-bordered btn-Lratings
			    rankings-table" id="'.$climbType.'RankingsTable">
						
			</table>');
}

?>