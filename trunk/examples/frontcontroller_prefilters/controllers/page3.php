<?php

class page3 {
	protected $response;

	function forceError($locator) {
		echo "FORCE ERROR\n";
		return true;
	}
	
	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page 3 - Action</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>