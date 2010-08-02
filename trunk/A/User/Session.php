<?php
/**
 * Access to user data from session 
 * 
 * @package A_User 
 */

class A_User_Session {
	protected $_data;
	protected $_session;
	protected $_namespace;
	
	
	public function __construct($session, $namespace='A_User_Session') {
		$this->_session = $session;
		$this->_namespace = $namespace;
	}

	public function setSession($session) {
		$this->_session = $session;
		return $this;
	}

	public function getSession() {
		return $this->_session;
	}

	public function setNamespace($namespace) {
		$this->_namespace = $namespace;
		return $this;
	}

	public function getNamespace() {
		return $this->_namespace;
	}

	public function start() {
		$this->_session->start();
		if (!isset($this->_data)) {
			$this->_data =& $this->_session->getRef($this->_namespace);
		}
	}

	public function isLoggedIn() {
		$this->start();
		if ($this->_data && isset($this->_data['auth']) ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function logout() {
		if ($this->_data) {
			$this->_session->set($this->_namespace, null);	// unset all data
		}
	}
	
	public function login($data=array()) {
		if ($this->_namespace) {
			$this->start();
			$this->_data['auth'] = true;
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
		$this->start();
		if ($this->_namespace && isset($this->_data['data'] ) ) {
			if ($key) {
				if (isset($this->_data['data'][$key]) ) {
					return $this->_data['data'][$key];
				}
			} else {
				return $this->_data['data'];
			}
		}
	}
	
	public function set($key, $value) {
		if ($key && $this->_namespace) {
			$this->start();
			if ($value !== null) {
				$this->_data['data'][$key] = $value;
			} else {
				unset($this->_data['data'][$key]);
			}
		}
		return $this;
	}
	
	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function __call($name, $args) {
		$prefix = substr($name, 0, 3);
		$key = strtolower(substr($name, 3));
		$this->start();
		switch ($prefix) {
		case 'get':
			return isset($this->_data[$key]) ? $this->_data[$key] : null;
			break;
		case 'set':
			$this->_data[$key] = isset($args[0]) ? $args[0] : null;
			return $this;
			break;
		}
	}

	public function merge($data) {
		if (is_array($data) && $this->_namespace) {
			$this->start();
			if (isset($this->_data['data']) && is_array($this->_data['data'])) {
				$this->_data['data'] = array_merge($this->_data['data'], $data);
			} else {
				$this->_data['data'] = $data;
			}
		}
		return $this;
	}
	
	public function close() {
		$this->_session->close();
		//session_write_close();
	}

}
