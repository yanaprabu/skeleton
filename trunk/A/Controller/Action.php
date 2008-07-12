<?php

class A_Controller_Action {
	const APP = 'app';
	const MODULE = 'module';
	const CONTROLLER = 'controller';
	const ACTION = 'action';
	protected $locator;
	protected $loader = null;
	protected $helpers = array();
	
	public function __construct($locator=null){
	    $this->locator = $locator;
	}
	 
	protected function forward($dir, $class, $method='run', $args=null){
		$forward = new A_DL($dir, $class, $method, $args=null);
		return $forward;
	}
 
	protected function __call($name, $args=null) {
		$args = count($args) ? $args : null;
		if (! isset($this->helpers[$name])) {
		    $class = ucfirst($name);
		    if (in_array($name, array('load', 'flash'))) {
				include_once "A/Controller/Helper/$class.php";
				$class = "A_Controller_Helper_$class";
			// return object from registry
		    } elseif (isset($this->locator) && $this->locator->has($name)) {
		    	$obj = $this->locator->get($name);
		    	return $obj;
		    }
		    $this->helpers[$name] = new $class($this->locator, $args);
		} else {
			$this->helpers[$name]->__construct($this->locator, $args);
		}
		return $this->helpers[$name];
	}

}
