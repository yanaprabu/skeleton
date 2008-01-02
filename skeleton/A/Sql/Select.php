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
	 * Unsupported
	*/
	protected $having;

	/**
	 * $havingEquation
	 * Unsupported
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
		if (!$this->columns) include_once('A/Sql/Piece/Columns.php');
		$this->columns = new A_Sql_Piece_Columns(func_get_args());
		return $this;
	}

	/**
	 * from()
	*/
	public function from($table) {
		if (!$this->table) include_once('A/Sql/Piece/Table.php');
		$this->table = new A_Sql_Piece_Table($table);
		return $this;
	}

	/**
	 * where()
	*/
	public function where($data, $value=null) {
		if (!$this->whereEquation) include_once ('A/Sql/Piece/Equation.php');
		if (!$this->where) include_once('A/Sql/Piece/List.php');
		$this->whereEquation = new A_Sql_Piece_Equation($data, $value);	
		$this->where = new A_Sql_Piece_List($this->whereEquation);
		return $this;
	}

	/**
	 * join()
	 * Unsupported	 
	 * Do we use the previous join class?
	*/
	public function join($table1, $column1, $table2, $column2) {
		if (!$this->join) include_once('A/Sql/Piece/Join.php');
		$this->join = new A_Sql_Piece_Join($table1, $column1, $table2, $column2);
		return $this;
	}
	
	/**
	 * having()
	*/	
	public function having($data, $value=null) {
		if (!$this->havingEquation) include_once ('A/Sql/Piece/Equation.php');
		if (!$this->having) include_once('A/Sql/Piece/List.php');
		$this->havingEquation = new A_Sql_Piece_Equation($data, $value);	
		$this->having = new A_Sql_Piece_List($this->havingEquation);
		return $this;
	}
	
	/**
	 * groupby()
	 * Unsupported	 
	*/	
	public function groupby() {
		if (!$this->groupby) include_once('A/Sql/Piece/Groupby.php');
		return $this;
	}
	
	/**
	 * orderby()
	 * Unsupported
	*/	
	public function orderby($data, $value=null) {
		if (!$this->orderby) include_once('A/Sql/Piece/Orderby.php');
		return $this;
	}

	/**
	 * setWhereLogic()
	*/
	public function setWhereLogic($logic) {
		$this->whereLogic = $logic; 
	}

	/**
	 * setHavingLogic()
	*/	
	public function setHavingLogic($logic) {
		$this->havingLogic = $logic;
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
		var_dump($this->havingEquation);
			$this->having->setLogic($this->havingLogic);
			$this->havingEquation->setEscapeCallback($db);
		}
		
		$table 	= $this->table->render();
		$columns = $this->columns ? $this->columns->render() : '*';
		$joins 	= $this->joins ? $this->joins->render() : '';
		$where 	= $this->where ? $this->where->render() : '1=1';
		return sprintf('SELECT %s FROM %s %s WHERE %s', $columns, $table, $joins, $where);
	}
}

?>