<?php
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);
ini_set('log_errors', 'Off');
class example extends A_Controller_Action {

	function index($locator) {
		$this->_load()->response()->view();
	}

	function bar($locator) {
		$model = $this->_load()->model();
		$template = $this->_load('global')->template();
		$template->set('model', $model);
		$this->response->setRenderer($template);
	}

}
