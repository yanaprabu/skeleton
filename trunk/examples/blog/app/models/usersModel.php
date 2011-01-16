<?php

class usersModel extends A_Model {
	
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
	
	public function findAll(){
		$this->errorMsg = array();;
		$rows = $this->datasource->find(array('active'=>1));
		if (isset($rows[0])) {
			return $rows;
		} else {
			$this->errorMsg[] = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	public function find($id){
		$rows = $this->datasource->find(array('id'=>$id));
		return $rows;
	}
	
	public function delete($id){}	
	
	public function login($username, $password) {
		$this->errorMsg = array();;
		$rows = $this->datasource->find(array('username'=>$username, 'active'=>1));
		if (isset($rows[0])) {
			
			if ($rows[0]['username'] == $username) {
				if ($rows[0]['password'] == $password) {
					return $rows[0];
				} else {
					$this->errorMsg[] = 'Password does not match. ';
				}
			} else {
				$this->errorMsg[] = 'Username not found.';
			}
		} else {
			$this->errorMsg[] = $this->datasource->getErrorMsg();
		}
		return array();
	}
	
	public function loginErrorMsg() {
		return $this->errmsg;
	}

	
	public function isUsernameAvailable($username){ 
		$this->errorMsg = array();;
		$rows = $this->datasource->find(array('username'=>$username));
		if($this->datasource->isError()){
			$this->error = true;
			$this->errorMsg[] = $this->datasource->getErrorMsg();
		}
		if(!empty($rows)){
			return false;
		} else {
			return true;
		}
	}
	
	public function isEmailAvailable($email){	
		$rows = $this->datasource->find(array('email'=>$email));
		if(!empty($rows)){
			return false;
		} else {
			return true;
		}
	}
	
	public function usernameMatchesEmail($username, $email){ 
		$rows = $this->datasource->find(array('username'=>$username ,'email'=>$email));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	public function isAccountActivated($username, $email){
		$rows = $this->datasource->find(array('username'=>$username ,'active'=>1));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	public function isPasswordCorrect($username, $password){
		$rows = $this->datasource->find(array('username'=>$username ,'password'=>$password));
		if(!empty($rows)){
			return true;
		} else {
			return false;
		}
	}
	
	public function createActivationkey(){
		return md5(uniqid(rand(), true));
	}

	public function insertUser($username, $password, $email, $activationkey){
		// @Todo insert
		$this->datasource->insert(array('username'=>$username,'email'=>$email,'password'=>$password, 'activationkey'=>$activationkey));
	}
	
	public function activate($activationkey){
		if(!empty($activationkey)){
			// @Todo: Check if the account already been activated?
			
				// If yes, user might not know. Show login screen

				// If not, activate account + sign in user + redirect to certain page		
			
			// Is there a row with this activationkey?
			$rows = $this->datasource->find(array('activationkey'=>$activationkey));
			// If there is activate the acount
			if(!empty($rows)){
				// set to active and remove key
				$rows = $this->datasource->update(array('active'=>'1', 'activationkey'=>''), array('id'=>$rows[0]['id']));
				if($rows) {
					$this->errorMsg[] = 'Your account is now activated. ';
					return true;
				}
			}
			// something went wrong..
			$this->errorMsg[] = 'We could not activate the account. ';
			
		} else {
			// User is on activate page but the activation key is missing. What to show?
			$this->errorMsg[] = 'The activation key is missing. ';
		}
		return false;
	}
	
	public function newPassword($username){
		// Is this username registered?
		// If not: user made a typo or user has no account. Show New Password form + message about the error + link to register form
		
		// If yes: reset/regenerate password + send email + show signin screen with prefilled username + message
		
	}
	
}