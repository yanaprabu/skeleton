<?php

class example extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Base Action Controller</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific - foo.</a></li>
		<li><a href="?controller=example&action=bar">Default controller, specific - bar.</a></li>
		<li><a href="?controller=dispatch">Dispatch Action controller, no specified.</a></li>
		<li><a href="?controller=dispatch&action=foo">Dispatch Action controller, specific - foo.</a></li>
		<li><a href="?controller=dispatch&action=bar">Dispatch Action controller, specific - bar.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific specified.</a></li>
	</ol>
	<br/>
	<p><a href="../">Return to Examples</a></p>
';
		$model = $this->load()->model();
		$content .= '<br/>Model Object:<pre>' . print_r($model, 1) . '</pre>';

		$months = $this->load()->model('MonthsModel');
		$content .= '<br/>Model Months Object:<pre>' . print_r($months, 1) . '</pre>';

		$content .= '<br/>Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

	function foo($locator) {
		$this->flash()->set('foo', 'This is a flash var.');
		$this->load()->response()->template('', array('foo'=>'Set flash var.'));
	}

	function bar($locator) {
		$this->load()->helper('foo');
		$bar = $this->helper('foo')->bar('This is the arg for bar helper. ');
		$value = $this->flash()->get('foo');
		$this->load()->response()->template('', array('foo'=>$value, 'bar'=>$bar));
	}

}

