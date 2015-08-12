<?php 
include 'dbconnect.php';
//Generate list of states and corresponding gyms with links to their individual pages

$states = array(
    'AL'=>'Alabama',
    'AK'=>'Alaska',
    'AZ'=>'Arizona',
    'AR'=>'Arkansas',
    'CA'=>'California',
    'CO'=>'Colorado',
    'CT'=>'Connecticut',
    'DE'=>'Delaware',
    'DC'=>'District of Columbia',
    'FL'=>'Florida',
    'GA'=>'Georgia',
    'HI'=>'Hawaii',
    'ID'=>'Idaho',
    'IL'=>'Illinois',
    'IN'=>'Indiana',
    'IA'=>'Iowa',
    'KS'=>'Kansas',
    'KY'=>'Kentucky',
    'LA'=>'Louisiana',
    'ME'=>'Maine',
    'MD'=>'Maryland',
    'MA'=>'Massachusetts',
    'MI'=>'Michigan',
    'MN'=>'Minnesota',
    'MS'=>'Mississippi',
    'MO'=>'Missouri',
    'MT'=>'Montana',
    'NE'=>'Nebraska',
    'NV'=>'Nevada',
    'NH'=>'New Hampshire',
    'NJ'=>'New Jersey',
    'NM'=>'New Mexico',
    'NY'=>'New York',
    'NC'=>'North Carolina',
    'ND'=>'North Dakota',
    'OH'=>'Ohio',
    'OK'=>'Oklahoma',
    'OR'=>'Oregon',
    'PA'=>'Pennsylvania',
    'RI'=>'Rhode Island',
    'SC'=>'South Carolina',
    'SD'=>'South Dakota',
    'TN'=>'Tennessee',
    'TX'=>'Texas',
    'UT'=>'Utah',
    'VT'=>'Vermont',
    'VA'=>'Virginia',
    'WA'=>'Washington',
    'WV'=>'West Virginia',
    'WI'=>'Wisconsin',
    'WY'=>'Wyoming',
);

//The gym state is stored as the state abbreviation

//Find total number of gyms in database
$stmt = $db->prepare("SELECT gym_name,state,countryCode	 FROM gyms ORDER BY state ASC");
$stmt->execute();
$gym_result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$num_gyms = count($gym_result);
$num_entries = count($states)+$num_gyms;

$num_per_column = floor($num_entries/3);
echo $num_per_column;

$entry_ind = 1;

$state_list = "";
$curr_col_ind = 1;
$next_col_ind = 1;
while ($col_ind<=3) {
	//start a new column
	$state_list .= "<div class='col-xs-12 col-sm-4 col-md-4'><div class='col-sm-12'>";
	
	foreach ($states as $state) {
		//check if new column
		if ($next_col_ind==1) {
			$state_list .= "<div class='col-xs-12 col-sm-4 col-md-4'><div class='col-sm-12'>";
			$next_col_ind++;
		}
		
		if ($entry_ind > $num_per_column*$curr_col_ind && $curr_col_ind<3) {
			//if number of entries is over the number of entries per column, then make a new column
			$curr_col_ind++;
			$state_list .= "<div class='col-xs-12 col-sm-4 col-md-4'><div class='col-sm-12'>";
			
		}
	
		//make a headline for each state, and find how many gyms are in each state
		$state_list .= "<h4>".$state."</h4>";
		$entry_ind++;
		/*
		foreach ($gym_result as $gym_details) {
			print_r($gym_result);
		}*/
	}
	if ($entry_ind > $num_per_column*$col_ind) {
	
	}
	$col_ind++;
}
?>





































