<?php

class A_Db_Sql_Common {
	protected $db = null;
	protected $sql = '';
	protected $nameQuote = '`';

	function setDb($db=null) {
		if ($db) {
			$this->db = $db;
		}
	}
	
	public function escape($value) {
		return addslashes($value); //at least do something. Will depend on db
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

	public function equation($name, $op, $value) {
		$value = $this->db ? $this->db->escape($value) : $this->escape($value);
		return $this->quoteName($name) . " $op " . $this->quoteValue($value);
	}

	public function equationList($list, $op='=', $separator=',') {
		if (is_array($list)) {
			$tmp = array();
			foreach ($list as $name => $value) {
				$tmp[] = $this->equation($name, $op, $value);
			}
			return implode($separator, $tmp);
		}
		return $list;
	}

	public function valueList($list) {
		if (is_array($list)) {
			$tmp = array();
			foreach ($list as $value) {
				$value = $this->db ? $this->db->escape($value) : $this->escape($value);
				$tmp[] = $this->quoteName($value);
			}
			return implode(', ', $tmp);
		}
		return $list;
	}

	public function nameList($list) {
		if (is_array($list)) {
			$tmp = array();
			foreach ($list as $name) {
				$tmp[] = $this->quoteName($name);
			}
			return implode(', ', $tmp);
		}
		return $list;
	}

}