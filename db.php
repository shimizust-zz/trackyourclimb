<?php

class DB {
	
	public static function getDBConnection() {
		//Parse site configuration file into array
		$siteprop_array = parse_ini_file("siteproperties.ini");
		
		//connect to database
		error_reporting( E_ALL ^ ( E_NOTICE | E_WARNING | E_DEPRECATED ) );
		
		//Remove this mysql_connect statement once all outdated queries are removed.
		mysql_connect($siteprop_array["db_host"],$siteprop_array["db_username"],$siteprop_array["db_password"]) or die(mysql_error());
		mysql_select_db($siteprop_array["db_name"]) or die(mysql_error());
		
		
		//there's an error here when including the charset, figure out later
		$db = new PDO($siteprop_array["db_driver"].':host='.$siteprop_array["db_host"].';dbname='.$siteprop_array["db_name"],
				$siteprop_array["db_username"],$siteprop_array["db_password"],
				array(PDO::ATTR_EMULATE_PREPARES => false,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		return $db;
	}
}