<?php

class A_Db_Prepare {	protected $statement;	protected $db;						// object with escape() method
	protected $named_args = array();	// assoc array
	protected $numbered_args = array();	// 1-based indexed array
	protected $sql;						// prepared sql
	
	public function __construct($statement='') {
		$this->statement = $statement;
		$this->db = $this;
	}
		
	public function escape($value) {
		return addslashes($value);		// at least do something
	}
	
	public function statement($statement) {
		$this->statement = $statement;
	}
		
	public function bind(/* args, ... */) {
		$numargs = func_num_args();
		if ($numargs > 1) {
			$args = func_get_args();
#			$this->template = array_shift($args);		// 1st arg is template
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
	
	public function execute($db=null) {
		$this->sql = '';
		if ($this->statement) {
			// set object with escape() method if passed
			if ($db !== null) {
				$this->db = $db;
			}
			$statement = $this->statement;
			if ($this->named_args) {
				// escape all values
				foreach ($this->named_args as $name => $value) {
					$this->named_args[$name] = $this->db->escape($value);
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
					$this->sql .= $this->db->escape($arg) . $statement_array[$n++];
				}
			} else {
				$this->sql = $statement;
			}
		}
		return $this->sql;
	}
	
}
