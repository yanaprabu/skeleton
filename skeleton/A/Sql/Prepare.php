<?php

class A_Sql_Prepare {
	protected $statement;
	protected $db;						// object with escape() method
	protected $named_args = array();	// assoc array
	protected $numbered_args = array();	// 1-based indexed array
	protected $sql;						// prepared sql
	protected $quote_values = false;
	
	public function __construct($statement='', $db=null) {
		$this->statement = $statement;
		$this->db = $db;
	}
		
	public function setDb($db) {
		$this->db = $db;
		return $this; 
	}
		
	public function quoteValues($flag=true) {
		$this->quote_values = $flag;
		return $this; 
	}
		
	public function quoteEscape($value) {
		$value = $this->db ? $this->db->escape($value) : addslashes($value);
		return $this->quote_values ? "'" . $value . "'" : $value;
	}

	public function statement($statement) {
		$this->statement = $statement;
		return $this; 
	}
		
	public function bind(/* args, ... */) {
		$numargs = func_num_args();
		if ($numargs > 1) {
			$args = func_get_args();
			$n = 1;
			foreach ($args as $arg) {
				if (is_array($arg)) {
					$this->named_args = array_merge($this->named_args, $arg);
				} else {
					$this->numbered_args[$n++] = $arg;
				}
			}
		}
		return $this; 
	}
	
	public function render($db=null) {
		if ($this->statement) {
			// set object with escape() method if passed
			if ($db !== null) {
				$this->db = $db;
			}
			$statement = $this->statement;
			if ($this->named_args) {
				// escape all values
				foreach ($this->named_args as $name => $value) {
					$this->named_args[$name] = $this->quoteEscape($value);
				}
				// replace array keys found in statement with values
				$statement = str_replace(array_keys($this->named_args), array_values($this->named_args), $statement);
			}
			if ($this->numbered_args && (strpos($statement, '?') !== false)) {
				// split on ? and reassemble inserting values
				$statement_array = explode('?', $statement);
				$this->sql = $statement_array[0];
				$n = 1;
				foreach ($this->numbered_args as $arg) {
					$this->sql .= $this->quoteEscape($arg) . $statement_array[$n++];
				}
			} else {
				$this->sql = $statement;
			}
		}
		return $this->sql;
	}
	
	public function __toString() {
		return $this->render();
	}

}
