<?php

class A_Db_Sql_Common {
	protected $nameQuote = '`';

	public function escape($value) {
		return addslashes($value); //at least do something. Will depend on db
	}
	
	public function equation($field, $op, $value) {
		return $this->quoteValue($field) . " $op " . $this->quoteValue($this->db->escape($value));
	}

	public function quoteValue($value) {
		$value = trim($value, '\''); //incase the user already quoted the value
		if (preg_match('/^[A-Z\_]*\(/i', $value) || ctype_digit($value)) { //detect if the value is a function or digits
			return $value;
		}		
		return '\''. $value .'\'';
	}
	
	public function quoteName($name) {
		$name = str_ireplace(' AS ', $this->nameQuote .' AS '. $this->nameQuote, $name); //table aliases need backticks between AS
		return $this->nameQuote . trim($name, $this->nameQuote) . $this->nameQuote;
	}
}