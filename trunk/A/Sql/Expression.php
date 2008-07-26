<?php
include_once 'A/Sql/Statement.php';
/**
 * Generate SQL logical expression/equation
 * 
 * @package A_Sql 
 */

class A_Sql_Expression extends A_Sql_Statement {	
	protected $data = array();
	protected $operators = array('!=', '>', '<', '>=', '<=', '=', '<>', 'IN', 'NOT IN', ' LIKE ', ' NOT LIKE ');	
	protected $db;
	protected $escape = true;

	public function __construct($data, $value=null, $escape=true) {
		if ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $data;
		}	
		$this->escape = (bool)$escape;
	}
		
	public function quoteEscape($value) {
		if (is_numeric($value)) {
			return $value;
		}
		$value = $this->db ? $this->db->escape($value) : addslashes($value);
		return "'" . $value . "'";
	}


	protected function buildExpression($key, $value) {
		if (is_int($key)) {
			$key = $value;
			$value = null;
		} else {
			$key = trim($key);
		}
		if (preg_match('@('. implode('|', $this->operators).')$@i', $key, $matches)) { //operator detected
			if (is_array($value)) {
				$value = '('. implode(', ', $this->escape ? array_map(array($this, 'quoteEscape'), $value) : $value) .')';
			} elseif ($this->escape) {
				$value = $this->quoteEscape($value);
			}
			return str_replace($matches[1], '', $key) . $matches[1] .' '. $value;
		} elseif ($value !== null) {
			return $key .' = '. ($this->escape ? $this->quoteEscape($value) : $value);
		} 
		return $key;
	}
	
	public function render($logic='AND') {
		if (!is_array($this->data)) {
			$this->data = array($this->data);
		}
		$logic = $logic==',' ? ', ' : ' '.trim($logic).' ';
		return implode($logic, array_map(array($this, 'buildExpression'), array_keys($this->data), array_values($this->data)));
	}

	public function __toString() {
		return $this->render();
	}
}
