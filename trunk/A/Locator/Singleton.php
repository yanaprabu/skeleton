<?php
/**
 * A_Locator_Singleton
 * 
 * Allows static Singleton access to an A_Locator (or other) obejct
 *
 * @package A_Locator
 */

class A_Locator_Singleton {
	private static $_instance;
       
	function setInstance($instance) {
		$this->_instance = $instance;
	}

	function getInstance() {
		if (! isset($this->_instance)) {
			#include_once "A/Locator.php";
			$this->_instance = new A_Locator();
		}
		return $this->_instance;
	}

}