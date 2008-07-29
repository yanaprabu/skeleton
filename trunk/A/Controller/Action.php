<?php
/**
 * Basic MVC controller functionality
 * 
 * @package A_Controller 
 */

class A_Controller_Action {
	const APP = 'app';
	const MODULE = 'module';
	const CONTROLLER = 'controller';
	const ACTION = 'action';
	protected $locator;
	protected $request;
	protected $response;
	protected $load = null;
	protected $helpers = array();
	
	public function __construct($locator=null){
	    if ($locator) {
			$this->locator = $locator;
			$this->request = $locator->get('Request');
			$this->response = $locator->get('Response');
	    }
	}
	 
	protected function forward($dir, $class, $method='run', $args=null){
		$forward = new A_DL($dir, $class, $method, $args=null);
		return $forward;
	}
 
	protected function load($scope=null) {
		if (isset($this->load)) {
			$this->load->load($scope);
		} else {
			include_once "A/Controller/Helper/Load.php";
			$this->load = new A_Controller_Helper_Load($this->locator, $this, $scope);
		}
		return $this->load;
	}
 
	protected function flash($name=null, $value=null) {
		if (! isset($this->flash)) {
			include_once "A/Controller/Helper/Flash.php";
			$this->flash = new A_Controller_Helper_Flash($this->locator);
		}
		if ($name) {
			if ($value) {
				$this->flash->set($name, $value);
			} else {
				return $this->flash->get($name);
			}
		}
		return $this->flash;
	}
 
	public function setHelper($name, $helper) {
		if ($name) {
			$this->helpers[$name] = $helper;
		}
		return $this;
	}
 
	protected function helper($name) {
		if (isset($this->helpers[$name])) {
			return $this->helpers[$name];
		}
	}
 
}
