<?php

class A_Sql_Table {
	protected $table;
	
	public function __construct($table) {
		$this->table = $table;
	}
	
	public function render() {
		if (is_array($this->table)) {
			return implode(', ', $this->table);
		}
		return $this->table;
	}

	public function __toString() {
		return $this->render();
	}

}
