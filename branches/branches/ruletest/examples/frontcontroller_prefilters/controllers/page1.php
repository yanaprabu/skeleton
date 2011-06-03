<?php

class page1 {

	function denyAccess($locator) {
		echo "ACCESS CHECK\n";
		return true;
	}
	
	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Default Action</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>