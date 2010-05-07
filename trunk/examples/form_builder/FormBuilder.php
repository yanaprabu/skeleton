<?php
class FormBuilder extends A_Controller_Action {
	function index ($locator) {
		$template = $this->_load()->template('foo');
		$this->_response()->set('content', $template);
	}
}