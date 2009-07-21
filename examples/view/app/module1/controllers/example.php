<?php

class example extends A_Controller_Action {

	function index($locator) {
		$this->_load()->response()->view();
	}

	function bar($locator) {
		$model = $this->_load()->model();
		$template = $this->_load()->template();
		$template->set('model', $model);
		$this->response->setRenderer($template);
	}

}
