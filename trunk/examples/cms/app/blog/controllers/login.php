<?php
include_once 'A/User/Session.php';
include_once 'A/Model/Form.php';

class login extends A_Controller_Action {

	function index($locator) {
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
			$userdata = $model->login($form->get('userid'), $form->get('password'));
			if ($userdata) {	// user record matching userid and password found
				unset($userdata['password']);		// don't save passwords in the session
				$user->login($userdata);
				$this->_redirect($locator->get('Config')->get('BASE') . 'login/');	// build redirect URL back to this page
			} else {
				$errmsg = $model->getErrorMsg();
			}
		} elseif($form->isSubmitted()){		// submitted form has errors
			$errmsg =  $form->getErrorMsg(', ');
		}
		$template = $this->_load()->template();
		$template->set('errmsg', $errmsg);
		$template->set('userid', $form->get('userid'));
		$template->set('user', $user);
		
		$this->response->set('maincontent', $template);
	}

	
	function logout($locator) {
		$session = $locator->get('Session');
		$user = $locator->get('UserSession');
		
		$session->start();		// controller and view use session
		if ($user->isLoggedIn()) {	// user record matching userid and password found
			$user->signout();
		}
		$this->_redirect($locator->get('Config')->get('BASE') . 'login/');	// build redirect URL back to this page
	}
}