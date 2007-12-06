<?php

class page2 {
	var $response;

	function denyAccess($locator) {
		echo "ACCESS CHECK\n";
		$forward = new A_DL('', 'security-custom', 'run');
		return $forward;
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page 2 - Action</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>