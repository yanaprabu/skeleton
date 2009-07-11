<?php

class home extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	function index($locator) {
		$content = '
	<html>
	<body>
		<h2>Home Page Action</h2>
	</body>
	</html>
	';
		$this->response->setContent($content);
	}

}