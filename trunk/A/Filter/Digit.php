<?php
include_once 'A/Filter/Abstract.php';
/**
 * Filter a string to leave only digits
 * 
 * @package A_Filter 
 */

class A_Filter_Digit extends A_Filter_Abstract {
	
	public function filter () { 
		return preg_replace('/[^[:digit:]]/', '', $this->getValue());
	}

}