<?php
abstract class API
{
	//from coreymaynard.com/blog/creating-a-restful-api-with-php/
	/* 	
	Property: method
	The HTTP method this request was made in, either GET, POST, PUT or DELETE
	*/
	protected $method='';
	
	/*
	Property: endpoint
	The Model requested in the URI. eg: /files
	*/
	protected $endpoint='';
	
	
	/*
	Property: args
	Any additional URI components after the endpoint and verb have been removed. eg: /<endpoint>/<verb>/<arg0>/<arg1>
	*/
	protected $args = Array();
	
	/*
	Property: file
	Stores the input of the PUT request
	*/
	protected $file = Null;
	
	/*
	Constructor: __construct
	Allow for CORS, assemble and pre-process the data
	*/
	public function __construct($request) {
		//$request will be like 'users/user/1'
		
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: *");
		header("Content-Type: application/json");
		

		$this->args = explode('/',rtrim($request,'/'));
		$this->endpoint = array_shift($this->args); //remove first element of array and return it, shift rest of elements down in the array
		
		$this->method = $_SERVER['REQUEST_METHOD'];
		if ($this->method=='POST' && array_key_exists('HTTP_X_HTTP_METHOD',$_SERVER)) {
			if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
				$this->method = 'DELETE';
			} else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
				$this->method = 'PUT';
			} else {
				throw new Exception("Unexpected Header");
			}
		}
		
		switch($this->method) {
			case 'DELETE':
				
			case 'POST':
				$this->request = $this->_cleanInputs($_POST);
				break;
			case 'GET':
				$this->request = $this->_cleanInputs($_GET);
				break;
			case 'PUT':
				$this->request = $this->_cleanInputs($_GET);
				$this->file = file_get_contents("php://input");
				break;
			default:
				$this->_response('Invalid Method',405);
				break;
		}
	}
		
	public function processAPI() {
		if ((int)method_exists($this,$this->endpoint) > 0) {
			//in the concrete API class, you'll define the methods for the endpoints
			return $this->_response($this->{$this->endpoint}($this->args));
		}
		return $this->_response("No Endpoint: $this->endpoint", 404);
	}
	
	private function _response($data, $status=200) {
		//puts data in json format
		header("HTTP/1.1 " . $status . " " . $this->_requestStatus($status));
		header("Content-Type: application/json");
		return json_encode($data);
	}
	
	private function _cleanInputs($input) {
		$clean_input = Array();
		if (is_array($input)) {
			foreach ($input as $k => $v) {
				$clean_input[$k] = $this->_cleanInputs($v);
			}
		} else {
			//remove HTML and PHP tags from a string
			$clean_input = trim(strip_tags($input));
		}
		return $clean_input;
	}

	private function _requestStatus($code) {
		$status = array(  
			200 => 'OK',
			404 => 'Not Found',   
			405 => 'Method Not Allowed',
			500 => 'Internal Server Error',
		); 
		return ($status[$code])?$status[$code]:$status[500]; 
	}
	
	
}

?>