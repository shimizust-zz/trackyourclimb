<?php
/*
require_once('autoloader.php');

spl_autoload_register('Autoloader::model');
spl_autoload_register('Autoloader::service');
//register others here
*/

$includePaths = array();
$includePaths[] = __DIR__ . '/../model';
$includePaths[] = __DIR__ . '/../service';

spl_autoload_register(function ($class) use ($includePaths) {
	foreach ($includePaths as $path) {
		$file = $path . '/' . $class . '.php';
		if (is_file($file)) {
			include($file);
		}
	}
});
