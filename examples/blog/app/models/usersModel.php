<?php

class usersModel extends A_Model {
	
	protected $dbh = null;
	
	public function __construct($locator){
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('firstname'));
		$this->addField(new A_Model_Field('lastname'));
		$this->addField(new A_Model_Field('username'));
		$this->addField(new A_Model_Field('password'));
		$this->addField(new A_Model_Field('email'));
		$this->addField(new A_Model_Field('active'));

		$this->addRule(new A_Rule_Numeric('id', 'invalid ID'), 'id');
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'firstname', 'The firstname is not valid'), 'firstname'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'lastname', 'The lastname is not valid'), 'lastname'); 
		$this->addRule(new A_Rule_Length(5, 15, 'username', 'The username must be between 5 to 25 characters'), 'username'); 
		$this->addRule(new A_Rule_Regexp('/^[A-Za-z0-9]+$/D', 'username', 'The username is not valid'), 'username');		
		$this->addRule(new A_Rule_Regexp('/[0-9a-zA-Z\-\_\@\.]+/', 'password', 'The password is not valid'), 'password'); 
		$this->addRule(new A_Rule_Email('email', 'This is not a valid email adress'), 'email');
		$this->addRule(new A_Rule_Regexp('[^01]', 'active', 'active'), 'active');

		// create a Gateway style datasource for the Model
		$db = $locator->get('Db');
		$db->connect();
		$this->datasource = new A_Db_Tabledatagateway($db, 'users', 'id');
		// set the field names for the Gateway to fetch
		$this->datasource->columns($this->getFieldNames());
	}
	
	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}
	
	public function findBy($someArgs){}
	public function delete($id){}

	protected $errmsg = '';
	
	function findAll(){
		$this->errmsg = '';
		$rows = $this->datasource->find(array('active'=>1));
		if (isset($rows[0])) {
			return $rows;
		} else {
			$this->errmsg = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	function find($id){
		$rows = $this->datasource->find(array('id'=>$id));
		return $rows;
	}
	
	function login($userid, $password) {
		$this->errmsg = '';
		$rows = $this->datasource->find(array('username'=>$userid, 'active'=>1));
		if (isset($rows[0])) {
			
			if ($rows[0]['username'] == $userid) {
				if ($rows[0]['password'] == $password) {
					return $rows[0];
				} else {
					$this->errmsg = 'Password does not match. ';
				}
			} else {
				$this->errmsg = 'User ID not found.';
			}
		} else {
			$this->errmsg = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	function loginErrorMsg() {
		return $this->errmsg;
	}

	function register($request){ 
	
		/*
		* Registration process overview
		* S0 - Show Registration form
		* E1 - Registration form submitted; missing fields or unvalid values
		* E2 - Registration form submitted; user already has another account with the same email address
		* E3 - Registration form submitted; username not available
		* E4 - Registration form submitted; account created; activation email sent
		* E5 - Registration form submitted; username/email combination already exists, but with different password
		* E6 - Registration form submitted; username/email combination already exists; password is correct
		* E7 - Registration form submitted; account already exists but is not yet activated
		*/

		// Basic validation
		
		// Check if the passwords match
		$this->addRule(new A_Rule_Match('password', 'passwordagain', 'Fields password and passwordagain do not match'));
		// Check if the terms of service checkbox has been checked
		$this->addRule(new A_Rule_Regexp('/agree/', 'tos', 'Dont agree with the terms of service?'), 'tos'); 
		// Exclude some fields not needed in the validation of the model
		$this->excludeRules(array('id','firstname','lastname','active'));
		// If the values are not valid return the E1 code
		if(!$this->isValid($request)){
			return 'E1';
		}
		
		// Further validation of registration attempt
		
		// Get the field values for local use
		$username = $request->get('username');
		$email = $request->get('email');
		$password = $request->get('password');
		$passwordagain = $request->get('passwordagain');
		$tos = $request->get('tos');
		
		// Check if the username is available
		if($this->usernameAvailable($username)){ 
			if($this->emailExists($email)){ 
				// E2 - user already has another account with the same email address
				// The email adress is already in the db
				// User doesn't know he already has an account
				// or he tries to register again
				// Show message + registration form + link to sign in form + link to send new password
				return 'E2';
			} else { 
				// E4 - Registration form submitted; account created; activation email sent
				// Create account
					// @todo: create account
				// Send activation email
					// @todo: send activation email
				// Show message succesful registration
				return 'E4';
			}
			
		// Username is not available / already in database
		} else { 
			// Check if this username belongs to the posted email
			if($this->usernameHasEmail($username, $email)){ 
				// Check if account has been activated?
				if($this->accountActivated($username, $email)){ 
					// The account has been activated already. In that case check if password is correct
					if($this->passwordCorrect($username, $password)){ 
						// E6 - username/email combination already exists; password is correct
						// Login the user and redirect (?) to success page or tell user he has been logged in
						$this->login($username, $password);
						return 'E6';
					} else { 
						// E5 - username/email combination already exists, but with different password
						// User has an activated account but forgot his password
						// show message + signin form + forgot password link
						return 'E5';
					}
				} else { 
					// E7 - Registration form submitted; account already exists but is not yet activated
					// account is not yet activated
					// Show message user has to activate account + show link to resend activation email
					return 'E7';
				}
			} else {
				// E3 - Registration form submitted; username not available
				// Explanation: another user already taken that username: return error status
				// Show message username already taken + registration form
				return 'E3';
			}
		}
	}
	
	protected function usernameAvailable($username){ 
		$rows = $this->datasource->find(array('username'=>$username));
		if(!empty($rows)){
			return false;
		} else {
			return true;
		}
	}
	
	protected function emailExists($email){ 
		$rows = $this->datasource->find(array('email'=>$email));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	protected function usernameHasEmail($username, $email){ 
		$rows = $this->datasource->find(array('username'=>$username ,'email'=>$email));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	protected function accountActivated($username, $email){ 
		$rows = $this->datasource->find(array('username'=>$username ,'active'=>1));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	protected function passwordCorrect($username, $password){
		$rows = $this->datasource->find(array('username'=>$username ,'password'=>$password));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
}