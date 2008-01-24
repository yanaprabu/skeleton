<?php

class A_Controller_Action {
	protected $locator;
	protected $loader = null;
	
	public function __construct($locator){
	    $this->locator = $locator;
	}
	 
	protected function load($module=null) {
		if (! $this->loader) {
		    include_once 'A/Controller/Action/Loader.php';
			$this->loader = new A_Controller_Action_Loader($this->locator);
		}
		return $this->loader->load($module);
	}

	protected function dispatch($dir, $class, $method='run', $args=null){
		$dl = new A_DL($dir, $class, $method, $args=null);
		return $dl->run($this->locator);
	}
 
	protected function forward($dir, $class, $method='run', $args=null){
		$forward = new A_DL($dir, $class, $method, $args=null);
		return $forward;
	}
 
}
