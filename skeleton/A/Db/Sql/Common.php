<?php

class A_Db_Sql_Common {
	
	public function __construct() {
	}
	
	public function escape($value) {
		$value = addslashes($value);
		return $value;		// at least do something
	}
	
	public function equation($field, $op, $value) {
		return "$field$op" . $this->quoteValue($this->db->escape($value));
	}

	public function quoteValue($value) {
		if (preg_match('/^[A-Z\_]*\(/', $value) == 0) {	// not a function
			$value = "'$value'";
		}
		return $value;
	}
	
}

