<?php

class page {
	protected $response;

	function page($locator) {
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Default Action</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$this->response->setContent($content);
	}

	function example($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Specific Action</h2>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$this->response->setContent($content);
	}

}

?>