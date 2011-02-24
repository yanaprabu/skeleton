<?php
#include_once 'A/Filter/Abstract.php';
/**
 * Filter a string to leave only alpha-numeric characters
 * 
 * @package A_Filter 
 */

class A_Filter_Alnum extends A_Filter_Base {
	
	public function filter () {
		return preg_replace('/[^[:alnum:]]/', '', $this->getValue());
	}

}
