<?php

class A_Sql_Delete {
	protected $table;
	protected $where = array();
	protected $whereEquation;
	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function where($data, $value=null) {
		if (!$this->whereEquation) include_once ('A/Sql/Equation.php');
		if (!$this->where) include_once('A/Sql/List.php');
		$this->whereEquation = new A_Sql_Equation($data, $value);	
		$this->where = new A_Sql_List($this->whereEquation);
		return $this;
	}

	function render($db=null) {
		if ($this->table) {		// must at least specify a table
			if ($this->where) {
				$this->whereEquation->setEscapeCallback($db);
			}
			$table = $this->table->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';

			return "DELETE FROM $table$where";
		}
	}

}
