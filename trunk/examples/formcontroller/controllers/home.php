<?php

class home {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Action = Home</h2>
	
	<ol>
	<li><a href="?action=Form1">Action Form1</a></li>
	<li><a href="?action=DoesNotExist">Action does not exist - Error</a></li>
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