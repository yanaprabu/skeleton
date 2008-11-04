<?php

class admin extends A_Controller_Action {
	protected $response;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response'); 
		$this->usersession = $locator->get('UserSession');
		$this->userdata = $this->usersession->get();
	}

	public function denyAccess($locator){ //dump($this);
		// Start Sessions
		$Session = $locator->get('Session');
		$Session->start();
	//	$UserSession = $locator->get('UserSession');dump($UserSession);
	//	$Session = new A_Session();
	//	$Session->start();
		$UserSession = new A_User_Session($Session);dump($UserSession);
		//$Session = $this->locator->get('Session');
		//$Session->start();
		//$UserSession = $this->locator->get('UserSession');
		
	//	$UserSession->addRule(new A_User_Rule_Issignedin());
	//	$UserSession->addRule(new A_User_Rule_Ingroup('admin'));
		if($UserSession->isSignedIn()) {
			// do somthint
		} else {
			echo 'user is not signed in';
		}
	
	//	if($UserSession->isSignedIn()){
	//		echo 'user is signed in';
	//	} else {
	//		echo 'user is not signed in';
	//	}
	}
	/*
	function denyAccess($locator) {

		if (! $this->usersession->isSignedIn()) {  

		//	return $this->forward('','login','');
			$this->response->setRedirect('/'); 
		}
	}
*/
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