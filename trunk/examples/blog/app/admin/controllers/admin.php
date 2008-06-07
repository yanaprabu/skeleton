<?php

class admin extends A_Controller_Action {
	var $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response'); 
		$this->usersession = $locator->get('UserSession');
		$this->userdata = $this->usersession->get();
	}

	function denyAccess($locator) {

		if (! $this->usersession->isSignedIn()) {  

		//	return $this->forward('','login','');
			$this->response->setRedirect('/'); 
		}
	}

	function run($locator) { 
		$content = 'This is the content for the admin section';
		$subcontent = 'The sidebar of the admin section';
		
		$template = $this->load()->template('admin');
		$template->set('BASE', 'http://skeleton/examples/blog/' ); // $ConfigArray['BASE']  TODO: Fix this BASE/config mess
		$template->set('maincontent', $content);
		$template->set('subcontent', $subcontent);
		
		$this->response->setRenderer($template);

	}

}