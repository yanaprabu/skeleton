<?php

class example extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function index($locator) {
	
		$maincontent = 'this is the maincontent';
		$rightcol = 'this is the rightcol';

		$this->response->set('maincontent', $maincontent);
		$this->response->set('rightcol', $rightcol);
	}

}

