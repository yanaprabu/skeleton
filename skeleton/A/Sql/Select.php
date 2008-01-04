<?php

class A_Sql_Select {
	/**
	 * $table
	*/
	protected $table;

	/**
	 * $columns
	*/
	protected $columns;

	/**
	 * $where
	*/
	protected $where;

	/**
	 * $whereEquation
	*/
	protected $whereEquation;

	/**
	 * $whereLogic
	*/
	protected $whereLogic;
	
	/**
	 * $joins
	 * Unsupported
	*/
	protected $joins;

	/**
	 * $having
	*/
	protected $having;

	/**
	 * $havingEquation
	*/
	protected $havingEquation;

	/**
	 * $havingLogic
	*/
	protected $havingLogic;	
	/**
	 * $groupby
	 * Unsupported
	*/
	protected $groupby;			

	/** 
	 * $orderby
	 * Unsupported
	*/
	protected $orderby;
	
	/**
	 * columns()
	*/
	public function columns() {
		if (!$this->columns) include_once('A/Sql/Columns.php');
		$this->columns = new A_Sql_Columns(func_get_args());
		return $this;
	}

	/**
	 * from()
	*/
	public function from($table) {
		if (!$this->table) include_once('A/Sql/Table.php');
		$this->table = new A_Sql_Table($table);
		return $this;
	}

	/**
	 * join()
	 * Unsupported	 
	 * Do we use the previous join class?
	*/
	public function join($table1, $column1, $table2, $column2) {
		if (!$this->join) include_once('A/Sql/Join.php');
		$this->join = new A_Sql_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	 * having()
	*/	
	public function having($data, $value=null) {
		if (!$this->havingEquation) include_once ('A/Sql/Equation.php');
		if (!$this->having) include_once('A/Sql/List.php');
		$this->havingEquation = new A_Sql_Equation($data, $value);	
		$this->having = new A_Sql_List($this->havingEquation);
		return $this;
	}
	
	/**
	 * where()
	*/
	public function where($data, $value=null) {
		if (!$this->whereEquation) include_once ('A/Sql/Equation.php');
		if (!$this->where) include_once('A/Sql/List.php');
		$this->whereEquation = new A_Sql_Equation($data, $value);	
		$this->where = new A_Sql_List($this->whereEquation);
		return $this;
	}

	/**
	 * groupby()
	 * Unsupported	 
	*/	
	public function groupby() {
		if (!$this->groupby) include_once('A/Sql/Groupby.php');
		return $this;
	}
	
	/**
	 * orderby()
	 * Unsupported
	*/	
	public function orderby($data, $value=null) {
		if (!$this->orderby) include_once('A/Sql/Orderby.php');
		return $this;
	}

	/**
	 * setWhereLogic()
	*/
	public function setWhereLogic($logic) {
		$this->whereLogic = $logic; 
		return $this;
	}

	/**
	 * setHavingLogic()
	*/	
	public function setHavingLogic($logic) {
		$this->havingLogic = $logic;
		return $this;
	}

	/**
	 * render()
	*/
	public function render($db=null) {
		if (!$this->table) {
			return;
		}
		if ($this->where) {
			$this->where->setLogic($this->whereLogic);
			$this->whereEquation->setEscapeCallback($db);
		}
		if ($this->having) {
			$this->having->setLogic($this->havingLogic);
			$this->havingEquation->setEscapeCallback($db);
		}
		
		$table = $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins = $this->joins ? $this->joins->render() : '';
		$having = $this->having ? ' HAVING ' . $this->having->render() : '';
		$where = $this->where ? ' WHERE ' . $this->where->render() : '';
		return "SELECT $columns FROM $table $joins$having$where";
	}
}
