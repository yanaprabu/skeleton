<?php

class example extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
	
		$maincontent = 'this is the maincontent';
		$rightcol = 'this is the rightcol';

		$this->response->set('maincontent', $maincontent);
		$this->response->set('rightcol', $rightcol);
	}

}

