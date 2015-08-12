<?php

require "vendor/leafo/scssphp/scss.inc.php";
$scss = new scssc();

/*
//Example using the compiler directly
echo $scss->compile('
	$color: #abc;
	div {color: lighten($color,20%); }
');
*/

/*
//Example compiling a specific scss file

$scss->setImportPaths("css/scss/");
echo $scss->compile('@import "testcss.scss"');
*/

//Set directory to find the scss file
//Compile the scss file by going to localhost/climbtracker/
/*
$server = new scss_server($directory,null,$scss);
$server->serve();
*/

$directory = "css/scss";
$scss->setFormatter('scss_formatter');
$scssIn = file_get_contents('css/scss/mycss.scss');
$cssOut = $scss->compile($scssIn);
file_put_contents('css/mycss.css',$cssOut);



//scss_server::serveFrom($directory);