<?php
//connect to database
include 'dbconnect.php';

//check that user has a valid cookie, redirect if no valid cookie
include 'php_common/cookiecheck.php';	

//extract all workouts by a user
$stmt4 = $db->prepare("SELECT * FROM workouts LEFT OUTER JOIN 
	workout_segments ON workouts.workout_id = workout_segments.workout_id INNER JOIN gyms ON workouts.gymid = gyms.gymid
	WHERE workouts.userid = :userid ORDER BY 
	date_workout DESC, workouts.workout_id DESC, climb_type ASC, 
	grade_index ASC"); 
$stmt4->execute(array(':userid'=>$userid));
$allworkouts = $stmt4->fetchAll(PDO::FETCH_ASSOC);

include 'BoulderRouteGradingSystems.php';
include 'php_dataquery/getUserGradingPrefs.php';

/** PHPExcel_IOFactory */
include 'vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();
$objWorksheet = $objPHPExcel->getActiveSheet();
$objWorksheet->setTitle('TrackYourClimb Workouts');

//$previd = $allworkouts[0]['workout_id'];
$prev_id = -1;
$i = 3; //current row number in excel
$w_id = 0; //current workout id number
$b_ind = 3; //boulder index
$t_ind = 3; //top rope index
$l_ind = 3; //lead index

//set column headers
$objWorksheet->getCell('A2')->setValue('ID Number');
$objWorksheet->getCell('B2')->setValue('Date');
$objWorksheet->getCell('C2')->setValue('Gym');
$objWorksheet->getCell('D2')->setValue('Boulder Notes');
$objWorksheet->getCell('E2')->setValue('Top-Rope Notes');
$objWorksheet->getCell('F2')->setValue('Lead Notes');
$objWorksheet->getCell('G2')->setValue('Other Notes');
$objWorksheet->getCell('I1')->setValue('Boulder');
$objWorksheet->getCell('L1')->setValue('Top-Rope');
$objWorksheet->getCell('O1')->setValue('Lead');
$objWorksheet->getCell('H2')->setValue('Grade');
$objWorksheet->getCell('I2')->setValue('Ascent Type');
$objWorksheet->getCell('J2')->setValue('Reps');
$objWorksheet->getCell('K2')->setValue('Grade');
$objWorksheet->getCell('L2')->setValue('Ascent Type');
$objWorksheet->getCell('M2')->setValue('Reps');
$objWorksheet->getCell('N2')->setValue('Grade');
$objWorksheet->getCell('O2')->setValue('Ascent Type');
$objWorksheet->getCell('P2')->setValue('Reps');

$style = array(
	'borders' => array(
		'top' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN
			)
		)
	);
foreach($allworkouts as $row) {
	$curr_id = $row['workout_id'];
	
	if ($curr_id != $prev_id) {
		$w_id ++; //new workout id number
		$i = max($b_ind,$t_ind,$l_ind);
		$b_ind = $i;
		$t_ind = $i;
		$l_ind = $i;
		
		$prev_id = $curr_id;
		
		$objWorksheet->setCellValueByColumnAndRow(0,$i,$w_id);
		$objWorksheet->setCellValueByColumnAndRow(1,$i,$row['date_workout']);
		$objWorksheet->setCellValueByColumnAndRow(2,$i,$row['gym_name']);
		$objWorksheet->setCellValueByColumnAndRow(3,$i,$row['boulder_notes']);
		$objWorksheet->setCellValueByColumnAndRow(4,$i,$row['tr_notes']);
		$objWorksheet->setCellValueByColumnAndRow(5,$i,$row['lead_notes']);
		$objWorksheet->setCellValueByColumnAndRow(6,$i,$row['other_notes']);

		//set border on top
		$leftCell = 'A'.$i;
		$rightCell = 'P'.$i;
		$cellRange = $leftCell.':'.$rightCell;
		$objWorksheet->getStyle($cellRange)->applyFromArray($style);
	}
	else {
		//part of same workout, don't list general details of workout
		
	}
	
	if ($row['ascent_type']=='project') {
		$ascent_type_str = 'attempt';
	}
	else {
		$ascent_type_str = $row['ascent_type'];
	}
	
	if ($row['climb_type'] == 'boulder') {
		$objWorksheet->setCellValueByColumnAndRow(7,$b_ind,$boulderConversionTable[$boulderGradingID][$row['grade_index']]); 
		$objWorksheet->setCellValueByColumnAndRow(8,$b_ind,$ascent_type_str);
		$objWorksheet->setCellValueByColumnAndRow(9,$b_ind,$row['reps']);
		$b_ind++;
	}
	elseif ($row['climb_type'] == 'toprope') {
		$objWorksheet->setCellValueByColumnAndRow(10,$t_ind,$routeConversionTable[$routeGradingID][$row['grade_index']]); 
		$objWorksheet->setCellValueByColumnAndRow(11,$t_ind,$ascent_type_str);
		$objWorksheet->setCellValueByColumnAndRow(12,$t_ind,$row['reps']);
		$t_ind++;
	}
	elseif ($row['climb_type'] == 'lead') {
		$objWorksheet->setCellValueByColumnAndRow(13,$l_ind,$routeConversionTable[$routeGradingID][$row['grade_index']]); 
		$objWorksheet->setCellValueByColumnAndRow(14,$l_ind,$ascent_type_str);
		$objWorksheet->setCellValueByColumnAndRow(15,$l_ind,$row['reps']);
		$l_ind++;
	}
	else {
		//NULL climbing value
		$b_ind++; 
		$t_ind++;
		$l_ind++;
	}

}



$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");


header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"TrackYourClimb_workouts.xls\"");
//header("Cache-Control: max-age=0");
$objWriter->save('php://output');

//Make sure no blank lines before or after the php markers or else it screws up the binary file.

?>