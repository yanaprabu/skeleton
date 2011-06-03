<?php

class error {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Module1 Error</h2>
	<p><a href="?module=module1&controller=home">Module Home</a></p>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}
