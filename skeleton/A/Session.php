<?php

class A_Session {
	protected $namespace;
	protected $regenerate;
	
	public function __construct($namespace='Session', $regenerate=false) {
		$this->namespace = $namespace;
		$this->regenerate = $regenerate;
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
			if ($this->regenerate) {
				session_regenerate_id();
			}
		}
	}
	
	public function get($name) {
		$this->start();
		return (isset($_SESSION[$this->namespace][$name]) ? $_SESSION[$this->namespace][$name] : null);
	}
	
	public function set($name, $value) {
		if ($name) {
			$this->start();
			$_SESSION[$this->namespace][$name] = $value;
		}
		return $this;
	}
	
	public function has($name) {
		$this->start();
		return isset($_SESSION[$this->namespace][$name]);
	}
	
	public function close() {
		session_write_close();
	}
	
}
