<?php
class home extends A_Controller_Action {
	
	function index ($locator) {
		$template = $this->_load()->template('index');
		$this->_response()->set('content', $template);
	}

}