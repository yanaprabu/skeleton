<?php

class security_default {

	function run($locator) {
		$response = $locator->get('Response');

		$content = '
<html>
<body>
	<h2>Front Controller: Page - Default Security Action</h2>
	<p>You are seeing this page because this controller has denied access but has not forwarded, so the Front Controller forwards a specified by this pre-method. </p>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response->setContent($content);
	}

}

?>