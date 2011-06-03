<?php

class index extends A_Controller_Action {

	public function __construct($locator=null){
		parent::__construct($locator);
	}
	
	public function index($locator=null) {
		$content = '
	This is the content for the Application index page.
	';
		$this->response->set('maincontent', $content);
	}

}