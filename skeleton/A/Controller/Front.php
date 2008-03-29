<?php
include_once 'A/Locator.php';
include_once 'A/DL.php';

class A_Controller_Front {
	protected $_mapper;
	protected $_error_action;
	protected $_prefilters;
	protected $_actions = array();	// history of actions run
	protected $_error = 0;
	
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

    public function getActions() {
		return $this->_actions;
    }

    public function isError() {
		return $this->_error;
    }

    public function run($locator) {
		if (isset($locator) && method_exists($locator, 'set')) {
			$locator->set('Mapper', $this->_mapper);		// set mapper in registry for mvc loader to use
		}
        $action = $this->_mapper->doMapping($locator);
		$error_action = $this->_error_action;
        $n = 0;
        while ($action) {
			$class  = $this->_mapper->buildClass($action->class);
	        $method = $this->_mapper->buildMethod($action->method);
			if ($action->dir == '') {
				$dir = $this->_mapper->getPath();
			} else {
	    	    $dir = $action->dir;
			}
			$this->_actions[] = $action;	// save history of actions
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
				} else {
					$this->_error = 2;		// no known method to dispatch
				}
			} elseif ($error_action) {
				$action = $error_action;
				$error_action = null;
			} elseif ($n == 0) {
				$this->_error = 1;			// cannot load class and not error action 
			}
			++$n;
        }
		return $this->_error;
    }
}
