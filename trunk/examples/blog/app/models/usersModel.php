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
		$this->addRule(new A_Rule_Length(5, 15, 'username', 'name must 5 to 25 characters'), 'username'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\_\@\.]', 'username', 'invalid username'), 'username'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'firstname', 'invalid firstname'), 'firstname'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'lastname', 'invalid firstname'), 'lastname'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\_\@\.]', 'password', 'invalid username'), 'password'); 
		$this->addRule(new A_Rule_Email('email', 'This is not a valid email'), 'email');
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
		Registration pages
		    * - Show Registration form
		    * - Registration form submitted; user already has another account with the same email address
		    * - Registration form submitted; username not available
		    * - Registration form submitted; account created; activation email sent
		    * - Registration form submitted; username/email combination already exists, but with different password
		    * - Registration form submitted; username/email combination already exists; password is correct
		    * - Registration form submitted; account already exists but is not yet activated
		*/

		$data = array();
		//Check if any field is empty
		if (empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['passwordagain'])) {
			// Return error missing field
			
		}
		// Check if the passwords match
     	if (isset($data['password']) && isset($data['passwordagain']) && $data['password'] != $data['passwordagain']){
			// Return error passwords do not match
			
		}

		// Check if the username is available
		if($this->usernameAvailable($data['username'])){ 
			if($this->emailExists($data['email'])){ 
				// The email adress is already in the db
				// User doesn't know he already has an account
				// or he tries to register again
				// Show message + registration form + link to sign in form + link to send new password
				
			} else {
				// Create account
				
				// Send activation email
				
				// Show message succesful registration
				
			}
			
		// Username is not available / already in database
		} else {
			// Check if thise username belongs to the posted email
			if($this->usernameHasEmail($data['username'], $data['email'])){
				// Check if account has been activated?
				if($this->accountActivated($data['username'], $data['email'])){
					// The account has been activated already. In that case check if password is correct
					if($this->passwordCorrect($data['username'], $data['password'])){
						// Login the user and redirect to success page
						$this->login($data['username'], $data['password']);
						
					} else {
						// User has an activated account but forgot his password
						// show message + signin form + forgot password link
						
					}
				} else {
					// account is not yet activated
					// Show message user has to activate account + show link to resend activation email
					
				}
			} else {
				// Explanation: another user already taken that username: return error status
				// Show message username already taken + registration form

			}
			
		}
		
	}
	
	protected function usernameAvailable($username){}
	protected function emailExists($email){}
	protected function usernameHasEmail($username, $email){}
	protected function accountActivated($username, $email){}
	protected function passwordCorrect($username, $password){}
	
}