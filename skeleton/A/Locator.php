<?php

class A_Locator {
   protected $_obj = array();
   protected $_reg = array();
   protected $file_extension;
    
	public function __construct($ext='.php') {
    	$this->file_extension = $ext;
	}

	public function register($dl) {
		$this->_reg[$dl->class] = $dl;
	}

	public function load($file, $dir='', $file_extension='') {
		if ($dir) {
			$dir = rtrim($dir, '/') . '/';
		}
		$path = $dir . $file . $file_extension;
		if (($dir == '') || file_exists($path)) {		// either in search path or absolute path exists
			$result = include($path);
			return $result !== false;
		} else {
			return false;
		}
	}

	public function loadClass($class='', $dir='') {
		if (class_exists($class)) {
			return true;
		} else {
			$file = str_replace(array('_','-'), array('/','_'), $class);
			$class = str_replace('-', '_', $class);
			return self::load($file, $dir, isset($this->file_extension) ? $this->file_extension : '.php')
					&& class_exists($class);
		}
	}

	public function get($name, $class='', $dir='') {
		if (isset($this->_obj[$name])) {
			return $this->_obj[$name];		// return registered object
		} else {
			$obj = null;
			$param = null;
			if ($class) {
			    if (func_num_args() > 3) {
				    $param = array_slice(func_get_args(), 3);	// get params after name/clas/dir
				    // if only one param then pass the param rather than an array of params
				    if (count($param) == 1) {
				    	$param = $param[0];
				    }
			    }
			} elseif (isset($this->_reg[$name]->class)) {
				$class = $this->_reg[$name]->class;
				$dir = $this->_reg[$name]->dir;
				$param = $this->_reg[$name]->param;
			}
			if ($class) {
				if (! class_exists($class)) {
					$this->loadClass($class, $dir);
				}
			}
/*
// param is name of registered object the pass it a param
			if (is_string($param) && isset($this->_obj[$param])) {
				$param = $this->_obj[$this->_reg[$name]->param];
			}
*/
			if (class_exists($class)) {
				$obj = new $class($param);
			}
			if ($name) {
				$this->_obj[$name] = $obj;
			}
		}
		return $obj;
	}

	public function set($name, $value) {
		$this->_obj[$name] = $value;
		return $this;
	}

}
