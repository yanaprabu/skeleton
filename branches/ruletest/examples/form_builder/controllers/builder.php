<?php
class builder extends A_Controller_App {
	
	function index ($locator) {
		$template = $this->_load('controller')->template('index');
		$this->_response()->set('content', $template);
	}

}