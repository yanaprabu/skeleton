<?php

class page3 {
	var $response;

	function forceError($locator) {
		echo "FORCE ERROR\n";
		return true;
	}
	
	function run($locator) {
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