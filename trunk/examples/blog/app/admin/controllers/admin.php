<?php

class admin extends A_Controller_Action {

	function __construct($locator) {
		parent::__construct($locator);
		$this->usersession = $locator->get('UserSession');
		$this->userdata = $this->usersession->get();
	}

	/*
	 * This function is called only if it exists. Front Controller pre-filter 
	 * calls it to get required groups for this controller
	 */
	public function _requireGroups(){
		return array('post','admin');
	}
	
/*
	// These are examples of Access Control done in the Action Controller
	// where the method called by the pre-filter acts as a forward

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

	function denyAccess($locator) {

		if (! $this->usersession->isSignedIn()) {  

		//	return $this->forward('','login','');
			$this->response->setRedirect('/'); 
		}
	}
*/
	function index($locator) { 
		$content = 'This is the content for the admin section';
		$subcontent = 'The sidebar of the admin section';
		
		$template = $this->_load('application')->template('admin');
#		$template->set('BASE', 'http://skeleton/examples/blog/' ); // $ConfigArray['BASE']  TODO: Fix this BASE/config mess
#		$template->set('maincontent', $content);
#		$template->set('subcontent', $subcontent);
		
		$this->response->set('maincontent', $template->render());

	}

}