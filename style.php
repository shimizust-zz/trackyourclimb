<?php

require "vendor/leafo/scssphp/scss.inc.php";
$directory = "css/scss";

//serve css file directly
scss_server::serveFrom($directory);

/*
//Compile the file and save to css/mycss.css when hit localhost/trackyourclimb/style.php/css/scss/mycss.scss
$scss = new scssc();
$scss->setFormatter('scss_formatter');
$scssIn = file_get_contents('css/scss/mycss.scss');
$cssOut = $scss->compile($scssIn);
file_put_contents('css/mycss.css',$cssOut);
*/
