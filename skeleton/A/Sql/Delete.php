<?php

class A_Sql_Delete {
	protected $table = null;
	protected $where = null;
	protected $whereExpression;
	
	public function __construct($table=null, $where=array()) {
		$this->table($table);
		$this->where($where);
	}
	
	public function table($table) {
		if ($table) {
			if (!$this->table) include_once('A/Sql/Table.php');
			$this->table = new A_Sql_Table($table);
		}
		return $this;
	}

	public function where($data, $value=null) {
		if ($data) {
			if (!$this->whereExpression) include_once ('A/Sql/Expression.php');
			if (!$this->where) include_once('A/Sql/List.php');
			$this->whereExpression = new A_Sql_Expression($data, $value);	
			$this->where = new A_Sql_List($this->whereExpression);
		}
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
