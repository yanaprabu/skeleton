<?php
/**
 * Create object of this class to pass to Front Controller preFilter() method
 * to set one or more properties in all dispatched Action objects with a given value
 * 
 * Created on Sep 5, 2007
 *
 * @package A_Controller
 * @subpackage Front
 */

class A_Controller_Front_Injector {
	protected $properties;

	/*
	 * Parameters can be either (array('name'=>'value',...) or ('name', 'value')
	 */
	public function __construct($property, $value=null) {
		if (is_array($property)) {
			$this->properties = $property;
		} else {
			$this->properties[$property] = $value;
		}
	}
	
	public function run($controller) {
		foreach ($this->properties as $property => $value) {
			$controller->$property = $value;
		}
	}
	
}
