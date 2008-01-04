<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_Table extends A_Sql_Piece_Abstract {
	protected $table;
	
	public function __construct($table) {
		$this->table = $table;
	}
	
	public function render() {
		if (is_array($this->table)) {
			return implode(', ', array_map(array($this, 'quoteName'), $this->table));
		}
		return $this->quoteName($this->table);
	}
}
