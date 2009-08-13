<?php

class home extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	function index($locator) {
		$content = '
	This is the content for the home page.
	';
		$this->response->set('maincontent', $content);
	}

}