<?php

class A_User_Session {
	protected $data = array();
	protected $session;
	protected $namespace;
	
	
	public function __construct($session, $namespace='A_User_Session') {
		$this->session = $session;
		$this->namespace = $namespace;
	}

	public function setNamespace($namespace) {
		$this->namespace = $namespace;
		return $this;
	}

	public function isSignedIn() {
		if ($this->namespace && isset($_SESSION[$this->namespace]['auth']) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function signout() {
		if ($this->namespace) {
			unset ($_SESSION[$this->namespace]);
#			session_unregister ($this->namespace);
		}
	}
	
	public function signin($data=array()) {
		if ($this->namespace) {
			$_SESSION[$this->namespace]['auth'] = true;
			$this->merge($data);
		}
	}
	
	public function get($key='') {
		if ($this->namespace && isset($_SESSION[$this->namespace]['data'] ) ) {
			if ($key) {
				if (isset($_SESSION[$this->namespace]['data'][$key]) ) {
					return $_SESSION[$this->namespace]['data'][$key];
				}
			} else {
				return $_SESSION[$this->namespace]['data'];
			}
		}
	}
	
	public function set($key, $value) {
		if ($key && $this->namespace) {
			if ($value !== null) {
				$_SESSION[$this->namespace]['data'][$key] = $value;
			} else {
				unset($_SESSION[$this->namespace]['data'][$key]);
			}
		}
		return $this;
	}
	
	public function merge($data) {
		if (is_array($data) && $this->namespace) {
			if (isset($_SESSION[$this->namespace]['data']) && is_array($_SESSION[$this->namespace]['data'])) {
				$_SESSION[$this->namespace]['data'] = array_merge($_SESSION[$this->namespace]['data'], $data);
			} else {
				$_SESSION[$this->namespace]['data'] = $data;
			}
		}
		return $this;
	}
	
	public function close() {
		session_write_close();
	}

}
