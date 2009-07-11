<?php
include_once 'A/Controller/Action/Dispatch.php';

class dispatch extends A_Controller_Action_Dispatch {

	function index($locator) {
		$content = '
<html>
<body>
	<h2>Front Controller: Page - Dispatch Action Controller</h2>
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
		$model = $this->_load()->model();
		$content .= '<br/>Model Object:<pre>' . print_r($model, 1) . '</pre>';

		$months = $this->_load()->model('MonthsModel');
		$content .= '<br/>Model Months Object:<pre>' . print_r($months, 1) . '</pre>';

		$content .= '<br/>Action Object:<pre>' . print_r($this, 1) . '</pre>';
		$content .= '
</body>
</html>
';
		$this->response->setContent($content);
	}

	function foo($locator) {
		$this->_flash()->set('foo', 'This is a flash var.');
		$this->_load()->response()->template('', array('foo'=>'Set flash var.'));
	}

	function bar($locator) {
		$value = $this->_flash()->get('foo');
		$this->_load()->response()->template('', array('foo'=>$value));
	}

	function _preDispatch() {
		echo "<!--preDispatch called.-->\n";
	}

	function _postDispatch() {
		echo "<!--postDispatch called.-->\n";
	}

}

