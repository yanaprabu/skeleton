<?php

class index extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	function index($locator) {
		$content = '
	This is the content for the Application index page.
	';
		$this->response->set('maincontent', $content);
	}

}