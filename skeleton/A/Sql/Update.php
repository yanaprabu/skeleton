<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Update extends A_Sql_Statement {
	protected $table;
	protected $where;
	protected $joins = array();	
	protected $set;
	
	public function table($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}	

	public function set($data, $value=null) {
		if (!$this->set) {
			include_once('A/Sql/Set.php');	
			$this->set = new A_Sql_Set();
		}
		$this->set->addExpression($data, $value);
		return $this;
	}
	
	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}	
	
	public function where($arg1, $arg2=null, $arg3=null) {
		if (!$this->where) {
			include_once('A/Sql/Where.php');		
			$this->where = new A_Sql_Where();
		}
		$this->where->addExpression($arg1, $arg2, $arg3);
		return $this;		
	}

	public function orWhere($data, $value=null) {
		if (!$this->where) {
			include_once('A/Sql/Where.php');
			$this->where = new A_Sql_Where();
		}
		$this->where->addExpression('OR', $data, $value);
		return $this;		
	}
	
	public function render() {
		if (!$this->table || !$this->set) return;
		$this->notifyListeners();
		
		$table = $this->table->render();
		$joins = ''; //not implemented
		$set 	 = $this->set->setDb($this->db)->render();
		$where   = $this->where ? ' '. $this->where->setDb($this->db)->render() : '';

		return "UPDATE $table $set$joins$where";
	}

	public function __toString() {
		return $this->render();
	}

}
