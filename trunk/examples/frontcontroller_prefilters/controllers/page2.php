<?php

class page2 {
	protected $response;

	function denyAccess($locator) {
		echo "ACCESS CHECK\n";
		$forward = array('', 'security-custom', 'run');
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