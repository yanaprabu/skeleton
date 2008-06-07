<?php
include_once 'A_Template.php';

class error extends A_Controller_Action {
	var $content;
	var $template_main;
	function __construct($locator){
		parent::__construct($locator);
		$this->usersession = $locator->get('UserSession');
		$this->response = $locator->get('Response');
	}
/*
	function error($locator) {
		
	}
*/	
	function run($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$page_template = new A_Template_Strreplace('templates/error.html');
		
		$response->setContent($page_template->render());
	}
}

?>