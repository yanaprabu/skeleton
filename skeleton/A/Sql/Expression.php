<?php

include_once 'A/Sql/Abstract.php';

class A_Sql_Expression extends A_Sql_Abstract {

	/**
	 * data
	*/		
	protected $data = array();
	/**
	 * operators
	*/	
	protected $operators = array('>', '<', '>=', '<=', '=', '<>', 'NOT IN', 'IN');

	/**
	 * operators
	*/		
	protected $escapeCallback;
	
	/**
	 * __construct()
	*/	
	public function __construct($data, $value) {
		if ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $data;
		}	
	}

	/**
	 * setEscapeCallback()
	*/		
	public function setEscapeCallback($db) {
		$this->escapeCallback = $db;
	}			

	/**
	 * render()
	*/		
	public function render() {
		if (is_string($this->data)) {
			$this->data = array($this->data);
		}
		return '('. implode(' AND ', array_map(array($this, 'buildExpression'), array_keys($this->data), array_values($this->data))).')';
	}

	/**
	 * escape()
	*/		
	public function escape($value) {
		return $this->quoteValue($this->escapeCallback ? $this->escapeCallback->escape($value) : addslashes($value));
	}

	/**
	 * buildExpression()
	*/	
	protected function buildExpression($key, $value) {
		if (is_int($key)) {
			$key = $value;
			$value = null;
		}
		if (preg_match('!('. implode('|', $this->operators).')$!i', $key, $matches)) { //operator detected
			if (is_array($value)) {
				$value = '('. implode(', ', array_map(array($this, 'escape'), $value)) .')';
			} else {
				$value = $this->escape($value);
			}
			return str_replace($matches[1], '', $key) . ' ' . $matches[1] .' '. $value;
		} elseif ($value !== null) {
			return $key .' = '. $this->quoteValue($this->escape($value));
		}
		return $key;
	}
}
