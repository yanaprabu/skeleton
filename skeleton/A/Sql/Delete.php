<?php

class A_Sql_Delete {
	protected $table;
	protected $where = array();
	protected $whereExpression;
	
	public function table($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function where($data, $value=null) {
		if (!$this->whereExpression) include_once ('A/Sql/Expression.php');
		if (!$this->where) include_once('A/Sql/List.php');
		$this->whereExpression = new A_Sql_Expression($data, $value);	
		$this->where = new A_Sql_List($this->whereExpression);
		return $this;
	}

	function render($db=null) {
		if ($this->table) {		// must at least specify a table
			if ($this->where) {
				$this->whereExpression->setEscapeCallback($db);
			}
			$table = $this->table->render();
			$where = $this->where ? ' WHERE ' . $this->where->render() : '';

			return "DELETE FROM $table$where";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
