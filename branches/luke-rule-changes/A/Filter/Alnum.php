<?php
/**
 * Filter a string to leave only alpha-numeric characters
 * 
 * @package A_Filter 
 */

class A_Filter_Alnum {
	
	public function run ($value) {
		return preg_replace('/[^[:alnum:]]/', '', $value);
	}

}
