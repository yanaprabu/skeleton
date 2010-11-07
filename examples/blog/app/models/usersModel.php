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
		$this->addField(new A_Model_Field('access'));

		$this->addRule(new A_Rule_Numeric('id', 'invalid ID'), 'id');
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'firstname', 'The firstname is not valid'), 'firstname'); 
		$this->addRule(new A_Rule_Regexp('[^0-9a-zA-Z\-\ \\\']', 'lastname', 'The lastname is not valid'), 'lastname'); 
		$this->addRule(new A_Rule_Length(3, 15, 'username', 'The username must be between 3 to 25 characters'), 'username'); 
		$this->addRule(new A_Rule_Regexp('/^[A-Za-z0-9]+$/D', 'username', 'The username is not valid'), 'username');		
		$this->addRule(new A_Rule_Regexp('/[0-9a-zA-Z\-\_\@\.]+/', 'password', 'The password is not valid'), 'password'); 
		$this->addRule(new A_Rule_Email('email', 'This is not a valid email adress'), 'email');
		$this->addRule(new A_Rule_Regexp('[^01]', 'active', 'active'), 'active');
		$this->addRule(new A_Rule_Regexp('/[~0-9a-zA-Z\-\_\|]/', 'access', 'User access'), 'access'); 
		
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
	
	public function findAll(){
		$this->errmsg = '';
		$rows = $this->datasource->find(array('active'=>1));
		if (isset($rows[0])) {
			return $rows;
		} else {
			$this->errmsg = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	public function find($id){
		$rows = $this->datasource->find(array('id'=>$id));
		return $rows;
	}
	
	public function login($username, $password) {
		$this->errmsg = '';
		$rows = $this->datasource->find(array('username'=>$username, 'active'=>1));
		if (isset($rows[0])) {
			
			if ($rows[0]['username'] == $username) {
				if ($rows[0]['password'] == $password) {
					return $rows[0];
				} else {
					$this->errmsg = 'Password does not match. ';
				}
			} else {
				$this->errmsg = 'Username not found.';
			}
		} else {
			$this->errmsg = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	public function loginErrorMsg() {
		return $this->errmsg;
	}

	public function register($request){ 
	
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
		
		// Default status, unregistered
		$regstat = 'S0';
		
		// Basic validation
		
		// Check if the passwords match
		$this->addRule(new A_Rule_Match('password', 'passwordagain', 'Fields password and passwordagain do not match'));
		// Check if the terms of service checkbox has been checked
		$this->addRule(new A_Rule_Regexp('/agree/', 'tos', 'Dont agree with the terms of service?'), 'tos'); 
		// Exclude some fields not needed in the validation of the model
		$this->excludeRules(array('id','firstname','lastname','active','access'));
		//dump($request);
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
		if($this->isUsernameAvailable($username)){ 
			if($this->isEmailAvailable($email)){ 
				// E4 - Registration form submitted; account created; activation email sent
				// Create account
				// @todo: Generate random string for activation
				$activationkey = 'someuniquerandomstring';
				$this->datasource->insert(array('username'=>$username,'email'=>$email,'password'=>$password, 'activationkey'=>$activationkey));
				
				// Send activation email $this->sendActivationMail($email, $regkey);
				$subject = 'Activation account';
				$message = 'Please click the following link to activate your account' . "\n\r";
				$message .= 'http://skeleton/examples/blog/user/activate?id=' . $activationkey . "\n\r";
				$message .= 'Thanks.';
				$from = 'From: skeleton blog';
				mail($email, $subject, $message, $from);
				
				// Show message succesful registration
				return 'E4';
			} else { 
				// E2 - user already has another account with the same email address
				// The email adress is already in the db
				// User doesn't know he already has an account
				// or he tries to register again
				// Show message + registration form + link to sign in form + link to send new password
				return 'E2';
			}
			
		// Username is not available / already in database
		} else { 
			// Check if this username belongs to the posted email
			if($this->usernameMatchesEmail($username, $email)){ 
				// Check if account has been activated?
				if($this->accountActivated($username, $email)){ 
					// The account has been activated already. In that case check if password is correct
					if($this->passwordCorrect($username, $password)){ echo 'we try to login';
						// E6 - username/email combination already exists; password is correct
						// Login the user and redirect (?) to success page or tell user he has been logged in
						$userdata = $this->login($username, $password);//var_dump($res);
						$user = $locator->get('UserSession');
						$user->login($userdata);
						
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
	
	protected function isUsernameAvailable($username){ 
		$this->errmsg = '';
		$rows = $this->datasource->find(array('username'=>$username));
		if($this->datasource->isError()){
			$this->error = true;
			$this->errmsg = $this->datasource->getErrorMsg();
		}
		if(!empty($rows)){
			return false;
		} else {
			return true;
		}
	}
	
	// protected function emailExists($email){ 
	protected function isEmailAvailable($email){	
		$rows = $this->datasource->find(array('email'=>$email));
		if(!empty($rows)){
			return false;
		} else {
			return true;
		}
	}
	
	protected function usernameMatchesEmail($username, $email){ 
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
	
	protected function sendActivationMail($email, $key){
		$subject = 'Activation account';
		$activationlink = 'http://skeleton/examples/blog/user/activate?id=' . $key;
		$message = 'Please click the following link to activate your account' . "\n\r";
		$message .= $activationlink;
		$from = 'From: skeleton blog';
		mail($email, $subject, $message, $from);
	}
	
	public function activate($activatelink){
		// Check if the link contains all necessary info
		
			// If not explain problem
			
		// Has the account already been activated?
		
			// If yes, user might not know. Show login screen
			
			// If not, activate account + sign in user + redirect to certain page
					
	}
	
	public function newPassword($username){
		// Is this username registered?
		// If not: user made a typo or user has no account. Show New Password form + message about the error + link to register form
		
		// If yes: reset/regenerate password + send email + show signin screen with prefilled username + message
		
	}
	
}