<?php

class A_Sql_Columns {
	protected $columns = array();
	protected $args = array();
	
	public function __construct($args=null) {
		if (is_array($args)) {
			if (isset($args[0]) && is_array($args[0])) {
				$this->columns = $args[0];
			} else {
				$this->columns = $args;
			}
		} else {
			$this->columns = func_get_args();
		}
	}
	
	public function render() {
		if ($this->columns) {
			if (is_array($this->columns) && count($this->columns)) {
				$this->columns = implode(', ', $this->columns);
			}
			return $this->columns;
		}
	}

	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}

	public function __toString() {
		return $this->render();
	}

}
