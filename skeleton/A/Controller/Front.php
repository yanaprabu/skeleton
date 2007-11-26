<?php
if (! class_exists('A_Locator')) include 'A/Locator.php';
if (! class_exists('A_DL')) include 'A/DL.php';

class A_Controller_Front {
   protected $_mapper;
   protected $_error_action;
   protected $_prefilters;

    public function __construct($mapper, $error_action, $prefilters=array()) {
    	$this->_mapper = $mapper;
    	$this->_error_action = $error_action;
    	$this->_prefilters = $prefilters;
    }

    public function addPreMethod($method, $action) {
    	$this->_prefilters[$method] = $action;
    }

    public function addPreFilter($name, $prefilter) {
   		$this->_prefilters[$name] = $prefilter;
    }

    public function run($locator) {
        $action = $this->_mapper->doMapping($locator);
		$error_action = $this->_error_action;
        while ($action) {
			$class  = $this->_mapper->buildClass($action->class);
	        $method = $this->_mapper->buildMethod($action->method);
			if ($action->dir == '') {
				$dir = $this->_mapper->getPath();
			} else {
	    	    $dir = $action->dir;
			}
	        $action = null;
	        $result = $locator->loadClass($class, $dir);
			if ($result) {
				$class = str_replace('-', '_', $class);
		        $controller = new $class($locator);
	
				if ($this->_prefilters) {
					foreach (array_keys($this->_prefilters) as $name) {
						if (is_object($this->_prefilters[$name])) {
							if (! ($this->_prefilters[$name] instanceof A_DL)) {
								// pass controller to DI object to modify
								$change_action = $this->_prefilters[$name]->run($controller);
							} elseif (method_exists($controller, $name)) {
								// pre-execute method if it exists 
								$change_action = $controller->{$name}($locator);
							} else {
								$change_action = null;
							}
							if ($change_action) {
								if (is_object($change_action)) {
									$action = $change_action;
								} elseif (is_object($this->_prefilters[$name])) {
									$action = $this->_prefilters[$name];
								} else {
									$action = $this->_error_action;
								}
								continue 2;
							}
						} elseif (is_string($this->_prefilters[$name]) && function_exists($this->_prefilters[$name])) {
							$func = $this->_prefilters[$name];
							$func($controller);
						}
					}
				}
				
				if (! method_exists($controller, $method)) {
					$method = $this->_mapper->default_method;
				}
				if (method_exists($controller, $method)) {
					$action = $controller->{$method}($locator);
				}
			} else {
				$action = $error_action;
				$error_action = null;
			}
        }
    }

}
