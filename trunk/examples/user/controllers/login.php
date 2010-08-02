<?php
#include_once 'A/Controller_Input.php';
#include_once 'A/Rule/Length.php';
#include_once 'A/Template/Strreplace.php';

class login extends A_Controller_Action {
	protected $usersession;

#	public function __construct($locator) {
#	}
	
	public function index($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		$usersession = $locator->get('UserSession');
		
		$errmsg = '';
		$usernamestr = '';
		if (! $usersession->isLoggedIn()) {
			if ($request->get('op') == 'login') {
				$form = new A_Model_Form();
				
				$username = new A_Model_Form_Field('username');
				$username->addFilter(new A_Filter_Regexp('/[^a-zA-Z0-9]/', ''));
				$username->addFilter(new A_Filter_ToLower());
				$username->addRule(new A_Rule_Notnull('username', 'Username required'));
				$username->addRule(new A_Rule_Length(4, 20, 'username', 'Username must be 4 characters long'));
				$form->addField($username);
				
				$password = new A_Model_Form_Field('password');
				$password->addFilter(new A_Filter_Regexp('/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+]/', ''));
				$password->addRule(new A_Rule_Notnull('password', 'Password required'));
				$password->addRule(new A_Rule_Length(4, 20, 'password', 'Password must be 4 characters long'));
				$form->addField($password);
			
				if ($form->isValid($request)) {
					$user = $this->_load()->model('UsersModel');
					if ($row = $user->findAuthorized($username->value, $password->value)) {
						$usersession->merge($row);
						$usersession->login($username->value, $password->value);
					}
				} else {
					$errmsg = 'Errors: ' . $form->getErrorMsg(', ');
					$usernamestr = $username->value;
				}
			}
		}
		if ($usersession->isLoggedIn()) {
			$page_template = new A_Template_Strreplace('templates/logout.html');
		} else {
			$page_template = new A_Template_Strreplace('templates/login.html');
			$page_template->set('errmsg', $errmsg);
			$page_template->set('username', $usernamestr);
		}
		$response->setContent($page_template->render());
	}

	public function logout($locator) {
echo "logout()<br/>";
		$response = $locator->get('Response');
		$usersession = $locator->get('UserSession');
		
		$errmsg = '';
		$usernamestr = '';
		if ($usersession->isLoggedIn()) {
			$usersession->logout();
		}
		$page_template = new A_Template_Strreplace('templates/login.html');
		$page_template->set('errmsg', $errmsg);
		$page_template->set('username', $usernamestr);
		$response->setContent($page_template->render());
	}

}
