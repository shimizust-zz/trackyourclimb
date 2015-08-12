<?php
function cleanURL($URL) {
	
	if(strpos($URL, "http://")!== false || strpos($URL, "https://")!== false) $URL = $URL;
	else $URL = "http://".$URL;
	
	
	return $URL;

}

?>