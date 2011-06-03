<?php

class about extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
		$this->response->set('subcontent', '<li><a href="about/">About Us</a></li><li>
		<a href="about/agents/">Our Agents</a></li> ');
	}
	
	/* Default action. Shows latest articles */
	function index($locator) {
		$this->response->set('maincontent', '<h1>About Us</h1>This is the maincontent for the about page. ');
		
	}
	
	function agents($locator) {
		$this->response->set('maincontent', '<h1>About Our Agents</h1>This is the maincontent for the AGENTS page. ');
	}

}