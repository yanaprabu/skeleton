<?php
include_once 'A_Template.php';

class error {
	var $content;
	var $template_main;

	function error($locator) {
	}
	
	function run($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$page_template = new A_Template_Strreplace('template/error.html');
		
		$response->setContent($page_template->render());
	}
}

?>