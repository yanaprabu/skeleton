<?php

class usersModel extends A_Model {
	
	protected $dbh = null;
	
	public function __construct($db){
		$this->dbh = $db;
		$this->addField(new A_Model_Field('id'));
		$this->addField(new A_Model_Field('firstname'));
		$this->addField(new A_Model_Field('lastname'));
		$this->addField(new A_Model_Field('username'));
		$this->addField(new A_Model_Field('password'));
		$this->addField(new A_Model_Field('email'));
		$this->addField(new A_Model_Field('active'));
	}
	
	public function save(){
		// if doesn't exist yet create
		if(!$this->get('id')){
			// insert new 
		} else {
			// update
		}
	}
	
	public function find($id){}
	public function findBy($someArgs){}
	public function delete($id){}


	public $data = array(
			array(
				'id' => 1,
				'userid' => 'user1',
				'password' => 'user1',
				'access' => 'post',
				'fname' => 'Test',
				'lname' => 'One',
				),
			array(
				'id' => 2,
				'userid' => 'user2',
				'password' => 'user2',
				'access' => 'post|admin',
				'fname' => 'Test',
				'lname' => 'Two',
				),
			);
	protected $errmsg = '';
	
	function findAll(){
		$this->errmsg = '';
		return $this->data; 
	}
	
	function find($id){
		$this->errmsg = '';
		foreach ($this->data as $row) {
			if ($row['id'] == $id) {
				return $row; 
			}
		}
		return array();
	}
	
	function signin($userid, $password) {

		$this->errmsg = '';
		if ($this->data) {
			foreach ($this->data as $row) {
				if ($row['userid'] == $userid) {
					if ($row['password'] == $password) {
						return $row;
					} else {
						$this->errmsg = 'password does not match.';
					}
					break;
				} else {
					$this->errmsg = 'userid not found.';
				}
			}
		} else {
			$this->errmsg = 'no user data not found.';
		}
		return array();
	}
	
	function getErrorMsg(){
		return $this->errmsg;
	}
	
}