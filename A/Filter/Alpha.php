<?php
include_once 'A/Filter/Abstract.php';
/**
 * Filter a string to leave only alpha characters
 * 
 * @package A_Filter 
 */

class A_Filter_Alpha extends A_Filter_Abstract {
	
	public function filter () {
		return preg_replace('/[^[:alpha:]]/', '', $this->getValue());
	}

}