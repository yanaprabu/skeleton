<?php
#require_once 'A/Rule/Length.php';
#require_once 'A/Rule/Alpha.php';
#require_once 'A/Rule/Alnum.php';
#require_once 'A/Rule/Email.php';

class UsersModel {
	
	private $fields = array();
	private $rules = array();
	
	function __construct($locator=null) {
		$this->fields[] = new A_Model_Field('name');
		$this->fields[] = new A_Model_Field('email');
		$this->fields[] = new A_Model_Field('password');
		
		$this->rules[] = new A_Rule_Length(5, 15, 'name', 'name must 5 to 25 characters'); 
		$this->rules[] = new A_Rule_Alpha('name', 'name can only contain letters'); 
		$this->rules[] = new A_Rule_Email('email', 'This is not a valid email');
		$this->rules[] = new A_Rule_Alnum('password', 'password can only contain letters or numbers');
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
}