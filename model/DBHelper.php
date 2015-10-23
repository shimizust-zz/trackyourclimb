<?php

include '../core/bootstrap.php';

class DBHelper {
	
	
	public static function genPrepareString($propValArray) {
		//The property name has already been verified, so no need to use
		//placeholders for these, just the property values
	
		//Input: an array like: array("column1","column2",...)
		//Ouput: a String like: "column1=:column1,column2=:column2,..."
		$prepString = "";
		foreach ($propValArray as $key => $val) {
			$prepString .= $key."=".":".$key.",";
		}
		return rtrim($prepString, ","); //remove trailing comma
	}
	
	public static function genExecuteArray($propValArray) {
		/*
		 * Input: an associative array of properties: array("prop1"=>val1,"prop2"=>val2,...)
		 * 
		 * Output: array(":prop1"=>val1, ":prop2"=>val2, ...)
		 */
		
		$executeArray = array();
		foreach ($propValArray as $key => $val) {
			$executeArray[':'.$key] = $val;
		}
		return $executeArray;
	}
	
	public static function genFieldList($propKeys) {
		/*
		 * Return a comma-delimited string of property values in $propValArray
		 * Input: $propKeys = array("prop1","prop2",...)
		 * Output: "prop1,prop2,..."
		 */
		return implode(",", $propKeys);
	}
	
	public static function genPlaceholderList($propKeys) {
		/*
		 * Input: $propKeys = array("prop1","prop2",...)
		 * Output: ":prop1,:prop2,..."
		 */
		$placeholderList = $propKeys;
		//Use '&' to actually modify the array
		array_walk($placeholderList, function(&$value, $key) {
			$value = ":".$value;
		});
		return implode(",", $placeholderList);
	}
	
	public static function performUpdateQuery($db, $table, $propValArray, $selectKeyValue) {
		/*
		 * Input:
		 * $db = a database connection
		 * $table = the name of the table to update row 
		 * $propValArray = array("prop1"=>val1,"prop2"=>val2,...)
		 * Update an existing row into the specified table with the 
		 * corresponding values
		 * $selectKeyValue = [0=>key, 1=>value]
		 */
		
		$selectKey = $selectKeyValue[0];
		$selectValue = $selectKeyValue[1];
		$prepareString = self::genPrepareString($propValArray);
		$stmt = $db->prepare("UPDATE ".$table." SET ".$prepareString.
				" WHERE ".$selectKey."=:".$selectKey);
		
		$executeArray = self::genExecuteArray($propValArray);
		$executeArray[":".$selectKey] = $selectValue;
		
		return $stmt->execute($executeArray);

	}
	
	public static function performInsertQuery($db, $table, $propValArray) {
		/*
		 * Input:
		 * $db = a database connection
		 * $table = the name of the table to insert row into 
		 * $propValArray = array("prop1"=>val1,"prop2"=>val2,...)
		 * Insert a new row into the specified table with the corresponding values
		 */
		$propKeys = array_keys($propValArray);
		$fieldList = self::genFieldList($propKeys);
		$placeholderList = self::genPlaceholderList($propKeys);
		
		$stmt = $db->prepare("INSERT INTO ".$table." (".
				$fieldList.") VALUES (".$placeholderList.")");

		$result = $stmt->execute(DBHelper::genExecuteArray($propValArray));
		return ["result"=>$result, "insertID"=>$db->lastInsertId()];
	}
	
	public static function performSimpleSelectQuery($db, $table, $selectKeyValue) {
		/*
		 * Input:
		 * $db = a database connection
		 * $table = the name of the table to update row 
		 * $selectKeyValue = [0=>key, 1=>value]
		 */
		$selectKey = $selectKeyValue[0];
		$selectValue = $selectKeyValue[1];
		$stmt = $db->prepare("SELECT * FROM ".$table." WHERE ".
				$selectKey."=:".$selectKey);
		$stmt->execute(array(":".$selectKey=>$selectValue));
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
}



















