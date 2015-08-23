<?php

function convertArrayToTable($array) {
	//just create the rows of the actual table body (no headers)
	$table = "";
	foreach ($array as $row) {
		$table .= "<tr>";
		

		for ($i=0;$i<=8;$i++) {
			//Need to iterate directly or else it will skip over empty cells in $array
			$element = $row[$i];
			$table .= "<td>{$element}</td>";
		}
		$table .= "</tr>";
	}

	return $table;
}


?>
