<?php
/**
 * Filter a string to leave only digits
 * 
 * @package A_Filter 
 */

class A_Filter_Digit {
	
	public function run ($value) {
		return preg_replace('/[^[:digit:]]/', '', $value);
	}

}
