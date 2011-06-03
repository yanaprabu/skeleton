<?php
#require_once 'A/Rule/Length.php';
#require_once 'A/Rule/Alpha.php';
#require_once 'A/Rule/Alnum.php';
#require_once 'A/Rule/Email.php';

#require_once 'A/Model.php';
#require_once 'A/Model/Field.php';

class UsersModel extends A_Model {
	
	function __construct($locator=null) {

		$this->addField(new A_Model_Field('name'));
		$this->addField(new A_Model_Field('email'));
		$this->addField(new A_Model_Field('password'));
		
		$this->addRule(new A_Rule_Length(5, 15, 'name', 'name must 5 to 25 characters'), 'name'); 
		$this->addRule(new A_Rule_Alpha('name', 'name can only contain letters'), 'name'); 
		$this->addRule(new A_Rule_Email('email', 'This is not a valid email'), 'email');
		$this->addRule(new A_Rule_Alnum('password', 'password can only contain letters or numbers'), 'password');

	}

	function save($data=array()) {
		dump($data, 'UserModel::save() data=');
	}
}