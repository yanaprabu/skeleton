<?php
require_once 'A/Rule/Length.php';
require_once 'A/Rule/Alpha.php';

class UsersModel {
	private $rules = array();
	
	function __construct($locator=null) {
		$this->rules[] = new A_Rule_Length('name', 5, 15, 'name must 5 to 25 characters'); 
		$this->rules[] = new A_Rule_Alpha('name', 'name can only contain letters'); 
	}

	function getRules() {
		return $this->rules;
	}
	
	function save($data=array()) {
		dump($data, 'UserModel::save() data=');
	}
}