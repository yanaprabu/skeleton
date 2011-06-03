<?php

class error {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Action = Error</h2>
	<a href="?action=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>