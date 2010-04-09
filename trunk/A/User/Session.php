<?php
/**
 * Access to user data from session 
 * 
 * @package A_User 
 */

class A_User_Session {
	protected $data = array();
	protected $session;
	protected $namespace;
	
	
	public function __construct($session, $namespace='A_User_Session') {
		$this->session = $session;
		$this->namespace = $namespace;
	}

	public function setSession($session) {
		$this->session = $session;
		return $this;
	}

	public function getSession() {
		return $this->session;
	}

	public function setNamespace($namespace) {
		$this->namespace = $namespace;
		return $this;
	}

	public function getNamespace() {
		return $this->namespace;
	}

	public function isLoggedIn() {
		$this->session->start();
		if ($this->namespace && isset($_SESSION[$this->namespace]['auth']) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function logout() {
		if ($this->namespace) {
			$this->session->start();
			unset ($_SESSION[$this->namespace]);
#			session_unregister ($this->namespace);
		}
	}
	
	public function login($data=array()) {
		if ($this->namespace) {
			$this->session->start();
			$_SESSION[$this->namespace]['auth'] = true;
			$this->merge($data);
		}
	}
	
	/**
	 * depricated name for isLoggedIn()
	 */
	public function isSignedIn() {
		return $this->isLoggedIn();
	}
	
	/**
	 * depricated name for logout()
	 */
	public function signout() {
		$this->logout();
	}
	
	/**
	 * depricated name for login()
	 */
	public function signin($data=array()) {
		$this->login($data);
	}
	
	public function get($key='') {
		$this->session->start();
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
			$this->session->start();
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
			$this->session->start();
			if (isset($_SESSION[$this->namespace]['data']) && is_array($_SESSION[$this->namespace]['data'])) {
				$_SESSION[$this->namespace]['data'] = array_merge($_SESSION[$this->namespace]['data'], $data);
			} else {
				$_SESSION[$this->namespace]['data'] = $data;
			}
		}
		return $this;
	}
	
	public function close() {
		$this->session->close();
		//session_write_close();
	}

}
