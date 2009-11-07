<?php
include_once 'A/User/Session.php';
include_once 'A/Model/Form.php';

class user extends A_Controller_Action {

	function login($locator) {
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
#dump($user, 'USER: ');
		
		$errmsg = '';
		
		$session->start();		// controller and view use session
		
		$form = new A_Model_Form();

		$field = new A_Model_Form_Field('userid');
		$field->addRule(new A_Rule_Notnull('userid', 'User ID required'));
		$form->addField($field);
		
		$field = new A_Model_Form_Field('password');
		$field->addRule(new A_Rule_Notnull('password', 'Password required'));
		$form->addField($field);
		
		// If username and password valid and isPost
		if($form->isValid($this->request)){ 
			
			// How to translate URL in correct action variable?
			$model = $this->_load('app')->model('users');
			$userdata = $model->signin($form->get('userid'), $form->get('password'));
			if ($userdata) {	// user record matching userid and password found
				unset($userdata['password']);		// don't save passwords in the session
				$user->signin($userdata);
				$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
			} else {
				$errmsg = $model->getErrorMsg();
			}
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		$template = $this->_load()->template('login');
		$template->set('errmsg', $errmsg);
		$template->set('userid', $form->get('userid'));
		$template->set('user', $user);
		
		$this->response->set('maincontent', $template);
	}
	
	function logout($locator) {
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		
		$session->start();		// controller and view use session
		if ($user->isSignedIn()) {	// user record matching userid and password found
			$user->signout();
		}
		$this->_redirect($locator->get('Config')->get('BASE') . 'user/login/');	// build redirect URL back to this page
	}
	
	function register($locator){
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');

		$errmsg = '';
		$session->start();		// controller and view use session

		$form = new A_Model_Form();

		$field = new A_Model_Form_Field('username');
		$field->addRule(new A_Rule_Notnull('username', 'username field is required'));
		$form->addField($field);

		$field = new A_Model_Form_Field('email');
		$field->addRule(new A_Rule_Notnull('email', 'email field is required'));
		$form->addField($field);

		// If registration is valid
		if($form->isValid($this->request)){
			$model = $this->_load('app')->model('users');
			// do the registration
			
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		// Show registration form
		$template = $this->_load()->template('register');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
	
	function profile($locator){
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');

		$errmsg = '';
		$session->start();
		
		$form = new A_Model_Form();
		
		// To show the profile we need the model
		$model = $this->_load('app')->model('users');
		
		// If profile form is posted and is valid
		if($form->isValid($this->request)){
			
		}
		// Show profile page and form
		$template = $this->_load()->template('profile');
		$template->set('errmsg', $errmsg);
		$template->set('user', $user);
		$this->response->set('maincontent', $template);
	}
	
	function password($locator){
		
		// If password forgot form is posted and is valid
		if($form->isValid($this->request)){
			
		}
		// Show password forgot page and form
		$template = $this->_load()->template();
		$template->set('errmsg', $errmsg);
		
		$this->response->set('maincontent', $template);
	}
	
}