<?php

class Autoloader {
	public static function model($className) {
		$path = '../model/';
		
		include $path.$className.'.php';
	}
	
	public static function service($className) {
		$path = '../service/';
	
		include $path.$className.'.php';
	}
}