<?php
function cleanURL($URL) {
	return preg_replace('/^(?!https?:\/\/)/', 'http://', $URL);
}

?>
