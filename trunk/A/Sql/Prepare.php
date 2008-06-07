<?php

class A_Sql_Prepare {
	protected $statement = '';			// SQL template
	protected $db = null;				// object with escape() method
	protected $named_args = array();	// assoc array
	protected $numbered_args = array();	// 1-based indexed array
	protected $sql;						// prepared sql
	protected $quote_values = false;
	
	public function __construct(/*$statement, $args...*/) {
		$args = func_get_args();
		if (count($args) > 0) {
			$this->statement = array_shift($args);
			if (count($args) > 0) {
#echo '__construct1 ARGS=<pre>' . print_r($args, 1) . '</pre>';
				$this->bind($args);
			}
		}
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
		$args = func_get_args();
		// check for the case where they passed an array
		if ((count($args) == 1) && is_array($args[0])) {
			$args = $args[0];
		}
		if (count($args)) {
			$n = 1;
			// process each arg
			foreach ($args as $key1 => $arg1) {
#echo 'ARGS=<pre>' . print_r($arg, 1) . '</pre>';
				// arg may be an array or a string
				if (is_array($arg1)) {
					foreach ($arg1 as $key2 => $value) {
						// separate into numbered and named args
						if (is_numeric($key2)) {
							$this->numbered_args[$n++] = $value;
						} else {
							$this->named_args[$key2] = $value;
						}
					}
				} else {
					// separate into numbered and named args
					if (is_numeric($key1)) {
						$this->numbered_args[$n++] = $arg1;
					} else {
						$this->named_args[$key1] = $arg1;
					}
				}
			}
		}
#echo 'BIND ARGS=<pre>' . print_r($args, 1) . '</pre>';
#echo 'NAMED=<pre>' . print_r($this->named_args, 1) . '</pre>';
#echo 'NUMBERED=<pre>' . print_r($this->numbered_args, 1) . '</pre>';
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
