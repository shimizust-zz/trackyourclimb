<?php

class DBConnectionManager {
	private $host = 'localhost';
	private $dbname = '';
	private $username = '';
	private $password = '';

	
	function __construct() {

	}
	
	public function connect() {

		$db = new PDO('mysql:host='.$this->host.';dbname='.$this->dbname,
		$this->username,$this->password,array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

		return $db;
	}
}

?>