<?php
include_once 'A/Template/Strreplace.php';

class example {
	protected $content;
	protected $template_main;

	function example($locator) {
	}
	
	function index($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		
		$page_template = new A_Template_Strreplace('templates/example.html');
		
		$response->setContent($page_template->render());
	}

}

?>