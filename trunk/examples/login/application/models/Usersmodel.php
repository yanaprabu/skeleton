<?php
require_once 'A/Rule/Length.php';
require_once 'A/Rule/Alpha.php';
require_once 'A/Rule/Alnum.php';
require_once 'A/Rule/Email.php';

require_once 'A/Model.php';
require_once 'A/Model/Field.php';

class UsersModel extends A_Model {
	
	function __construct($locator=null) {

		$this->addField(new A_Model_Field('username'));
		$this->addField(new A_Model_Field('password'));
		
		$this->addRule(new A_Rule_Length(5, 15, 'username', 'username must 5 to 25 characters'), 'username'); 
		$this->addRule(new A_Rule_Alpha('username', 'username can only contain letters'), 'username'); 
		$this->addRule(new A_Rule_Alnum('password', 'password can only contain letters or numbers'), 'password');

	}

	function getRules() {
		return $this->rules;
	}
	function getFields() {
		return $this->fields;
	}
	
	function save($data=array()) {
		dump($data, 'UserModel::save() data=');
	}
	
	function findAuthorized($username, $password) {  
		
		// Normally you'd do a db check here, for now just mock it
		//$userhandler = new A_Db_Tabledatagateway($this->db,'co_users','userID');
		//$result = $userhandler->findByKey($userid);
		$result = $this->findByUsername($username);
		
		if(!empty($result)){
			if ( $result['username'] == $username && $result['password'] == $password ) { 
				return $result;
			}
		}		
		return array();
	}
	
	public function findByUsername($username){
		if($username === 'admin') {
			$result = array('username' => 'admin', 'password' => 'secret');
			return $result;
		} else {
			return FALSE;
		}
	}
	
	
}