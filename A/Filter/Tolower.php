<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Convert a string to lowercase
 * 
 * @package A_Filter 
 */

class A_Filter_Tolower extends A_Filter_Abstract {

	public function filter () {
		return strtolower($this->getValue());
	}

}
