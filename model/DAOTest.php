<?php

//TODO: Replace with phpunit
require_once('ClimbingAreaDAO.php');

$CDAO = new ClimbingAreaDAO();
$areaid = 7;
$indoor = 0;
var_dump($CDAO->climbingAreaExists($areaid, $indoor));


require_once('UserDAO.php');
$UserDAO = new UserDAO();
echo $UserDAO->getNumUsers();

var_dump($UserDAO->getUserPrefs(954));
var_dump($UserDAO->setUserPrefs(954,array("show_boulder"=>1, "minL"=>3)));
var_dump($UserDAO->getUserPrefs(954));

var_dump($UserDAO->getUserProfile(954));
var_dump($UserDAO->getUserRecords(954));

?>