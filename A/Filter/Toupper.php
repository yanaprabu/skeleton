<?php
/**
 * Convert a string to uppercase
 * 
 * @package A_Filter 
 */

class A_Filter_Toupper {

	public function run ($value) {
		return strtoupper($value);
	}

}
