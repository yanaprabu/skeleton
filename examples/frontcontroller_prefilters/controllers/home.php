<?php

class home {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Home</h2>
	In these examples the Front Controller is calling methods or setting properties in the Action Controller before dispatch using addInjector().  
	<ol>
	<li><a href="?">No Page Given - Default (here)</a></li>
	<li><a href="?controller=page1">Controller with pre method - Default Security Action</a></li>
	<li><a href="?controller=page2">Controller with pre method - Custom Security Action</a></li>
	<li><a href="?controller=page3">Controller with pre method - Action with no forward</a></li>
	<li><a href="?controller=page4">Controller with pre filter - Inject Response object in Action by setting property</a></li>
	<li><a href="?controller=DoesNotExist">Page does not exist - Error</a></li>
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