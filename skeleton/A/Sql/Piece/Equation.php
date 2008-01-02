<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_Equation extends A_Sql_Piece_Abstract {
	protected $data = array();
	protected $operators = array('>', '<', '>=', '<=', '=', '<>', 'NOT IN', 'IN');
	
	public function __construct($data, $value) {
		if ($value !== null) {
			$this->data[$data] = $value;
		} else {
			$this->data = $data;
		}	
	}
	
	public function setEscapeCallback($db) {
		$this->escapeCallback = $db;
	}			
	
	public function render() {
		if (is_string($this->data)) {
			$this->data = array($this->data);
		}
		return array_map(array($this, 'buildExpression'), array_keys($this->data), array_values($this->data));
	}
	
	/** 
	 * TODO: Add support for user passing formatting string, that needs to be escaped
	 *
	 * Currently this function only supports array('column operator' => value) format or for a straight
	 * equals comparison array('column' => 'value')
	*/
	protected function buildExpression($key, $value) {
		if (preg_match('!('. implode('|', $this->operators).')$!i', $key, $matches)) { //operator detected
			if (is_array($value)) {
				foreach ($value as &$element) {
					$element = $this->quoteValue($this->escape($value));
				}
				$value = '('. implode(', ', $value) .')';
			} else {
				
				$value = $this->quoteValue($this->escape($value));
			}
			
			return $this->quoteName(str_replace($matches[1], '', $key)) . ' ' . $matches[1] .' '. $value;
		}
		return $this->quoteName($key) .' = '. $this->quoteValue($this->escape($value));
	}
	
	public function escape($value) {
		return $this->escapeCallback ? $this->escapeCallback->escape($value) : addslashes($value);
	}
}

?>