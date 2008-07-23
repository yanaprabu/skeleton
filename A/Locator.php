<?php

class A_Locator {
   protected $_obj = array();
   protected $_reg = array();
   protected $_dir = '';
   protected $_inject = array();
   protected $_extension;
    
	public function __construct($ext='.php') {
    	$this->_extension = $ext;
	}

/*
// allow injecting via constructor or setters
	$inject = array( 
		'Foo' => array( 
			'__construct' => array('Boo'), 
			'setBar' => array('Bar'), 
			'setBaz' => array('Baz', 'Jazz'),
			), 
		); 
*/
	public function register($dl) {
		if (is_string($dl)) {
			$params = func_get_params();
			array_shift($params);
			$dl = array($dl => $params);
		}
		if (is_array($dl)) {
			$this->_inject = array_merge($this->_inject, $dl);
		} elseif ($dl instanceof A_DL) {
			$this->_reg[$dl->class] = $dl;
		}
		return $this;
	}

	public function setDir($dir='') {
		$this->_dir = $dir;
		return $this;
	}

	public function loadClass($class='', $dir='', $autoload=false) {
		if (class_exists($class, $autoload)) {
			return true;
		}
		$file = str_replace(array('_','-'), array('/','_'), $class);
		$class = str_replace('-', '_', $class);
		if ($dir) {
			$dir = rtrim($dir, '/') . '/';
		} elseif (isset($this->_dir)) {
			$dir = $this->_dir;
		}
		$path = $dir . $file . (isset($this->_extension) ? $this->_extension : '.php');
		if (($dir == '') || file_exists($path)) {		// either in search path or absolute path exists
			$result = include($path);
			$result = $result !== false;
		} else {
			$result = false;
		}
		return $result && class_exists($class, $autoload);
	}

	public function get($name='', $class='') {
		$param = null;
	    if (func_num_args() > 2) {
		    $param = array_slice(func_get_args(), 2);	// get params after name/clas/dir
		    // if only one param then pass the param rather than an array of params
		    if (count($param) == 1) {
		    	$param = $param[0];
		    }
	    }
		if ($name) {
			if (isset($this->_obj[$name])) {
				return $this->_obj[$name];		// return registered object
			} elseif (isset($this->_reg[$name]->class)) {
				$this->setDir($this->_reg[$name]->dir);
				return $this->newInstance($this->_reg[$name]->class, $this->_reg[$name]->param);
			} elseif ($class) {
				$obj = $this->newInstance($class, $param);
				if ($obj) {
					$this->_obj[$name] = $obj;
				}
				return $obj;		// return registered object
			}
		} elseif ($class) {
			return $this->newInstance($class, $param);
		}
	}

	public function newInstance($class='') {
		$obj = null;
		// get dir and clear
		$dir = $this->_dir;
		$this->_dir = null;
		if ($class) {
			$param = null;
		    if (func_num_args() > 1) {
			    $param = array_slice(func_get_args(), 1);	// get params after name/clas/dir
			    // if only one param then pass the param rather than an array of params
			    if (count($param) == 1) {
			    	$param = $param[0];
			    }
		    }

			if ($this->loadClass($class, $dir)) {
				// do constructor injection here
				if (isset($this->_inject[$class])) {
					$inject = array();
					foreach ($this->_inject[$class] as $method => $params) {
						foreach ($params as $key => $param) {
							if (isset($param['type'])) {
								switch ($param['type']) {
								// create new object from class
								case 'class':
									$inject[$method][$key] = $this->get('', $param['value']);
									break;
								// get container from locator
								case 'locator':
									$container = $this->get($param['name']);
									if ($container) {
										$inject[$method][$key] = $container->get($param['value']);
									}
									break;
								// set to value
								default:
									$inject[$method][$key] = $param['value'];
								}
							}
						}
					}
					if (isset($inject['__construct'])) {
						$reflector = new ReflectionClass($class);
						$obj = $reflector->newInstanceArgs($inject['__construct']);
						unset($inject['__construct']);
					} else {
						$obj = new $class($param);
					}
					// do setter injection
					if ($inject) {
						foreach ($inject as $method => $params) {
							call_user_func_array(array($obj, $method), $params);
						}
					}
				} else {
					$obj = new $class($param);
				}
			}
		}
		return $obj;
	}

	public function set($name, $value) {
		if ($value !== null) {
			$this->_obj[$name] = $value;
		} else {
			unset($this->_obj[$name]);
		}
		return $this;
	}

	public function has($name) {
		return isset($this->_obj[$name]);
	}

}
