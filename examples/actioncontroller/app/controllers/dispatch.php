<?php
include_once 'A/Controller/Action/Dispatch.php';

class dispatch extends A_Controller_Action_Dispatch {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Dispatch Action Controller</h2>
	<ol>
		<li><a href="?controller=example">Default controller, no action specified.</a></li>
		<li><a href="?controller=example&action=foo">Default controller, specific action - foo.</a></li>
		<li><a href="?controller=example&action=bar">Default controller, specific action - bar.</a></li>
		<li><a href="?controller=dispatch">Dispatch Action controller, no action specified.</a></li>
		<li><a href="?controller=dispatch&action=foo">Dispatch Action controller, specific action - foo.</a></li>
		<li><a href="?controller=dispatch&action=bar">Dispatch Action controller, specific action - bar.</a></li>
		<li><a href="?module=module1&controller=example">Module and controller, no action specified.</a></li>
		<li><a href="?module=module1&controller=example&action=bar">Module and controller, specific action specified.</a></li>
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
		$select = $this->load()->helper('A_Html_Form_Select');
dump($select, 'SELECT=');
		$this->flash()->set('foo', 'This is a flash var.');
		$this->load()->response()->template('', array('foo'=>'Set flash var.'));
	}

	function bar($locator) {
		$value = $this->flash()->get('foo');
		$this->load()->response()->template('', array('foo'=>$value));
	}

	function preDispatch() {
		echo "preDispatch called.<br/>";
	}

	function postDispatch() {
		echo "postDispatch called.<br/>";
	}

}

