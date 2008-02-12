<?php

class A_Session {
	protected $_data = array();
	protected $a_namespace = 'A_Session';
	protected $namespace;
	protected $regenerate;
	protected $isstarted = false;
	
	public function __construct($namespace=null, $regenerate=false) {
		$this->namespace = $namespace;
		$this->regenerate = $regenerate;
		$this->_init();
	}
	
	private function _init() {
		if (session_id() != '') {
			if ($this->namespace) {
				$this->_data =& $_SESSION[$this->namespace];
			} else {
				$this->_data =& $_SESSION;
			}
			$this->isstarted = true;	// already started
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
			$this->_init();
			if ($this->regenerate) {
				session_regenerate_id();
			}
		}
	}
	
	public function get($name, $default=null) {
		$this->start();
		return isset($this->_data[$name]) ? $this->_data[$name] : $default;
	}
	
	public function set($name, $value, $count=0) {
		if ($name) {
			$this->start($this->namespace);
			$this->_data[$name] = $value;
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
	
	public function expire($name, $count=0) {
		$this->start();
		$_SESSION[$this->a_namespace]['expire'][$name] = $count;
	}
	
	protected function doExpiration() {
		if (isset($_SESSION[$this->a_namespace]['expire'])) {
			foreach ($_SESSION[$this->a_namespace]['expire'] as $name => $value) {
				if ($value > 1) {
					--$_SESSION[$this->a_namespace]['expire'][$name];		// decrement counter if > 1
				} else {
					unset($this->_data[$name]);								// remove session var
					unset($_SESSION[$this->a_namespace]['expire'][$name]);	// remove counter
				}
			}
		}
	}
	
	public function close() {
		session_write_close();
	}
	
	public function destroy() {
		session_destroy();
	}
	
}
