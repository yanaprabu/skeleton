<?php

class index extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	/* Default action. Shows latest articles */
	public function index($locator) {
		$this->response->set('maincontent', 'This is the maincontent for the Blog index page. ');
		$this->response->set('subcontent', 'This is the subcontent for the blog index page. ');

	}

}