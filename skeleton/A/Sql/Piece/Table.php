<?php

include_once 'A/Sql/Piece/Abstract.php';

class A_Sql_Piece_Table extends A_Sql_Piece_Abstract {
	protected $table;
	
	public function __construct($table) {
		$this->table = $table;
	}
	
	public function render() {
		return $this->quoteName($this->table);
	}
}
