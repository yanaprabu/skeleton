<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Update extends A_Sql_Statement {
	protected $table;
	protected $data;
	protected $where;
	
	public function table($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	public function set($data, $value=null) {
		include_once('A/Sql/Expression.php');
		$this->data[] = new A_Sql_Expression($data, $value);	
		$this->escapeListeners[] = end($this->data);	
		return $this;
	}
	
	public function where($arg1, $arg2=null, $arg3=null) {
		$this->condition($this->where, $arg1, $arg2, $arg3);
		return $this;		
	}

	public function orWhere($data, $value=null) {
		$this->_condition($this->where, 'OR', $data, $value);
		return $this;		
	}
	
	public function render() {
		if (!$this->table || !$this->data) return;
		$this->notifyListeners();
		
		$table = $this->table->render();
		$joins = ''; //not implemented

		$sets = array();
		if (count($this->data)) {
			foreach ($this->data as $data) {
				$sets[] = $data->render(', ');
			}
		}	
		$set = implode(', ', $sets);

		$where = '';
		if ($this->where) {
			include_once 'A/Sql/LogicalList.php';
			$wherelist = new A_Sql_LogicalList($this->where);
			$where = ' WHERE '. $wherelist->render();
		}
		$this->where = null;

		return "UPDATE $table SET $set$joins$where";

	}

	public function __toString() {
		return $this->render();
	}

}
