<?php
/** Include path **/

/** PHPExcel_IOFactory */
include '../Classes/PHPExcel/IOFactory.php';

$objPHPExcel = new PHPExcel();
$objPHPExcel->getActiveSheet()->setTitle('TrackYourClimb Workouts');
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel5");

//$objWriter->save('filename.xls');

header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
header("Content-Disposition: attachment; filename=\"results.xls\"");
//header("Cache-Control: max-age=0");
$objWriter->save('php://output');

//Make sure no blank lines before or after the php markers or else it screws up the binary file.

header('Location: index.php');
header('Location: index.php');
?>



