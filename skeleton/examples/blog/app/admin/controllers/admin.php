<?php

class admin extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}

	function run($locator) { 
		$this->response->set('layout', 'adminlayout');
	}

}