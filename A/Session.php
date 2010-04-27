<?php
/**
 * Encapsulate session data 
 * 
 * @package A_Session 
 */

class A_Session {
	protected $_data = array();
	protected $_a_namespace = 'A_Session';
	protected $_namespace;
	protected $_regenerate;
	protected $_isstarted = false;
	
	public function __construct($namespace=null, $regenerate=false) {
		$this->initNamespace($namespace);
		$this->_regenerate = $regenerate;
	}
	
	public function initNamespace($namespace=null) {
		if ($namespace) {
			$this->_namespace = $namespace;
		}
		if (session_id() != '') {
			if ($this->_namespace) {
				if (! isset($_SESSION[$this->_namespace])) {
					$_SESSION[$this->_namespace] = array();
				}
				$this->_data =& $_SESSION[$this->_namespace];
echo "USING NAMESPACE<br/>";
			} else {
				$this->_data =& $_SESSION;
echo "NO NAMESPACE<br/>";
			}
			$this->_isstarted = true;	// already started
			$this->doExpiration();
		}
	}
	
	public function setHandler($handler) 
	{
		session_set_save_handler(array(&$handler, 'open'),
									array(&$handler, 'close'),
									array(&$handler, 'read'),
									array(&$handler, 'write'),
									array(&$handler, 'destroy'),
									array(&$handler, 'gc'));
		register_shutdown_function('session_write_close');
		
		//ensure the session is restarted after changing the save handler
		session_destroy();
		session_start();
		session_regenerate_id();

		return $this;
	}
	
	public function start() {
		if (session_id() == '') {
			if (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
				session_cache_limiter('must-revalidate');
			}
			session_start();
			$this->initNamespace();
			if ($this->_regenerate) {
				session_regenerate_id();
			}
		}
	}
	
	public function get($name, $default=null) {
		$this->start();
		return isset($this->_data[$name]) ? $this->_data[$name] : $default;
	}
	
	public function & getRef($name=null) {
		$this->start();
		if ($name !== null) {
			if (! isset($this->_data[$name])) {
				$this->_data[$name] = array();
			}
			return $this->_data[$name];
		} else {
			return $this->_data;
		}
	}
	
	public function set($name, $value, $count=0) {
		if ($name) {
			$this->start();
			if ($value !== null) {
				$this->_data[$name] = $value;
			} else {
				unset($this->_data[$name]);
			}
			if ($count > 0) {
				$this->expire($name, $count);
			}
		}
		return $this;
	}
	
	public function has($name) {
		$this->start();
		return isset($this->_data[$name]);
	}
	
	public function __get($name) {
		return $this->get($name);
	}

	public function __set($name, $value) {
		return $this->set($name, $value);
	}

	public function expire($name, $count=0) {
		$this->start();
		$_SESSION[$this->_a_namespace]['expire'][$name] = $count;
	}
	
	protected function doExpiration() {
		if (isset($_SESSION[$this->_a_namespace]['expire'])) {
			foreach ($_SESSION[$this->_a_namespace]['expire'] as $name => $value) {
				if ($value > 0) {
					--$_SESSION[$this->_a_namespace]['expire'][$name];		// decrement counter if > 1
				} else {
					unset($this->_data[$name]);								// remove session var
					unset($_SESSION[$this->_a_namespace]['expire'][$name]);	// remove counter
				}
			}
		}
	}
	
	public function close() {
		session_write_close();
	}
	
	public function destroy() {
		if ($this->_namespace) {
			$_SESSION[$this->_namespace] = array();
		} else {
			$_SESSION = array();
		}
	}
	
}
