<?php

abstract class AbstractDAO {

	protected static function convertToAssocArray($dbKeys, $propVals) {
		/*
		 * $propVals is a non-associative array with values corresponding
		 * to the entries in $dbKeys
		 * $dbKeys is an array of strings corresponding to the column values
		 * in a database table. 
		 *  
		 * Ouptut: [$dbKeys[0]=>$propVals[0], $dbKeys[1]=>$propVals[1], ...]
		 */
		$propKeyVals = array();
		foreach ($dbKeys as $i=>$key) {
			$propKeyVals[$key] = $propVals[$i];
		}
		return $propKeyVals;
	}
}