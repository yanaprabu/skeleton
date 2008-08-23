<?php
include_once 'A/Filter/Abstract.php';
/**
 * Convert a string to uppercase
 * 
 * @package A_Filter 
 */

class A_Filter_Toupper extends A_Filter_Abstract {

	public function filter () {
		return strtoupper($this->getValue());
	}

}
