<?php

class home extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	function index($locator) {
		$content = '
	<html>
	<body>
		<h2>Home Page Action</h2>
	</body>
	</html>
	';
		$response = $locator->get('Response');
		$response->setContent($content);
	}

}