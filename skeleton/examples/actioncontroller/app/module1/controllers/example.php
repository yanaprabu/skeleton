<?php

class example extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Action Controller: Page - Module1 Default Action</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action specified.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
';
		$model = $this->load()->model();
		$content .= '<br/>Model Object:<pre>' . print_r($model, 1) . '</pre>';

		$days = $this->load()->model('Days');
		$content .= '<br/>Model Days Object:<pre>' . print_r($days, 1) . '</pre>';

		$months = $this->load('global')->model('MonthsModel');
		$content .= '<br/>Global Model Months Object:<pre>' . print_r($months, 1) . '</pre>';

		$content .= 'Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

	function bar($locator) {
		$content = '
<html>
<body>
	<h2>Action Controller: Page - Module1 Specific Action</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action specified.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
';
		$content .= 'Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

}

?>