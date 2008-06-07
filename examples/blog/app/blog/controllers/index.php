<?php

class index extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
	}
	
	/* Default action. Shows latest articles */
	function run($locator) {
		
	/*	$Template = new A_Template_Include('examples/blog/app/blog/templates/bloglayout.php');
	    $Template->set('BASE', 'http://skeleton/examples/blog/');
		$this->response->setRenderer($Template);
	*/
		$this->response->set('maincontent','This is the maincontent for the blog index');
		$this->response->set('subcontent','This is the subcontent for the blog index');

	}

}