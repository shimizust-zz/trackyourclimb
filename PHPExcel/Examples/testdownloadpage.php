<?php

?>
<html>
<head>
<script>
window.onload = function() {
	document.getElementById('print-button').onclick = function() {

	   var iframe = document.createElement('iframe');
	   iframe.src = 'testwrite.php';
	   iframe.display = 'none';
	   document.body.appendChild(iframe);
		console.log("Click detected");
	};
};
</script>
</head>
<body>

Click to download <h2 id="print-button">here</h2>

</body>

</html>


