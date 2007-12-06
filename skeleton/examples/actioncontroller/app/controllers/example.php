<?php

class example extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Default Action</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action specified.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
';
		$content .= '<br/>Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

	function foo($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Specific Action</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action specified.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
';
		$content .= '<br/>Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

}

