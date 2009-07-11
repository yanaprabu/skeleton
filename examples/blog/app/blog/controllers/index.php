<?php

class index extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	/* Default action. Shows latest articles */
	function index($locator) {
		
		$this->response->set('maincontent', 'This is the maincontent for the blog index');
		$this->response->set('subcontent', 'This is the subcontent for the blog index');

	}

}