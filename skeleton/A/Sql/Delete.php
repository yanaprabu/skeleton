<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Delete extends A_Sql_Statement{
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

	public function where($arg1, $arg2=null, $arg3=null) {
		$this->condition($this->where, $arg1, $arg2, $arg3);
		return $this;		
	}

	public function orWhere($data, $value=null) {
		$this->condition($this->where, 'OR', $data, $value);
		return $this;		
	}
	
	function render() {
		if ($this->table) {
			$this->notifyListeners();
			$table = $this->table->render();
#			$where = $this->where ? ' WHERE ' . $this->where->render() : '';
	
			$where = '';
			if ($this->where) {
				include_once 'A/Sql/LogicalList.php';
				$wherelist = new A_Sql_LogicalList($this->where);
				$where = ' WHERE '. $wherelist->render();
			}
			$this->where = null;

			return "DELETE FROM $table$where";
		}
	}

	public function __toString() {
		return $this->render();
	}

}
