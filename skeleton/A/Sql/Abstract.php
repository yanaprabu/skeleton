<?php

abstract class A_Sql_Abstract {
	/** 
	 * quoteValue
	*/
	public function quoteValue($value) {
		$value = trim($value, '\''); //incase the user already quoted the value
		if (preg_match('/^[a-z\_]*\(/i', $value) || ctype_digit($value)) { //detect if the value is a function or digits
			return $value;
		}		
		return '\''. $value .'\'';
	}	
}
