<?php

class home {

	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Home</h2>
	
	<ol>
	<li><a href="?">No Page Given - Default (here)</a></li>
	<li><a href="?controller=page">Go to a Page - Default Action</a></li>
	<li><a href="?controller=page&action=example">Go to a Page - Specify Action</a></li>
	<li><a href="?controller=DoesNotExist">Page does not exist - Error</a></li>
	<li><a href="?module=module1&controller=home">Go to a Module</a></li>
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