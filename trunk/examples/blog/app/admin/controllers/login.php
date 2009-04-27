<?php
require_once 'A/Controller/Input.php';
require_once 'A/Rule/Length.php';
require_once 'A/Rule/Notnull.php';
require_once 'A/Filter.php';
require_once 'A/Filter/Regexp.php';
require_once 'A/Filter/Tolower.php';

require_once 'app/admin/models/UserTableGateway.php';

class login extends A_Controller_Input {

	protected $response;
	protected $request;
	protected $usersession;
	protected $db;

	function __construct($locator) {
		parent::__construct($locator);
		$this->response = $locator->get('Response');
		$this->usersession = $locator->get('UserSession');
		$this->request = $locator->get('Request');
	}

	function run($locator) {
		
		$msg = '';
		$usernamestr = '';

		if (! $this->usersession->isSignedIn()) {

			if($this->request->isPost()) {
				
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
				
				if ($this->processRequest($this->request)) { 
					$user = new UserTableGateway(); 
					if ($row = $user->findAuthorized($username->value, $password->value)) {
						$this->usersession->merge($row);
						$this->usersession->signin($username->value, $password->value);
						// TODO: now redirect to last visited page
					} else {
						$msg = 'You gave an invalid username and/or password. Try again';
						$usernamestr = $username->value;
					}
				} else {
					$msg = 'Errors: ' . implode(', ', $this->getErrorMsgs());
					$usernamestr = $username->value;
				}
			} else {
				$msg = 'Please login by filling in the form below'; // TODO: this should be moved to somewhere else
			}
		} 
		
		if($this->usersession->isSignedIn()) {

			if($this->request->get('op') == 'signout'){
				$this->usersession->signout();
				//	$this->response->setRedirect('/'); // TODO: this should go to previous visited page ?
				$msg = 'You are logged out';
				
			} 
			else 
			{
				// just show the form? Or redirect to homepage?
				$msg = 'You are logged in';
echo "ADMIN/LOGIN!!!!!!!<br>";
#				$this->response->setRedirect('/admin/'); 
			}
		}
		
		$logintemplate = $this->load()->template('loginform');
		$logintemplate->set('msg', $msg);
		$logintemplate->set('username', $usernamestr);
		
		//$this->response->setRenderer($logintemplate);
		$this->response->set('maincontent', $logintemplate->render());
		$this->response->set('subcontent', 'The subcontent for the login');
	}
	
}