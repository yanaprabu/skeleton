<?php

class security_custom {

	function run($locator) {
		$response = $locator->get('Response');
	
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Custom Security Action</h2>
	<p>You are seeing this page because a controller has denied access and has forwarded to a custom controller. </p>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response->setContent($content);
	}

}

?>