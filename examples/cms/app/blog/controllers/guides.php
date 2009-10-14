<?php

class guides extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
		$this->response->set('subcontent', 'This is the subcontent value. I set it in the action controller constructor. So, by default you would see this on all "pages" under this area. Maybe I should use this or something like it to modify the sub-navigation above. ');
	}
	
	/* Default action. Shows latest articles */
	function index($locator) {
		$this->response->set('maincontent', 'This is the maincontent for the GUIDES page. ');
		$this->response->set('subcontent', 'This is the subcontent for the blog index page. ');
	}
	
	function buyers($locator) {
		$this->response->set('maincontent', '<h1>Buyers Guide</h1>This is the maincontent for the buyers page. ');
	}

	function sellers($locator) {
		$this->response->set('maincontent', '<h1>Sellers Guide</h1>This is the maincontent for the sellers page. ');
	}


}