<?php

class error {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Error</h2>
	<a href="?controller=example">Return</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>