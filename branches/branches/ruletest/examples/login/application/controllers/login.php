<?php

class login extends A_Controller_Action {

	function index($locator) {
		$usersession = $locator->get('UserSession');
		$usersmodel = $this->_load()->model('Users');
		$form = new A_Model_Form();
		$form->addField($usersmodel->getFields());
		$base = $locator->get('Config')->get('BASE');
		
		// If the user is loged in:
		if ( $usersession->isLoggedIn() ) {
			// and wants to log out
			if($this->request->get('op') == 'logout') {
				$usersession->logout();
				$this->_flash('Message', 'You are now logged-out');
				$this->response->setRedirect($base);
				// For now I do a redirect but you can also do:
				//$layout = $this->_load()->template('login.tpl');
				//$layout->set('message', 'you are now loged out');
			} else {
				// else just show the logout form
				$layout = $this->_load()->template('logout');
				$layout->set('BASE', $base);	
				$layout->set('message', 'Please log out');	
				$this->response->setRenderer($layout);
			}
			
		} else {
		
		// If not loged in and user wants to log in
			$layout = $this->_load()->template('login');
			
			if ($this->_request('op') == 'login') { 
				if($form->isValid($this->request)) {	
					if ($row = $usersmodel->findAuthorized($form->get('username'), $form->get('password'))) { 

						$usersession->login($form->get('username'));  
						$this->_flash('Message', 'You are now logged-in');
						$url = 'http://skeleton/examples/login/';
						$this->response->setRedirect($url);
					}	
				} 
			} else {
				$layout->set('message', 'Please log in');
			}
			
			$layout->set('errmsg', 'Please fill in correct username and password');
			$layout->set('errmsg', $form->getErrorMsg(' ,'));
			$layout->set('BASE', $base);	
			$layout->set('username', $form->get('username'));
			$this->_response()->setRenderer($layout);
		}
		
	}

}