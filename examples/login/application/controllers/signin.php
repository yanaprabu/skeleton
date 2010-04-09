<?php

class login extends A_Controller_Action {

	function index($locator) {
		$usersession = $locator->get('UserSession');
		$usersmodel = $this->_load()->model('Users');
		$form = new A_Model_Form();
		$form->addField($usersmodel->getFields());
		$base = $locator->get('Config')->get('BASE');
		
		// If the user is signed in:
		if ( $usersession->isLoggedIn() ) {
			// and wants to sign out
			if($this->request->get('op') == 'signout') {
				$usersession->signout();
				$this->_flash('Message', 'You are now signed out');
				$this->response->setRedirect($base);
				// For now I do a redirect but you can also do:
				//$layout = $this->_load()->template('signin.tpl');
				//$layout->set('message', 'you are now signed out');
			} else {
				// else just show the logout form
				$layout = $this->_load()->template('signout');
				$layout->set('BASE', $base);	
				$layout->set('message', 'Please sign out');	
				$this->response->setRenderer($layout);
			}
			
		} else {
		
		// If not Signed in and user wants to sign in
			$layout = $this->_load()->template('signin');
			
			if ($this->_request('op') == 'signin') { 
				if($form->isValid($this->request)) {	
					if ($row = $usersmodel->findAuthorized($form->get('username'), $form->get('password'))) { 

						$usersession->login($form->get('username'));  
						$this->_flash('Message', 'You are now signed in');
						$url = 'http://skeleton/examples/login/';
						$this->response->setRedirect($url);
					}	
				} 
			} else {
				$layout->set('message', 'Please sign in');
			}
			
			$layout->set('errmsg', 'Please fill in correct username and password');
			$layout->set('errmsg', $form->getErrorMsg(' ,'));
			$layout->set('BASE', $base);	
			$layout->set('username', $form->get('username'));
			$this->_response()->setRenderer($layout);
		}
		
	}

}