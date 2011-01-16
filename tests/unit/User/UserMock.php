<?php

class UserMock {
	public $loggedin = false;
	public $data = array();
	
	public function __construct() {
	}

	public function get($key='') {
		return isset($this->data[$key]) ? $this->data[$key] : null;
	}
	
	public function set($key, $value) {
		$this->data[$key] = $value;
		return $this;
	}
	
	public function setLoggedIn($loggedin) {
		$this->loggedin = $loggedin;
	}

	public function isLoggedIn() {
		return $this->loggedin;
	}
}

