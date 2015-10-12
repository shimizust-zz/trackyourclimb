<?php

class Autoloader {
	public static function loader($className) {
		$path = '../model/';
		
		include $path.$className.'.php';
	}
}