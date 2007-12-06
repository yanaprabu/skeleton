<?php
include_once 'A/Template.php';

class example {
	var $content;
	var $template_main;

	function example($locator) {
	}
	
	function run($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$page_template = new A_Template_Strreplace('templates/example.html');
		
		$response->setContent($page_template->render());
	}
}

?>