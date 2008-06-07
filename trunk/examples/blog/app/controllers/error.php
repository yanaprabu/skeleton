<?php

class error  extends A_Controller_Action {

	var $response;
	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function run($locator) {
		$maincontent = '<h2>Front Controller: Error</h2>';
		$subcontent = 'subcontent error page';
		
		$this->response->set('maincontent', $maincontent);
		$this->response->set('subcontent', $subcontent);
	}

}

?>