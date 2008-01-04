<?php

class A_Sql_Delete {
	protected $table;
	protected $where = array();
	protected $whereEquation;
	protected $whereLogic;
	
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

	public function setWhereLogic($logic) {
		$this->whereLogic = $logic; 
		return $this;
	}

	function render($db=null) {
		if ($this->table) {		// must at least specify a table
			if ($this->where) {
				$this->where->setLogic($this->whereLogic);
				$this->whereEquation->setEscapeCallback($db);
			}
			$table = $this->table->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';

			return "DELETE FROM $table$where";
		}
	}

}
