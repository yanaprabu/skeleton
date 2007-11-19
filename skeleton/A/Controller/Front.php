<?php
if (! class_exists('A_Locator')) include 'A/Locator.php';
if (! class_exists('A_DL')) include 'A/DL.php';

class A_Controller_Front {
   protected $mapper;
   protected $error_action;
   protected $prefilters;

    public function __construct($mapper, $error_action, $prefilters=array()) {
    	$this->mapper = $mapper;
    	$this->error_action = $error_action;
    	$this->prefilters = $prefilters;
    }

    public function addPreMethod($method, $action) {
    	$this->prefilters[$method] = $action;
    }

    public function addPreFilter($name, $prefilter) {
   		$this->prefilters[$name] = $prefilter;
    }

    public function run($locator) {
        $action = $this->mapper->doMapping($locator);
		$error_action = $this->error_action;
        while ($action) {
			$class  = $this->mapper->buildClass($action->class);
	        $method = $this->mapper->buildMethod($action->method);
			if ($action->dir == '') {
				$dir = $this->mapper->getPath();
			} else {
	    	    $dir = $action->dir;
			}
	        $action = null;
	        $result = $locator->loadClass($class, $dir);
			if ($result) {
				$class = str_replace('-', '_', $class);
		        $controller = new $class($locator);
	
				if ($this->prefilters) {
					foreach (array_keys($this->prefilters) as $name) {
						if (is_object($this->prefilters[$name])) {
							if (! is_a($this->prefilters[$name], 'A_DL')) {
								// pass controller to DI object to modify
								$change_action = $this->prefilters[$name]->run($controller);
							} elseif (method_exists($controller, $name)) {
								// pre-execute method if it exists 
								$change_action = $controller->{$name}($locator);
							} else {
								$change_action = null;
							}
							if ($change_action) {
								if (is_object($change_action)) {
									$action = $change_action;
								} elseif (is_object($this->prefilters[$name])) {
									$action = $this->prefilters[$name];
								} else {
									$action = $this->error_action;
								}
								continue 2;
							}
						} elseif (is_string($this->prefilters[$name]) && function_exists($this->prefilters[$name])) {
							$func = $this->prefilters[$name];
							$func($controller);
						}
					}
				}
				
				if (! method_exists($controller, $method)) {
					$method = $this->mapper->default_method;
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
