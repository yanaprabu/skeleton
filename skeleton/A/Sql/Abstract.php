<?php

class A_Sql_Abstract {
	public function quoteName($name) {
		if (strpos($name, ',')) { //if columns are list, need to apply quoting to all columns
			return implode(', ', array_map(array($this, 'quoteName'), explode(',', $name)));
		}
		$name = preg_replace('/\s{2,}/', ' ', trim($name)); //remove all excess spaces in columns list
		return preg_replace('/([a-z_]+[^`\s.]+)/i', '`$1`', $name);
	}
	
	public function quoteValue($value) {
		$value = trim($value, '\''); //incase the user already quoted the value
		if (preg_match('/^[a-z\_]*\(/i', $value) || ctype_digit($value)) { //detect if the value is a function or digits
			return $value;
		}		
		return '\''. $value .'\'';
	}	
}
