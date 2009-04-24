<?php

class usersModel {
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