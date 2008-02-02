<?php

class index extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		$this->response->set('layout','articlelayout');
		$this->response->set('maincontent','The maincontent for the blog index');
		$this->response->set('subcontent','This is the subcontent');
		//	$this->response->setContent('hello world');
	}

}