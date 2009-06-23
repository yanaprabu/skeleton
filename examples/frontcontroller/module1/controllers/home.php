<?php

class home {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Module1 Home</h2>
	
	<ol>
	<li><a href="?module=module1">No Page Given - Default (here)</a></li>
	<li><a href="?module=module1&controller=page">Go to a Page - Default Action</a></li>
	<li><a href="?module=module1&controller=page&action=example">Go to a Page - Specify Action</a></li>
	<li><a href="?module=module1&controller=DoesNotExist">Page does not exist - Error</a></li>
	<li><a href="?controller=home">Return Home</a></li>
	</ol>
	<a href="../">Return to Examples</a>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}

?>