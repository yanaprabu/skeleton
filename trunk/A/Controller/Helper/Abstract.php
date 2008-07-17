<?php

/**
 * A_Controller_Helper_Abstract
 * 
 * Abstract class to be extended to create application helper classes
 *
 * @package A_Controller
 */
abstract class A_Controller_Helper_Abstract {
	/**
	 * calling controller
	 * @var A_Controller_Action object
	 */
	protected $controller = null;
   	
	public function __construct(A_Controller_Action $controller) {
		$this->controller = $controller;
	}
   
	/**
	 * return instance of calling controller
	 * @return A_Controller_Action
	 */
	public function getController() {
		return $this->controller;
	}
}