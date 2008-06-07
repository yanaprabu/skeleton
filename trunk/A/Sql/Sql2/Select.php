<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Select extends A_Sql_Statement {
	protected $table;
	protected $columns;
	protected $where;
	protected $joins = array();
	protected $having;
	protected $groupby;			
	protected $orderby;
	
	public function columns() {
		include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	public function from($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
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

	public function having($arg1, $arg2=null, $arg3=null) {
		if (!$this->having) {
			include_once('A/Sql/Having.php');
			$this->having = new A_Sql_Having();
		}
		$this->having->addExpression($arg1, $arg2, $arg3);
		return $this;			
	}

	public function orHaving($data, $value=null) {
		if (!$this->having) {
			include_once('A/Sql/Having.php');
			$this->having = new A_Sql_Having();
		}
		$this->having->addExpression('OR', $data, $value);
		return $this;		
	}

	public function groupBy($columns) {
		include_once('A/Sql/Groupby.php');
		$this->groupby = new A_Sql_Groupby($columns);	
		return $this;
	}

	public function orderBy($columns) {
		include_once('A/Sql/Orderby.php');
		$this->orderby = new A_Sql_Orderby($columns);	
		return $this;
	}

	public function render() {
		if (!$this->table) return;
		
		$joins = '';
		if (count($this->joins)) {
			foreach ($this->joins as $join) {
				$joins .= $join->render();
			}
		}

		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$where   = $this->where ? ' '. $this->where->setDb($this->db)->render() : '';
		$having  = $this->having ? ' '. $this->having->setDb($this->db)->render() : '';
		$orderby = $this->orderby ? ' '. $this->orderby->render() : '';
		$groupby = $this->groupby ? ' '. $this->groupby->render() : '';
		
		return "SELECT $columns FROM $table$joins$having$where$orderby$groupby";
	}

	public function __toString() {
		return $this->render();
	}

}
