<?php
/**
 * Convert a string to lowercase
 * 
 * @package A_Filter 
 */

class A_Filter_Tolower {

public function run ($value) {
	return strtolower($value);
}

}
