<?php
include_once 'A/Controller_Input.php';
include_once 'A/Rule/Length.php';
include_once 'A/Template/Strreplace.php';

include_once 'UserTableGateway.php';

class signin extends A_Controller_Input {
	protected $usersession;

	function __construct($locator) {
		parent::__construct($locator);
	}
	
	function run($locator) {
		$request = $locator->get('Request');
		$response = $locator->get('Response');
		$usersession = $locator->get('UserSession');
		
		$errmsg = '';
		$usernamestr = '';
		if (! $usersession->isSignedIn()) {
			if ($request->get('op') == 'signin') {
				$username = new A_Controller_InputParameter('username');
				$username->addFilter(new A_Filter_Regexp('/[^a-zA-Z0-9]/', ''));
				$username->addFilter(new A_Filter_ToLower());
				$username->addRule(new A_Rule_Notnull('username', 'Username required'));
				$username->addRule(new A_Rule_Length(4, 20, 'username', 'Username must be 4 characters long'));
				$this->addParameter($username);
				
				$password = new A_Controller_InputParameter('password');
				$password->addFilter(new A_Filter_Regexp('/[^a-zA-Z0-9\!\@\#\$\%\^\&\*\(\)\-\_\=\+]/', ''));
				$password->addRule(new A_Rule_Notnull('password', 'Password required'));
				$password->addRule(new A_Rule_Length(4, 20, 'password', 'Password must be 4 characters long'));
				$this->addParameter($password);
			
				if ($this->processRequest($request)) {
					$user = new UserTableGateway();
					if ($row = $user->findAuthorized($username->value, $password->value)) {
						$usersession->merge($row);
						$usersession->signin($username->value, $password->value);
					}
				} else {
					$errmsg = 'Errors: ' . implode(', ', $this->getErrorMsgs());
					$usernamestr = $username->value;
				}
			}
		}
		if ($usersession->isSignedIn()) {
			$page_template = new Template_Strreplace('templates/signout.html');
		} else {
			$page_template = new Template_Strreplace('templates/signin.html');
			$page_template->set('errmsg', $errmsg);
			$page_template->set('username', $usernamestr);
		}
		$response->setContent($page_template->render());
	}

}

?>