<?php

class index extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		$this->load()->response()->view();
	//	$this->response->set('layout','adminlayout');
	//	$this->response->set('maincontent','The maincontent for the admin index');
	//	$this->response->set('subcontent','This is the subcontent of the admin index');
		//	$this->response->setContent('hello world');
	}

}