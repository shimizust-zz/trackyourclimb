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
}