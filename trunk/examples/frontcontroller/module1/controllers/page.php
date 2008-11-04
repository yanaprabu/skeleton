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
	<h2>Front Controller: Page - Module1 Default Action</h2>
	<p><a href="?module=module1&controller=home">Module Home</a></p>
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
	<h2>Front Controller: Page - Module1 Specific Action</h2>
	<p><a href="?module=module1&controller=home">Module Home</a></p>
	<a href="?controller=home">Return Home</a>
</body>
</html>
';
		$this->response->setContent($content);
	}

}

?>