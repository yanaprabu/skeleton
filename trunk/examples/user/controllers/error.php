<?php
#include_once 'A_Template.php';

class error extends A_Controller_Action {
	protected $content;
	protected $template_main;
	
	function __construct($locator){
		parent::__construct($locator);
		$this->usersession = $locator->get('UserSession');
	}
/*
	function error($locator) {
		
	}
*/	
	function index($locator) {
		$page_template = new A_Template_Strreplace('templates/error.html');
		
		$this->response->setContent($page_template->render());
	}
}
