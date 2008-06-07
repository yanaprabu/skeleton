<?php

class error {

	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Error</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>