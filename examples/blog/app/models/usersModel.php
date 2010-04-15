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
	
}