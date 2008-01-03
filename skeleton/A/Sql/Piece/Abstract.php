<?php

class A_Sql_Piece_Abstract {
	protected $nameQuote = '`';

	public function quoteName($name) {
		$name = trim($name); //strip whitespace
		$name = trim($name, $this->nameQuote); //strip quotes if they were passed
		$name = str_ireplace(' AS ', $this->nameQuote .' AS '. $this->nameQuote, $name); //table aliases need backticks between AS
		return $this->nameQuote . $name . $this->nameQuote;
	}
	
	public function quoteValue($value) {
		$value = trim($value, '\''); //incase the user already quoted the value
		if (preg_match('/^[A-Z\_]*\(/i', $value) || ctype_digit($value)) { //detect if the value is a function or digits
			return $value;
		}		
		return '\''. $value .'\'';
	}	
}
