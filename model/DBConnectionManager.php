<?php

class DBConnectionManager {
	private $host = 'localhost';
	private $dbname = '';
	private $username = '';
	private $password = '';

	
	function __construct() {

	}
	
	public function connect() {
		
		//Parse site configuration file into array
		$siteprop_array = parse_ini_file(realpath(dirname(__FILE__)."/../siteproperties.ini"));
		
		//connect to database
		error_reporting( E_ALL ^ ( E_NOTICE | E_WARNING | E_DEPRECATED ) );
		
		
		//there's an error here when including the charset, figure out later
		$db = new PDO($siteprop_array["db_driver"].':host='.$siteprop_array["db_host"].';dbname='.$siteprop_array["db_name"],
				$siteprop_array["db_username"],$siteprop_array["db_password"],
				array(PDO::ATTR_EMULATE_PREPARES => false,
						PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		
		return $db;
	}
}

?>