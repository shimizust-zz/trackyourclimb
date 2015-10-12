<?php

//TODO: Replace with phpunit
include "../core/bootstrap.php";

$CDAO = new ClimbingAreaDAO();
$areaid = 7;
$indoor = 1;
var_dump($CDAO->climbingAreaExists($areaid, $indoor));


$UserDAO = new UserDAO();
echo $UserDAO->getNumUsers();

var_dump($UserDAO->getUserPrefs(954));
var_dump($UserDAO->setUserPrefs(954,array("show_boulder"=>1, "minL"=>3)));
var_dump($UserDAO->getUserPrefs(954));

var_dump($UserDAO->getUserProfile(954));
var_dump($UserDAO->getUserRecords(954));

?>