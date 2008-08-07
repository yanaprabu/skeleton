<?php
/**
 * Filter a string to leave only alpha characters
 * 
 * @package A_Filter 
 */

class A_Filter_Alpha {
	
	public function run ($value) {
		return preg_replace('/[^[:alpha:]]/', '', $value);
	}

}
