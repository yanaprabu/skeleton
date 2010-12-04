<?php

class usersModel extends A_Model {
	
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
	
	const STATUS_BASE					= 'User not registerd';				// previously S0
	const ERROR_INVALID					= 'Missing or invalid fields';		// previously "E1"
	const ERROR_EMAIL_UNAVAILABLE 		= 'Email already has an account';	// previously "E2"
	const ERROR_USERNAME_UNAVAILABLE	= 'Username is taken';				// previously "E3"
	const ERROR_PASSWORD				= 'Password is incorrect';			// previously "E5"
	const ERROR_ACOUNT_UNACTIVATED 		= 'Account not activated yet';		// previously "E7"
	const STATUS_REGISTERED				= 'Registration completed succesfully'; // previously "E4"
	const STATUS_LOGGED_IN				= 'Account existed, user logged in';	// previously "E6"
	
	protected $status = self::STATUS_BASE;

	protected $errmsg = '';
		
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
	
	public function getStatus(){
		return $this->status;
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
	
	public function delete($id){}	
	
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
	
		$this->status = self::STATUS_BASE;
		
		// Add validation rules		
		// Check if the passwords match
		$this->addRule(new A_Rule_Match('password', 'passwordagain', 'Fields password and passwordagain do not match'));
		// Check if the terms of service checkbox has been checked
		$this->addRule(new A_Rule_Regexp('/agree/', 'tos', 'Dont agree with the terms of service?'), 'tos'); 
		// Exclude some fields not needed in the validation of the model
		$this->excludeRules(array('id','firstname','lastname','active','access'));

		// Validate. If the values are not valid return the E1 code
		if(!$this->isValid($request)){
			$this->status = self::ERROR_INVALID;
            return false;
		}
		
		// Further validation of registration attempt
		
		// Get the field values for local use
		$username 		= $request->get('username');
		$email 			= $request->get('email');
		$password 		= $request->get('password');
		$passwordagain 	= $request->get('passwordagain');
		$tos 			= $request->get('tos');
		
		// Check if the username is available
		if($this->isUsernameAvailable($username)){ 
			if($this->isEmailAvailable($email)){ 
				
				// Create activationkey  and insert user row for the account
				$activationkey = md5(uniqid(rand(), true));
				$this->datasource->insert(array('username'=>$username,'email'=>$email,'password'=>$password, 'activationkey'=>$activationkey));
				
				// Send activation email $this->sendActivationMail($email, $regkey);
				$subject = 'Activation account';
				$message = 'Please click the following link to activate your account' . "\n\r";
				$message .= 'http://skeleton/examples/blog/user/activate?id=' . $activationkey . "\n\r";
				$message .= 'Thanks.';
				$from = 'From: skeleton blog';
				mail($email, $subject, $message, $from);
				
				// E4 Registration form submitted; account created; activation email sent
				$this->status = self::STATUS_REGISTERED;
	            return true;
	
			} else { 
				
				// E2 - user already has another account with the same email address
				// The email adress is already in the db
				// User doesn't know he already has an account
				// or he tries to register again
				// Show message + registration form + link to sign in form + link to send new password
				$this->status = self::ERROR_EMAIL_UNAVAILABLE;
	            return false;
			}	
		// Username is not available / already in database
		} else { 
			// Check if this username belongs to the posted email
			if($this->usernameMatchesEmail($username, $email)){ 
				// Check if account has been activated?
				if($this->accountActivated($username, $email)){ 
					// The account has been activated already. In that case check if password is correct
					if($this->passwordCorrect($username, $password)){ 
						// E6 - username/email combination already exists; password is correct
						// Login the user and redirect (?) to success page or tell user he has been logged in
						$userdata = $this->login($username, $password);
						$user = $locator->get('UserSession');
						$user->login($userdata);

						$this->status = self::LOGGED_IN;
			            return true;
					} else { 
						// E5 - username/email combination already exists, but with different password
						// User has an activated account but forgot his password. show message + signin form + forgot password link
						$this->status = self::ERROR_PASSWORD;
			            return false;
					}
				} else { 
					// E7 - Registration form submitted; account already exists but is not yet activated
					// Show message user has to activate account + show link to resend activation email
					$this->status = self::ERROR_ACOUNT_UNACTIVATED;
		            return false;
				}
			} else {
				// E3 - Registration form submitted; username not available
				// Another user already taken that username: return error status
				$this->_error(self::ERROR_USERNAME_UNAVAILABLE);
	            return false;
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
	
	/* Not used for now
	protected function sendActivationMail($email, $key){
		$subject = 'Activation account';
		$activationlink = 'http://skeleton/examples/blog/user/activate?id=' . $key;
		$message = 'Please click the following link to activate your account' . "\n\r";
		$message .= $activationlink;
		$from = 'From: skeleton blog';
		mail($email, $subject, $message, $from);
	}*/
	
	public function activate($activationkey){
		// Is there a row with this activationkey?
		$rows = $this->datasource->find(array('activationkey'=>$activationkey));
		// If there is activate the acount
		if(!empty($rows)){
			$rows = $this->datasource->update(array('active'=>1), array('activationkey'=>$activationkey));
			if($rows) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}
	
	public function newPassword($username){
		// Is this username registered?
		// If not: user made a typo or user has no account. Show New Password form + message about the error + link to register form
		
		// If yes: reset/regenerate password + send email + show signin screen with prefilled username + message
		
	}
	
}