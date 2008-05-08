<?php
require_once 'A/Sql/Statement.php';

class A_Sql_Select extends A_Sql_Statement {
	/**
	 * $table
	*/
	protected $table;

	/**
	 * $columns
	*/
	protected $columns = null;

	/**
	 * $where
	*/
	protected $where = array();
	
	/**
	 * $joins
	*/
	protected $joins = array();

	/**
	 * $having
	*/
	protected $having = null;

	/**
	 * $groupby
	 * Unsupported
	*/
	protected $groupby = null;			

	/** 
	 * $orderby
	 * Unsupported
	*/
	protected $orderby = null;
	
	/**
	 * columns()
	*/
	public function columns() {
		include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	/**
	 * from()
	*/
	public function from($table) {
		include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	/**
	 * join()
	*/
	public function join($table1, $column1, $table2, $column2) {
		include_once('A/Sql/Join.php');
		$this->joins[$table2] = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	*/	
	/**
	 * where()
	*/	
	public function where($arg1, $arg2=null, $arg3=null) {
		$this->condition($this->where, $arg1, $arg2, $arg3);
		return $this;		
	}

    /**
	 * orWhere()
	*/	
	public function orWhere($data, $value=null) {
		$this->condition($this->where, 'OR', $data, $value);
		return $this;		
	}

	/**
	 * having()
	*/	
	public function having($arg1, $arg2=null, $arg3=null) {
		$this->condition($this->having, $arg1, $arg2, $arg3);
		return $this;		
	}

    /**
	 * orHaving()
	*/	
	public function orHaving($data, $value=null) {
		$this->condition($this->having, 'OR', $data, $value);
		return $this;		
	}

	/**
	 * groupby()
	*/	
	public function groupBy($columns) {
		include_once('A/Sql/Columns.php');
		$this->groupby = new A_Sql_Columns($columns);	
		return $this;
	}
	
	/**
	 * orderby()
	*/	
	public function orderBy($columns) {
		include_once('A/Sql/Columns.php');
		$this->orderby = new A_Sql_Columns($columns);	
		return $this;
	}

	/**
	 * render()
	*/
	public function render() {
		if (!$this->table) return;
		$this->notifyListeners();
	
		include_once 'A/Sql/LogicalList.php';
		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins = '';
		if (count($this->joins)) {
			foreach ($this->joins as $join) {
				$joins .= $join->render();
			}
		}
		
		$where = '';
		if ($this->where) {
			$wherelist = new A_Sql_LogicalList($this->where);
			$where = ' WHERE '. $wherelist->render();
		}
		$this->where = null;
		
		$having = '';
		if ($this->having) {
			$havinglist = new A_Sql_LogicalList($this->having);
			$having = ' HAVING '. $havinglist->render();
		}
		$this->having = null;
		
		$orderby = $this->orderby ? ' ORDER BY '. $this->orderby->render() : '';
		$groupby = $this->groupby ? ' GROUP BY '. $this->groupby->render() : '';
		
		return "SELECT $columns FROM $table$joins$having$where$orderby$groupby";
	}

	public function __toString() {
		return $this->render();
	}

}
