<?php

class home {

	function run($locator) {
		$content = '
<html>
<body>
	<h2>Home Page Action</h2>
</body>
</html>
';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}
